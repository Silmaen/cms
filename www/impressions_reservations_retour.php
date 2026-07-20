<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
require_once('../cgi-bin/tcpdf/config/tcpdf_config.php');

// Include the main TCPDF library (search the library on the following directories).

$tcpdf_include_dirs = array(
	realpath('../cgi-bin/tcpdf/tcpdf.php')
);
foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
	if (@file_exists($tcpdf_include_path)) {
		require_once($tcpdf_include_path);
		break;
	}
}



class MYPDF extends TCPDF {
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        
		$this->SetDrawColor(0, 0, 0);
		$this->Line(PDF_MARGIN_LEFT, $this->getY(), $this->getPageWidth()-PDF_MARGIN_LEFT, $this->getY());	
			
		// set color for text
		$this->SetTextColor(0, 0, 0);
		$this->writeHTMLCell(100, 5, 10, $this->getY(), utf8_encode("Le matériel prêté par le Comité des Fêtes de Genay est sous la responsabilité de l'adhérent et doit être rendu propre. <b>Tout article manquant ou détérioré vous sera facturé.</b>"), 0, 0, 0, true, 'L', true);
		
		// Page number
		$this->Cell(0, 10, utf8_encode("Créé le ").date("d-m-Y").utf8_encode(" - Imprimé le ").date("d-m-Y")." - Page ".$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

		
    }
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('COMITE DES FETES DE GENAY');
$pdf->SetTitle('ETAT DES RESERVATIONS PAR ARTICLE');
$pdf->SetSubject('ETAT DES RESERVATIONS PAR ARTICLE');
$pdf->SetKeywords('ETAT DES RESERVATIONS PAR ARTICLE');

// set default header data
$contenu='le '.date("d-m-Y");

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
//$pdf->setFooterData(array(0,64,0), array(0,64,128));


// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists('../cgi-bin/tcpdf/lang/fra.php')) {
	require_once('../cgi-bin/tcpdf/lang/fra.php');
	$pdf->setLanguageArray($l);
}


// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);


//////////////////
// HAUT DE PAGE //
//////////////////

//////////////////////////////////////////////
// DEBUT DE LA REQUETE SUR LES RESERVATIONS //
//////////////////////////////////////////////	
$compteur_page = 0;

if(isset($_GET["date_retour_reservation"]) AND $_GET["date_retour_reservation"]<>"")
{
	$_POST["date_retour_reservation"] = $_GET["date_retour_reservation"];
}
else
{
	$_POST["date_retour_reservation"] = GestionDate($_POST["date_retour_reservation"],'1');	
}


$nb_reservations = 0;
// On vérifie s'il y a des résultats => des réservations à rentrer ce jour là
$sql_nb=$connexion->prepare("SELECT COUNT(DISTINCT t1.id_reservation) as resultat FROM reservations_articles AS t1 LEFT JOIN reservations AS t2 ON t2.id_reservation=t1.id_reservation WHERE (t2.date_retour=:date_retour AND t1.quantite_reservee>0 AND t2.id_etat<>3)");
$sql_exec=$sql_nb->execute([":date_retour"=>$_POST["date_retour_reservation"]]);	
if(!$sql_exec) echo "DONNEES : Pb d'accès à la table reservations 1";
else
{
	foreach ($sql_nb->fetchAll() as $row_nb) 
	{

		$nb_reservations = $row_nb["resultat"];
	}
}


if($nb_reservations==0)
{
	//print"test 1";
	
	// CREATION PDF
	$pdf->AddPage();
	
	$_POST["date_retour_reservation"] = GestionDate($_POST["date_retour_reservation"],'0');
	
	$pdf->SetFont('Helvetica', '', 16);
	$pdf->MultiCell(180, 5, utf8_encode("<b>Aucune réservation à rentrer en date du ".$_POST["date_retour_reservation"]." </b>"), 0, 'C', 0, 1, 15, 100, true,'',true);
}
else
{
	//print"test 2";
	$sql=$connexion->prepare("SELECT t1.id_reservation, t1.id_client, t1.date_depart, t1.date_retour, 
	t2.nom, t2.prenom, t2.association, t2.telephone, 
	t3.libelle_statut 
	FROM reservations AS t1 LEFT JOIN clients AS t2 ON t2.id_client=t1.id_client 
	LEFT JOIN clients_statuts AS t3 ON t3.id_statut_client=t2.id_statut_client 
	WHERE (t1.date_retour=:date_retour AND t1.id_etat<>3) ORDER BY t2.association, t2.nom, t2.prenom");
	$sql_exec=$sql->execute([":date_retour"=>$_POST["date_retour_reservation"]]);	
	if(!$sql_exec) echo "DONNEES : Pb d'accès à la table reservations";
	else
	{
		// CREATION PDF
		$pdf->AddPage('L', 'A4');
		$nb_reservations_par_page = 25;
		$compteur_page ++;
		$compteur_reservation = 0;			
		$posy = 0;
		
		foreach ($sql->fetchAll() as $row) 
		{	
			//print"test 3";
			if($compteur_resevations==0)
			{
				//print"test 4";
				$pdf->SetFont('Helvetica', '', 12);
				$pdf->SetTextColor(75, 75, 75);
					
				// Affichage des réservations
				$offset_titre_reservations = 20;
	
				$pdf->SetFont('Helvetica', '', 16);
				$pdf->MultiCell(290, 5, utf8_encode("<b>Liste des réservations avec un retour le ".GestionDate($row["date_retour"],'0')." </b>"), 0, 'C', 0, 1, 5, ($offset_titre_reservations), true,'',true);
				

				// Réservations
				$offset_reservations = ($offset_titre_reservations + 10 + ($compteur_reservation*7));
				
				if(($compteur_page==1 AND $compteur_reservation==0) OR ($compteur_page>1 AND (fmod($compteur_reservation, $nb_reservations_par_page)==0)))
				{
					//print"test 5";

					$pdf->SetFont('Helvetica', '', 12);
					$pdf->MultiCell(45, 7, utf8_encode("<b>Statut</b>"), 0, 'L', 0, 1, 5, ($offset_reservations), true,'',true);
					$pdf->MultiCell(95, 7, utf8_encode("<b>Libellé</b>"), 0, 'L', 0, 1, 50, ($offset_reservations), true,'',true);
					$pdf->MultiCell(45, 7, utf8_encode("<b>Contact</b>"), 0, 'L', 0, 1, 145, ($offset_reservations), true,'',true);
					$pdf->MultiCell(40, 7, utf8_encode("<b>Téléphone</b>"), 0, 'C', 0, 1, 190, ($offset_reservations), true,'',true);
					$pdf->MultiCell(20, 7, utf8_encode("<b>Retour</b>"), 0, 'C', 0, 1, 230, ($offset_reservations), true,'',true);
					$pdf->MultiCell(20, 7, utf8_encode("<b>Chèque</b>"), 0, 'C', 0, 1, 250, ($offset_reservations), true,'',true);
					$pdf->MultiCell(20, 7, utf8_encode("<b>Espèces</b>"), 0, 'C', 0, 1, 270, ($offset_reservations), true,'',true);

					$pdf->SetDrawColor(180, 180, 180);
					$pdf->Line(5, ($offset_reservations + 7), 290, ($offset_reservations + 7));					
				}

				$offset_reservations = ($offset_reservations + 7);				
				
				$pdf->SetFont('Helvetica', '', 12);
				$pdf->MultiCell(45, 7, $row['libelle_statut'], 'R', 'L', 0, 1, 5, ($offset_reservations), true,'',true);
				$pdf->MultiCell(95, 7, $row['association'], 'R', 'L', 0, 1, 50, ($offset_reservations), true,'',true);
				$pdf->MultiCell(45, 7, $row['nom']." ".$row['prenom'], 'R', 'L', 0, 1, 145, ($offset_reservations), true,'',true);
				$pdf->MultiCell(40, 7, $row['telephone'], 'R', 'C', 0, 1, 190, ($offset_reservations), true,'',true);
				$pdf->MultiCell(20, 7, "", 'R', 'C', 0, 1, 230, ($offset_reservations), true,'',true);
				$pdf->MultiCell(20, 7, "", 'R', 'C', 0, 1, 250, ($offset_reservations), true,'',true);
				$pdf->MultiCell(20, 7, "", 0, 'C', 0, 1, 270, ($offset_reservations), true,'',true);
				
				
				$pdf->SetDrawColor(180, 180, 180);
				$pdf->Line(5, ($offset_reservations+7), 290, ($offset_reservations+7));

				
				$compteur_reservation++;
				
				if(fmod($compteur_reservation, $nb_reservations_par_page)==0)
				{
					$pdf->AddPage();
					$compteur_page++;
					$compteur_reservation=0;
				}
				
			}	
			$compteur_page = 0;
		}
	}
}


// ---------------------------------------------------------

//Close and output PDF document

$pdf->Output('fiche-reservations-retour-'.$_POST["date_retour_reservation"].'.pdf', 'D');



//============================================================+
// END OF FILE
//============================================================+

