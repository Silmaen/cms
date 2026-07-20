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

$date_debut_reglements_synthese = $_POST["date_debut_reglements_synthese"];
$date_fin_reglements_synthese = $_POST["date_fin_reglements_synthese"];
	
$_POST["date_debut_reglements_synthese"] = GestionDate($_POST["date_debut_reglements_synthese"],'1');
$_POST["date_fin_reglements_synthese"] = GestionDate($_POST["date_fin_reglements_synthese"],'1');
		
//print"test AA<br />";

		
$sql_clients=$connexion->prepare("SELECT 
t1.id_client, t1.association, t1.nom, t1.prenom, t1.cp, t1.ville 
FROM clients AS t1 
WHERE t1.id_etat=1 
ORDER BY t1.association ASC, t1.nom ASC, t1.prenom ASC");
$sql_exec=$sql_clients->execute();	
if(!$sql_exec) echo "NB - DONNEES : Pb d'accès à la table clients";
else
{
	//print"test AB<br />";
	$id_client_temp=0;
	$row_clients['id_client'] = 0;
	$compteur_lignes_clients = 1;			
	$compteur_client = 0;			
	$compteur_page ++;
	$nb_clients_par_page = 35;
	$offset_titre_clients = 15;
	$offset_titre_colonnes = 25;
	$offset_clients = 0;
	
	$nb_adhesions = 0;
	$nb_dons = 0;
	$total_dons = 0;
	$total_adhesions = 0;
	
	foreach ($sql_clients->fetchAll() as $row_clients) 
	{

		//print "id_client=".$row_clients['id_client']." <br />";
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
			//print"test AD<br />";
			$row_clients["premier_affichage"] = 1;
			$row_clients['client']=$row_clients['client'];	
			$position_x_trait = 5;
			$longueur_trait = 290;			
		}	

		$row_clients['client'] = $row_clients['nom']." ".$row_clients['prenom'];
		$row_clients['dons_clients']=0;
		$row_clients['montant_adhesion']=0;

		$sql_adhesion=$connexion->prepare("SELECT 
		t1.annee AS annee_adhesion, t1.montant AS montant_adhesion 
		FROM clients_adhesions AS t1 
		WHERE (t1.id_client=:id_client AND t1.date_modification>=:date_debut_reglements_synthese AND t1.date_modification<=:date_fin_reglements_synthese AND t1.montant>0)");
		$sql_exec=$sql_adhesion->execute([":id_client"=>$row_clients["id_client"], ":date_debut_reglements_synthese"=>$_POST["date_debut_reglements_synthese"], ":date_fin_reglements_synthese"=>$_POST["date_fin_reglements_synthese"]]);	
		if(!$sql_exec) echo "NB - DONNEES : Pb d'accès à la table clients adhésion";
		else
		{
			//print"test AE2<br />";
			foreach ($sql_adhesion->fetchAll() as $row_adhesion) 
			{						
				//$row_clients['montant_adhesion']= $row_adhesion['montant_adhesion'];
				$total_adhesions += $row_adhesion['montant_adhesion'];
				$nb_adhesions ++;
				//print "nb_adhesions=".$nb_adhesions."< br/>";
				$row_clients['montant_adhesion'] += $row_adhesion['montant_adhesion'];
			}	
		}	
	
		//print"id_client=".$row_clients["id_client"]." date_debut_reglements_synthese=".$_POST["date_debut_reglements_synthese"]." date_fin_reglements_synthese=".$_POST["date_fin_reglements_synthese"]."<br />";		
				
				
		// ON CALCULE LES DONS POUR CHAQUE CLIENT
		$row_dons['somme_dons']=0;
		$sql_dons=$connexion->prepare("SELECT t1.id_client, t1.id_reservation, SUM(t1.don) as somme_dons, t1.id_etat, t1.date_retour 
		FROM reservations AS t1 
		WHERE (t1.id_client=:id_client AND t1.don>0 AND (t1.id_etat=1 OR t1.id_etat=2) AND t1.date_retour>=:date_debut_reglements_synthese AND t1.date_retour<=:date_fin_reglements_synthese)");
		$sql_exec=$sql_dons->execute([":id_client"=>$row_clients["id_client"], ":date_debut_reglements_synthese"=>$_POST["date_debut_reglements_synthese"], ":date_fin_reglements_synthese"=>$_POST["date_fin_reglements_synthese"]]);	
		
		//$sql_dons->debugDumpParams();
		if(!$sql_exec) echo "NB - DONNEES : Pb d'accès à la table reservations</br >";
		else
		{
			//print"test AF<br />";
			
			foreach ($sql_dons->fetchAll() as $row_dons) 
			{
				$row_clients['dons_clients']+=$row_dons['somme_dons'];
			}
		}		
			
			
		/*if($row_clients['montant_adhesion']>0)
		{
			$nb_adhesions ++;
			$total_adhesions += $row_clients['montant_adhesion'];			
		}	
		*/
		if($row_clients['dons_clients']>0)
		{
			$nb_dons ++;
			$total_dons += $row_clients['dons_clients'];			
		}
			
		if($row_clients['montant_adhesion']==0 AND $row_clients['dons_clients']==0)
		{
			
		}
		else
		{			
			if((fmod($compteur_client, $nb_clients_par_page)==0) AND ( $row_clients['premier_affichage']==1 OR ($row_clients['premier_affichage']==0) ) )
			{
				$pdf->AddPage('P', 'A4');
				$compteur_client=0;
				$posy = 0;
				
				$pdf->SetFont('Helvetica', '', 16);
				$pdf->MultiCell(200, 5, utf8_encode("<b>Synthèse des réglements perçus entre le ".$date_debut_reglements_synthese." et le ".$date_fin_reglements_synthese."</b>"), 0, 'C', 0, 1, 5, ($offset_titre_clients), true,'',true);
				
				$pdf->SetFont('Helvetica', '', 12);
				$pdf->MultiCell(10, 7, utf8_encode("<b>Nb</b>"), 0, 'L', 0, 1, 5, ($offset_titre_colonnes), true,'',true);
				$pdf->MultiCell(75, 7, utf8_encode("<b>Association</b>"), 0, 'L', 0, 1, 15, ($offset_titre_colonnes), true,'',true);
				$pdf->MultiCell(75, 7, utf8_encode("<b>Client</b>"), 0, 'L', 0, 1, 90, ($offset_titre_colonnes), true,'',true);
				$pdf->MultiCell(25, 7, utf8_encode("<b>Adhésion</b>"), 0, 'C', 0, 1, 165, ($offset_titre_colonnes), true,'',true);
				$pdf->MultiCell(20, 7, utf8_encode("<b>Dons</b>"), 0, 'C', 0, 1, 190, ($offset_titre_colonnes), true,'',true);
				
			
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

				$pdf->MultiCell(75, 5, $row_clients['association'], 0, 'L', 0, 1, 15, (($offset_clients+1)), true,'',true);

				$pdf->MultiCell(75, 5, $row_clients['client'], 0, 'L', 0, 1, 90, (($offset_clients+1)), true,'',true);

				$pdf->MultiCell(25, 5, $row_clients['montant_adhesion'], 0, 'C', 0, 1, 165, (($offset_clients+1)), true,'',true);
				
				$pdf->MultiCell(20, 5, $row_clients['dons_clients'], 0, 'C', 0, 1, 190, (($offset_clients+1)), true,'',true);

				$compteur_client ++;			
			}
			$compteur_lignes_clients ++;
		}
	}
}			
			
			
// AFFICHAGE DES TOTAUX			
if($offset_clients>=230)	
{		
	$pdf->AddPage('P', 'A4');
	$offset_clients = 40;
}

$pdf->MultiCell(80, 5, utf8_encode("Nombre d'adhésions : ").$nb_adhesions, 0, 'L', 0, 1, 30, (($offset_clients+20)), true,'',true);

$pdf->MultiCell(80, 5, utf8_encode("Montant total des adhésions : ").$total_adhesions.utf8_encode(" €"), 0, 'L', 0, 1, 110, (($offset_clients+20)), true,'',true);

$pdf->MultiCell(80, 5, "Nombre de donneurs : ".$nb_dons, 0, 'L', 0, 1, 30, (($offset_clients+30)), true,'',true);

$pdf->MultiCell(80, 5, "Montant total des dons : ".$total_dons.utf8_encode(" €"), 0, 'L', 0, 1, 110, (($offset_clients+30)), true,'',true);

$pdf->SetFont('Helvetica', 'i', 9);
$pdf->MultiCell(200, 5, utf8_encode("DONS : Cette synthèse intègre les dons reçus pour les réservations dont la date de retour est comprise dans la période demandée."), 0, 'C', 0, 1, 3, (($offset_clients+40)), true,'',true);
$pdf->MultiCell(200, 5, utf8_encode("ADHESIONS : Cette synthèse intègre les adhésions affectées à l'année en cours (période comprise entre le 01/10 et le 31/09)."), 0, 'C', 0, 1, 3, (($offset_clients+45)), true,'',true);


// ---------------------------------------------------------
//Close and output PDF document

$pdf->Output('fiche-reglements-synthese'.$_POST["date_debut_reglements_synthese"].'-'.$_POST["date_fin_reglements_synthese"].'-synthese.pdf', 'D');

//============================================================+
// END OF FILE                                                
//============================================================+