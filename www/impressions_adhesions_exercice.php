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
        // Page number
        $this->Cell(0, 10, utf8_encode("Créé le ").date("d-m-Y").utf8_encode(" - Imprimé le ").date("d-m-Y")." - Page ".$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		
		$this->SetDrawColor(0, 0, 0);
		$this->Line(PDF_MARGIN_LEFT, $this->getY(), $this->getPageWidth()-PDF_MARGIN_LEFT, $this->getY());	
		
    }
}



// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);




// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('COMITE DES FETES DE GENAY');
$pdf->SetTitle('LISTE DES ADHESIONS AFFECTEES A L EXERCICE');
$pdf->SetSubject('LISTE DES ADHESIONS AFFECTEES A L EXERCICE');
$pdf->SetKeywords('LISTE DES ADHESIONS AFFECTEES A L EXERCICE');

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
$total_adhesions = 0;
$compteur_adhesions = 0;
$compteur_adhesions_page = 0;
$offset_adhesions = 0;

$sql_adhesion=$connexion->prepare("SELECT 
t1.annee AS annee_adhesion, t1.montant AS montant_adhesion,
t2.nom, t2.prenom, t2.association 
FROM clients_adhesions AS t1 
LEFT JOIN clients AS t2 ON t2.id_client=t1.id_client  
WHERE (t1.annee=:annee_fiscale_reference AND t1.montant>0)
ORDER BY t2.association ASC, t2.nom ASC, t2.prenom ASC");
$sql_exec=$sql_adhesion->execute([":annee_fiscale_reference"=>$_POST["annee_fiscale_reference_adhesion"]]);	
if(!$sql_exec) echo "NB - DONNEES : Pb d'accès à la table clients adhésion";
else
{
	foreach ($sql_adhesion->fetchAll() as $row_adhesion) 
	{						
		$total_adhesions += $row_adhesion['montant_adhesion'];

		$compteur_lignes_adhesions ++;			
	
		$nb_adhesions_par_page = 34;
		$offset_titre_adhesions = 15;
		$offset_titre_colonnes = 35;
		
		
		if($compteur_adhesions == 0)
		{
			$row_adhesion["premier_affichage"] = 0;
		}
		else
		{
			$row_adhesion["premier_affichage"] = 1;
		}		
		
		$compteur_adhesions ++;
		$compteur_adhesions_page ++;
		$row_adhesion['client'] = $row_adhesion['nom']." ".$row_adhesion['prenom'];
				
	

		if((fmod($compteur_adhesions, $nb_adhesions_par_page)==0) or $row_adhesion["premier_affichage"]==0)
		{			
			$pdf->AddPage('P', 'A4');
			$compteur_adhesions_page = 1;
			$posy = 0;
			
			$pdf->SetFont('Helvetica', '', 16);
			$pdf->MultiCell(200, 5, utf8_encode("<b>Liste des adhésions affectées à l'année fiscale de référence : ".$_POST["annee_fiscale_reference_adhesion"]."-".($_POST["annee_fiscale_reference_adhesion"]+1)."</b>"), 0, 'C', 0, 1, 5, ($offset_titre_adhesions), true,'',true);
			
			$pdf->SetFont('Helvetica', '', 10);
			$pdf->MultiCell(10, 7, utf8_encode("<b>Nb</b>"), 0, 'L', 0, 1, 5, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(80, 7, utf8_encode("<b>Association</b>"), 0, 'L', 0, 1, 15, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(80, 7, utf8_encode("<b>Adhérent</b>"), 0, 'L', 0, 1, 105, ($offset_titre_colonnes), true,'',true);			
			$pdf->MultiCell(20, 7, utf8_encode("<b>Montant</b>"), 0, 'L', 0, 1, 185, ($offset_titre_colonnes), true,'',true);			
		
			$pdf->SetDrawColor(180, 180, 180);
			$pdf->Line(205, ($offset_titre_colonnes+7), 5, ($offset_titre_colonnes+7));

			// Footer	
			$aujourdhui = date("d-m-Y");
			$pdf->SetFont('Helvetica', '', 8);	
			$pdf->MultiCell(85, 15, utf8_encode("Créé le ").$aujourdhui.utf8_encode(" <br />Imprimé le ").$aujourdhui, 0, 'R', 0, 1, 198, 193, true,'',true);			
		}
				
		$offset_adhesions = ($offset_titre_colonnes + ($compteur_adhesions_page*7));

		$pdf->SetDrawColor(180, 180, 180);
		$pdf->Line(205, ($offset_adhesions+7), 5, ($offset_adhesions+7));

		$pdf->SetFont('Helvetica', '', 10);
		$pdf->MultiCell(10, 5, $compteur_lignes_adhesions, 0, 'L', 0, 1, 5, (($offset_adhesions+1)), true,'',true);
		$pdf->MultiCell(80, 5, $row_adhesion['association'], 0, 'L', 0, 1, 15, (($offset_adhesions+1)), true,'',true);
		$pdf->MultiCell(80, 5, $row_adhesion['client'], 0, 'L', 0, 1, 105, (($offset_adhesions+1)), true,'',true);
		$pdf->MultiCell(20, 5, $row_adhesion['montant_adhesion'], 0, 'C', 0, 1, 185, (($offset_adhesions+1)), true,'',true);

	}
}			
			
// AFFICHAGE DES TOTAUX			
if($offset_adhesions>=230)	
{		
	$pdf->AddPage('P', 'A4');
	$offset_adhesions = $offset_titre_colonnes + 7;
} 

$pdf->MultiCell(80, 5, utf8_encode("Nombre d'adhésions : ").$compteur_adhesions, 0, 'L', 0, 1, 30, (($offset_adhesions+20)), true,'',true);

$pdf->MultiCell(80, 5, utf8_encode("Montant total des adhésions : ").$total_adhesions.utf8_encode(" €"), 0, 'L', 0, 1, 110, (($offset_adhesions+20)), true,'',true);

$pdf->SetFont('Helvetica', 'i', 9);

$pdf->MultiCell(200, 5, utf8_encode("ADHESIONS : Cette synthèse intègre les adhésions affectées à l'exercice ".$_POST["annee_fiscale_reference_adhesion"]." - ".($_POST["annee_fiscale_reference_adhesion"]+1)." (période comprise entre le 01/10 et le 31/09)."), 0, 'C', 0, 1, 3, (($offset_adhesions+40)), true,'',true);	


// ---------------------------------------------------------
//Close and output PDF document

$pdf->Output('fiche-adhesions-'.$_POST["annee_fiscale_reference_adhesion"]."-".($_POST["annee_fiscale_reference_adhesion"]+1).'.pdf', 'D');


//============================================================+
// END OF FILE
//============================================================+

