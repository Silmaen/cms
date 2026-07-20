<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
require_once('../cgi-bin/tcpdf/lang/fra.php');
require_once('../cgi-bin/tcpdf/config/tcpdf_config.php');
require_once('../cgi-bin/tcpdf/tcpdf.php');



/* TEST CONNEXION IDENTIFIANT */
if (!gestionIdentification($connexion))
{
	header("Location:index.php");
	exit();
}


	

///////////////////////////////////////////////
// CREATION DU PDF
///////////////////////////////////////////////

    
    

// create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Comité des Fêtes de Genay');
$pdf->SetTitle('Comité des Fêtes de Genay - Fiche Synthèse des réglements perçus');
$pdf->SetSubject('Comité des Fêtes de Genay - Fiche Synthèse des réglements perçus');
$pdf->SetKeywords('Fiche Synthèse des réglements perçus');

// set default header data
$contenu='le '.date("d-m-Y");


//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, utf8_encode('Etiquettes du Dossier Intervention SAV N° '), utf8_encode($contenu));
$pdf->SetFooterData(utf8_encode(date("d-m-Y")." - Document g&eacute;n&eacute;r&eacute; par www.cdf-genay.com"));


// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// --------------------------------------------------------

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

//////////////////
// HAUT DE PAGE //
//////////////////

//////////////////////////////////////////////
// DEBUT DE LA REQUETE SUR LES RESERVATIONS //
//////////////////////////////////////////////	
$compteur_page = 0;

$date_debut_reservations_adhesions = $_POST["date_debut_reservations_adhesions"];
$date_fin_reservations_adhesions = $_POST["date_fin_reservations_adhesions"];
	
$_POST["date_debut_reservations_adhesions"] = GestionDate($_POST["date_debut_reservations_adhesions"],'1');
$_POST["date_fin_reservations_adhesions"] = GestionDate($_POST["date_fin_reservations_adhesions"],'1');


	
$date_fiscale_reference = explode("-", date("Y-m-d"));
$annee_reference_temp = $date_fiscale_reference[0]; 
$mois_reference_temp = $date_fiscale_reference[1]; 

$annee_fiscale_reference = $_POST["annee_fiscale_reference"];
			

$sql_clients=$connexion->prepare("SELECT t1.id_client, t1.association, t1.nom, t1.prenom, t1.cp, t1.ville, t1.id_statut_client, t2.annee_reservation, t3.montant, t3.date_modification, t3.annee FROM clients AS t1 LEFT JOIN reservations AS t2 ON t2.id_client=t1.id_client LEFT JOIN clients_adhesions AS t3 ON t3.id_client=t1.id_client WHERE t1.id_statut_client<>2 AND t1.id_etat=1 AND t2.id_etat<3 AND t2.annee_reservation=:annee_fiscale_reference AND t3.annee=:annee_fiscale_reference AND (t3.montant=0 OR t3.montant=null) GROUP BY t1.id_client ORDER BY t1.association ASC, t1.nom ASC, t1.prenom");

$sql_exec=$sql_clients->execute([":annee_fiscale_reference"=>$annee_fiscale_reference]);	
if(!$sql_exec) echo "NB - DONNEES : Pb d'accès à la table clients";
else
{
	//print"test A <br />";
	
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
		//print"test B <br />";

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
			//print"test C <br />";

			$pdf->AddPage('P', 'A4');
			$compteur_client=0;
			$posy = 0;
			
			$pdf->SetFont('Helvetica', '', 16);
			$pdf->MultiCell(200, 5, utf8_encode("<b>Liste des adhérents ayant fait au moins une réservation sans avoir payé d'adhésion pour l'année fiscale de référence : ".$annee_fiscale_reference."-".($annee_fiscale_reference+1)."</b>"), 0, 'C', 0, 1, 5, ($offset_titre_clients), true,'',true);
			
			$pdf->SetFont('Helvetica', '', 12);
			$pdf->MultiCell(10, 7, utf8_encode("<b>Nb</b>"), 0, 'L', 0, 1, 5, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(100, 7, utf8_encode("<b>Association</b>"), 0, 'L', 0, 1, 15, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(100, 7, utf8_encode("<b>Client</b>"), 0, 'L', 0, 1, 115, ($offset_titre_colonnes), true,'',true);			
		
			// Footer	
			$aujourdhui = date("d-m-Y");
			$pdf->SetFont('Helvetica', '', 8);	
			$pdf->MultiCell(85, 15, utf8_encode("Créé le ").$aujourdhui.utf8_encode(" <br />Imprimé le ").$aujourdhui, 0, 'R', 0, 1, 198, 193, true,'',true);
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
			$pdf->SetFont('Helvetica', '', 12);

			$pdf->MultiCell(10, 5, $compteur_lignes_clients, 0, 'L', 0, 1, 5, (($offset_clients+1)), true,'',true);

			$pdf->MultiCell(100, 5, $row_clients['association'], 0, 'L', 0, 1, 15, (($offset_clients+1)), true,'',true);

			$pdf->MultiCell(100, 5, $row_clients['client'], 0, 'L', 0, 1, 115, (($offset_clients+1)), true,'',true);
			
			$compteur_client ++;			
		}
		$compteur_lignes_clients ++;
	}
}			
			
	


// ---------------------------------------------------------
//Close and output PDF document

$pdf->Output('fiche-reservations-adhesions-'.$_POST["date_debut_reglements_synthese"].'-'.$_POST["date_fin_reglements_synthese"].'.pdf', 'D');

//============================================================+
// END OF FILE                                                
//============================================================+