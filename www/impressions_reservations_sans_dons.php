<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
require_once('../vendor/tcpdf/config/tcpdf_config.php');

// Include the main TCPDF library (search the library on the following directories).

$tcpdf_include_dirs = array(
	realpath('../vendor/tcpdf/tcpdf.php')
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
		$this->Cell(0, 10, utf8_encode("Cr�� le ").date("d-m-Y").utf8_encode(" - Imprim� le ").date("d-m-Y")." - Page ".$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

		$this->SetDrawColor(0, 0, 0);
		$this->Line(PDF_MARGIN_LEFT, $this->getY(), $this->getPageWidth()-PDF_MARGIN_LEFT, $this->getY());

	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('COMITE DES FETES DE GENAY');
$pdf->SetTitle('LISTE DES ADHERENTS AVEC RESA SANS DON');
$pdf->SetSubject('LISTE DES ADHERENTS AVEC RESA SANS DON');
$pdf->SetKeywords('LISTE DES ADHERENTS AVEC RESA SANS DON');

// set default header data
$contenu='le '.date("d-m-Y");

// set default header data

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
if (@file_exists('../vendor/tcpdf/lang/fra.php')) {
	require_once('../vendor/tcpdf/lang/fra.php');
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

$date_fiscale_reference = explode("-", date("Y-m-d"));
$annee_reference_temp = $date_fiscale_reference[0];
$mois_reference_temp = $date_fiscale_reference[1];

$annee_fiscale_reference_dons = $_POST["annee_fiscale_reference_dons"];

$sql_clients=$connexion->prepare("SELECT t1.id_client, t1.id_reservation, t1.annee_reservation, COUNT(t1.id_reservation) as nombre_reservations, SUM(t1.don) as total_don, t2.association, t2.nom, t2.prenom FROM reservations AS t1 LEFT JOIN clients AS t2 ON t2.id_client=t1.id_client
WHERE t2.id_statut_client<>2 AND t1.annee_reservation=:annee_fiscale_reference AND t1.id_client AND t1.id_etat<>3 AND t1.date_retour<='".date("Y-m-d")."' GROUP BY t1.id_client HAVING SUM(t1.don) = 0
ORDER BY t2.association ASC, t2.nom ASC, t2.prenom ASC;");

$sql_exec=$sql_clients->execute([":annee_fiscale_reference"=>$annee_fiscale_reference_dons]);
if(!$sql_exec) echo "NB - DONNEES : Pb d'acc�s � la table clients";
else
{
	$id_client_temp=0;
	$row_clients['id_client'] = 0;
	$compteur_lignes_clients = 1;
	$compteur_client = 0;
	$compteur_page ++;
	$nb_clients_par_page = 35;
	$offset_titre_clients = 15;
	$offset_titre_colonnes = 35;
	$offset_clients = 0;

	foreach ($sql_clients->fetchAll() as $row_clients)
	{
		// TRAITEMENT DES DONNEES
		if($id_client_temp == $row_clients["id_client"])
		{
			$row_clients["premier_affichage"] = 0;
			$row_clients['client']='';
			$position_x_trait = 175;
			$longueur_trait = 290;
		}
		else
		{
			$row_clients["premier_affichage"] = 1;
			$row_clients['client']=$row_clients['client'];
			$position_x_trait = 5;
			$longueur_trait = 290;
		}

		$row_clients['client'] = $row_clients['nom']." ".$row_clients['prenom'];

		if((fmod($compteur_client, $nb_clients_par_page)==0) AND ( $row_clients['premier_affichage']==1 OR ($row_clients['premier_affichage']==0) ) )
		{
			$pdf->AddPage('P', 'A4');
			$compteur_client=0;
			$posy = 0;

			$pdf->SetFont('Helvetica', '', 16);
			$pdf->MultiCell(200, 5, utf8_encode("<b>Liste des adh�rents ayant fait au moins une r�servation sans avoir pay� de don pour l'ann�e fiscale de r�f�rence : ".$annee_fiscale_reference_dons."-".($annee_fiscale_reference_dons+1)."</b>"), 0, 'C', 0, 1, 5, ($offset_titre_clients), true,'',true);

			$pdf->SetFont('Helvetica', '', 10);
			$pdf->MultiCell(10, 7, utf8_encode("<b>Nb7</b>"), 0, 'L', 0, 1, 5, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(80, 7, utf8_encode("<b>Association</b>"), 0, 'L', 0, 1, 15, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(80, 7, utf8_encode("<b>Adh�rent</b>"), 0, 'L', 0, 1, 95, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(40, 7, utf8_encode("<b>Nb de r�servations</b>"), 0, 'L', 0, 1, 175, ($offset_titre_colonnes), true,'',true);

			// Footer
			$aujourdhui = date("d-m-Y");
			$pdf->SetFont('Helvetica', '', 8);
			$pdf->MultiCell(85, 15, utf8_encode("Cr�� le ").$aujourdhui.utf8_encode(" <br />Imprim� le ").$aujourdhui, 0, 'R', 0, 1, 198, 193, true,'',true);
		}

		// Clients
		$offset_clients = ($offset_titre_colonnes + ($compteur_client*7));

		if($compteur_client==0 OR $compteur_client==$nb_clients_par_page)
		{
			$position_x_trait = 5;
		}

		$pdf->SetDrawColor(180, 180, 180);
		$pdf->Line($longueur_trait, ($offset_clients+7), $position_x_trait, ($offset_clients+7));

		$offset_clients = ($offset_clients + 7);

		if($row_clients['premier_affichage']==1 )
		{
			$pdf->SetFont('Helvetica', '', 10);
			$pdf->MultiCell(10, 5, $compteur_lignes_clients, 0, 'L', 0, 1, 5, (($offset_clients+1)), true,'',true);
			$pdf->MultiCell(80, 5, $row_clients['association'], 0, 'L', 0, 1, 15, (($offset_clients+1)), true,'',true);
			$pdf->MultiCell(80, 5, $row_clients['client'], 0, 'L', 0, 1, 95, (($offset_clients+1)), true,'',true);
			$pdf->MultiCell(40, 5, $row_clients['nombre_reservations'], 0, 'C', 0, 1, 175, (($offset_clients+1)), true,'',true);

			$compteur_client ++;
		}
		$compteur_lignes_clients ++;
	}

}

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('fiche-reservations-sans-dons-'.$annee_fiscale_reference_dons.'-'.($annee_fiscale_reference_dons+1).'.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
