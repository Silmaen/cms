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
$pdf->SetTitle('LISTE DES DONS DES RETOURS DANS UNE PERIODE');
$pdf->SetSubject('LISTE DES DONS DES RETOURS DANS UNE PERIODE');
$pdf->SetKeywords('LISTE DES DONS DES RETOURS DANS UNE PERIODE');

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

$date_debut_reglements_detail = $_POST["date_debut_reglements_detail"];
$date_fin_reglements_detail = $_POST["date_fin_reglements_detail"];

$_POST["date_debut_reglements_detail"] = GestionDate($_POST["date_debut_reglements_detail"],'1');
$_POST["date_fin_reglements_detail"] = GestionDate($_POST["date_fin_reglements_detail"],'1');

// GESTION DES DONS

$sql_dons=$connexion->prepare("SELECT
t1.id_client, t1.association, t1.nom, t1.prenom, t1.cp, t1.ville, t1.id_etat,
t2.don, t2.date_don, t2.date_retour , t2.date_depart
FROM clients AS t1
LEFT JOIN reservations AS t2 ON t2.id_client=t1.id_client
WHERE (t1.id_etat=1 AND t2.don>0 AND t2.date_retour>=:date_debut_reglements_detail AND t2.date_retour<=:date_fin_reglements_detail AND (t2.id_etat=1 OR t2.id_etat=2) )
ORDER BY t1.association ASC, t1.nom ASC, t1.prenom ASC, t2.date_don");
$sql_exec=$sql_dons->execute([":date_debut_reglements_detail"=>$_POST["date_debut_reglements_detail"], ":date_fin_reglements_detail"=>$_POST["date_fin_reglements_detail"]]);

if(!$sql_exec) echo "NB - DONNEES : Pb d'acc�s � la table reservations";
else
{
	$id_client_temp=0;
	$row_dons['client'] = '';
	$compteur_lignes_clients = 1;
	$compteur_client = 0;
	$compteur_page ++;
	$nb_clients_par_page = 32;
	$offset_titre_clients = 15;
	$offset_titre_colonnes = 40;
	$offset_clients = 0;

	$nb_dons = 0;
	$total_dons = 0;

	foreach ($sql_dons->fetchAll() as $row_dons)
	{
		$row_dons['client'] = $row_dons['nom']." ".$row_dons['prenom'];

		// TRAITEMENT DES DONNEES
		if($id_client_temp == $row_dons["id_client"])
		{
			$row_dons["premier_affichage"] = 0;
			$row_dons['client']='';
			$position_x_trait = 175;
			$longueur_trait = 290;
		}
		else
		{
			$row_dons["premier_affichage"] = 1;
			$row_dons['client']=$row_dons['client'];
			$position_x_trait = 5;
			$longueur_trait = 290;
		}

		$nb_dons ++;
		$total_dons += $row_dons['don'];

		if((fmod($compteur_client, $nb_clients_par_page)==0) AND ( $row_dons['premier_affichage']==1 OR ($row_dons['premier_affichage']==0) ) )
		{
			$pdf->AddPage('P', 'A4');
			$compteur_client=0;
			$posy = 0;

			$pdf->SetFont('Helvetica', '', 16);
			$pdf->MultiCell(200, 5, utf8_encode("<b>D�tail des dons pour des retours entre le ".$date_debut_reglements_detail." et le ".$date_fin_reglements_detail."</b>"), 0, 'C', 0, 1, 5, ($offset_titre_clients), true,'',true);

			$pdf->SetFont('Helvetica', '', 8);
			$pdf->MultiCell(10, 7, utf8_encode("<b>Nb</b>"), 0, 'L', 0, 1, 5, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(50, 7, utf8_encode("<b>Association</b>"), 0, 'L', 0, 1, 15, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(50, 7, utf8_encode("<b>Adh�rent</b>"), 0, 'L', 0, 1, 65, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(25, 7, utf8_encode("<b>D�part le</b>"), 0, 'C', 0, 1, 115, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(25, 7, utf8_encode("<b>Retour le</b>"), 0, 'C', 0, 1, 140, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(25, 7, utf8_encode("<b>Date Don</b>"), 0, 'C', 0, 1, 165, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(20, 7, utf8_encode("<b>Montant</b>"), 0, 'C', 0, 1, 190, ($offset_titre_colonnes), true,'',true);

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

		if($row_dons['premier_affichage']==1 )
		{
			$pdf->SetFont('Helvetica', '', 8);

			$pdf->MultiCell(10, 5, $compteur_lignes_clients, 0, 'L', 0, 1, 5, (($offset_clients+1)), true,'',true);
			$pdf->MultiCell(50, 5, $row_dons['association'], 0, 'L', 0, 1, 15, (($offset_clients+1)), true,'',true);
			$pdf->MultiCell(50, 5, $row_dons['client'], 0, 'L', 0, 1, 65, (($offset_clients+1)), true,'',true);
			$pdf->MultiCell(25, 5, GestionDate($row_dons['date_depart'],'0'), 0, 'C', 0, 1, 115, (($offset_clients+1)), true,'',true);
			$pdf->MultiCell(25, 5, GestionDate($row_dons['date_retour'],'0'), 0, 'C', 0, 1, 140, (($offset_clients+1)), true,'',true);
			$pdf->MultiCell(25, 5, GestionDate($row_dons['date_don'],'0'), 0, 'C', 0, 1, 165, (($offset_clients+1)), true,'',true);
			$pdf->MultiCell(20, 5, $row_dons['don'], 0, 'C', 0, 1, 190, (($offset_clients+1)), true,'',true);

			$compteur_client ++;
		}
		$compteur_lignes_clients ++;

	}

	// AFFICHAGE DES TOTAUX
	if($offset_clients>=230)
	{
		$pdf->AddPage('P', 'A4');
		$offset_clients = 40;

		$pdf->SetFont('Helvetica', '', 16);
		$pdf->MultiCell(200, 5, utf8_encode("<b>D�tail des donc pour des retours entre le ".$date_debut_reglements_detail." et le ".$date_fin_reglements_detail."</b>"), 0, 'C', 0, 1, 5, ($offset_titre_clients), true,'',true);

	}

	$pdf->MultiCell(80, 5, "Nombre de dons : ".$nb_dons, 0, 'L', 0, 1, 30, (($offset_clients+20)), true,'',true);

	$pdf->MultiCell(80, 5, "Montant total des dons : ".$total_dons.utf8_encode(" �"), 0, 'L', 0, 1, 130, (($offset_clients+20)), true,'',true);

	$pdf->SetFont('Helvetica', '', 9);
	$pdf->MultiCell(180, 5, utf8_encode("(Cette synth�se int�gre les dons re�us pour les r�servations dont la date de retour est comprise dans la p�riode demand�e.)"), 0, 'L', 0, 1, 20, (($offset_clients+27)), true,'',true);

}

// ---------------------------------------------------------
//Close and output PDF document

$pdf->Output('fiche-dons-des-retours-'.$_POST["date_debut_reglements_detail"].'-'.$_POST["date_fin_reglements_detail"].'.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
