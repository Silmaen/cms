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
		$this->writeHTMLCell(270, 5, 10, $this->getY(), utf8_encode("Le matériel prêté par le Comité des Fêtes de Genay est sous la responsabilité de l'adhérent et doit être rendu propre. <b>Tout article manquant ou détérioré vous sera facturé.</b>"), 0, 0, 0, true, 'L', true);
		
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

$_POST["date_depart_article"] = GestionDate($_POST["date_depart_article"],'1');	
		
		
		
// RECUPERATION DE L'INVENTAIRE LE PLUS RECENT
$sql=$connexion->prepare("SELECT id_inventaire FROM inventaires WHERE id_etat=1 ORDER BY date_inventaire DESC LIMIT 1");
$sql_exec=$sql->execute();	
if(!$sql_exec) echo "INVENTAIRE : Pb d'accès à la table INVENTAIRES";
else
{
	foreach ($sql->fetchAll() as $row) 
	{
		$id_inventaire_selectionne = $row["id_inventaire"];
	}
}
		
$sql=$connexion->prepare("SELECT t1.id_article, t4.designation, t1.quantite_totale, t2.quantite_reservee, t2.id_reservation, t3.date_depart, t3.date_retour, t5.id_client, t5.nom, t5.prenom FROM inventaires_articles AS t1 LEFT JOIN reservations_articles AS t2 ON t2.id_article=t1.id_article LEFT JOIN reservations AS t3 On t3.id_reservation=t2.id_reservation LEFT JOIN articles AS t4 ON t4.id_article=t1.id_article LEFT JOIN clients AS t5 ON t5.id_client=t3.id_client WHERE (t1.id_inventaire=:id_inventaire AND t3.id_etat=1 AND t4.id_etat=1 AND t3.date_depart<=:date_depart AND t3.date_retour>:date_depart) ORDER BY t4.designation ASC, t2.quantite_reservee DESC, t5.nom ASC, t5.prenom ASC, t3.date_depart");
$sql_exec=$sql->execute([":id_inventaire"=>$id_inventaire_selectionne, ":date_depart"=>$_POST["date_depart_article"]]);	
if(!$sql_exec) echo "NB - DONNEES : Pb d'accès à la table inventaires";
else
{
	$id_article_temp=0;
	$nb_articles_par_page = 20;
	$compteur_page ++;
	$compteur_article = 0;			
	$offset_titre_articles = 15;
	$offset_titre_colonnes = 25;
	$offet_articles = 0;
	
	
	foreach ($sql->fetchAll() as $row) 
	{		
		// TRAITEMENT DES DONNEES
		if($id_article_temp == $row["id_article"])
		{
			$row["premier_affichage"] = 0;
			$row["total_depart_jour"] = 0;
			$row["total_sorti_periode"] = 0;

			$row['designation']='';
			$position_x_trait = 175;
			$longueur_trait = 290;
		}
		else
		{
			$row["premier_affichage"] = 1;
			$row['designation']=$row['designation'];	
			$position_x_trait = 5;
			$longueur_trait = 290;			
		}	

		// NB D'ARTICLES EN INVENTAIRE
		$row["total_inventaire"] = $row["quantite_totale"];
		
		
		// ON DENOMBRE LES ARTICLES SORTIS DANS LA PERIODE
		$sql_departs=$connexion->prepare("SELECT t1.quantite_reservee, t1.id_article, t2.date_depart FROM reservations_articles AS t1 LEFT JOIN reservations AS t2 ON t2.id_reservation=t1.id_reservation WHERE (t2.date_depart<=:date_depart AND t2.date_retour>:date_depart AND t1.id_article=:id_article AND t2.id_etat=1)");
		$sql_exec=$sql_departs->execute([":date_depart"=>$_POST["date_depart_article"], ":id_article"=>$row["id_article"]]);	
		if(!$sql_exec) echo "NB - DONNEES : Pb d'accès à la table reservations";
		else
		{
			foreach ($sql_departs->fetchAll() as $row_nb) 
			{
				if($row_nb["date_depart"]==$_POST["date_depart_article"]) 
				{
					$row["total_depart_jour"] += $row_nb["quantite_reservee"];
				}
				
				$row["total_sorti_periode"] += $row_nb["quantite_reservee"];
			}
		}		
		
		$row["stock"] = ($row["total_inventaire"] - $row["total_sorti_periode"] );
	
		
		$row["date_depart"] = GestionDate($row["date_depart"],'0');	
		$row["libelle"]='';
		if($row["association"]!='')
		{
			$row["libelle"] = $row["association"];
		}
		else
		{
			$row["libelle"] = $row["nom"]." ".$row["prenom"];
		}
		
		
		if((fmod($compteur_article, $nb_articles_par_page)==0) AND ( $row['premier_affichage']==1 OR ($row['premier_affichage']==0 AND $row['quantite_reservee']>0) ) )
		{
			$pdf->AddPage('L', 'A4');
			$compteur_article=0;
			$posy = 0;
			
			$pdf->SetFont('Helvetica', '', 14);
			$pdf->MultiCell(267, 5, utf8_encode("<b>Etat des réservations par article au ".$_POST["date_depart_article"]." </b>"), 0, 'C', 0, 1, 15, ($offset_titre_articles), true,'',true);
			
			$pdf->SetFont('Helvetica', '', 10);
			$pdf->MultiCell(25, 7, utf8_encode("<b>Inventaire</b>"), 0, 'C', 0, 1, 5, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(25, 7, utf8_encode("<b>Départ</b>"), 0, 'C', 0, 1, 30, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(25, 7, utf8_encode("<b>Stock</b>"), 0, 'C', 0, 1, 55, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(95, 7, utf8_encode("<b>Article</b>"), 0, 'L', 0, 1, 80, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(25, 7, utf8_encode("<b>Quantité</b>"), 0, 'C', 0, 1, 175, ($offset_titre_colonnes), true,'',true);
			$pdf->MultiCell(90, 7, utf8_encode("<b>Adhérent</b>"), 0, 'L', 0, 1, 200, ($offset_titre_colonnes), true,'',true);
		
			
		}
				
		// Articles
		$offet_articles = ($offset_titre_colonnes + ($compteur_article*7));
	

		if($compteur_article==0 OR $compteur_article==$nb_articles_par_page)
		{
			$position_x_trait = 5;
		}


	
		$pdf->SetDrawColor(180, 180, 180);
		$pdf->Line($longueur_trait, ($offet_articles+7), $position_x_trait, ($offet_articles+7));
		
		$offet_articles = ($offet_articles + 7);
	
	
		if($row['premier_affichage']==1 AND $row['quantite_reservee']==0 AND $row['total_sorti_periode']==0 )	
		{
			$pdf->SetFont('Helvetica', '', 10);

			$pdf->MultiCell(25, 5, $row['total_inventaire'], 0, 'C', 0, 1, 5, (($offet_articles+1)), true,'',true);

			$pdf->MultiCell(25, 5, '', 0, 'C', 0, 1, 30, (($offet_articles+1)), true,'',true);

			$pdf->MultiCell(25, 5, $row['stock'], 0, 'C', 0, 1, 55, (($offet_articles+1)), true,'',true);
			
			$pdf->MultiCell(95, 5, $row['designation'], 0, 'L', 0, 1, 80, (($offet_articles+1)), true,'',true);
			$pdf->MultiCell(25, 5, '', 0, 'C', 0, 1, 175, (($offet_articles+1)), true,'',true);
			$pdf->MultiCell(90, 5, '', 0, 'L', 0, 1, 200, (($offet_articles+1)), true,'',true);

			$compteur_article++;			
		}
		else if($row['premier_affichage']==1 AND $row['quantite_reservee']==0 AND $row['total_sorti_periode']>0 )	
		{
			$pdf->SetFont('Helvetica', '', 10);

			$pdf->MultiCell(25, 5, $row['total_inventaire'], 0, 'C', 0, 1, 5, (($offet_articles+1)), true,'',true);

			$pdf->MultiCell(25, 5, $row['total_depart_jour'], 0, 'C', 0, 1, 30, (($offet_articles+1)), true,'',true);

			$pdf->MultiCell(25, 5, $row['stock'], 0, 'C', 0, 1, 55, (($offet_articles+1)), true,'',true);
			
			$pdf->MultiCell(95, 5, $row['designation'], 0, 'L', 0, 1, 80, (($offet_articles+1)), true,'',true);
			$pdf->MultiCell(25, 5, $row['quantite_reservee'], 0, 'C', 0, 1, 175, (($offet_articles+1)), true,'',true);
			$pdf->MultiCell(90, 5, $row['libelle'], 0, 'L', 0, 1, 200, (($offet_articles+1)), true,'',true);
			
			$compteur_article++;
		}		
		else if($row['premier_affichage']==1 AND $row['quantite_reservee']>0)	
		{
			$pdf->SetFont('Helvetica', '', 10);

			$pdf->MultiCell(25, 5, $row['total_inventaire'], 0, 'C', 0, 1, 5, (($offet_articles+1)), true,'',true);

			$pdf->MultiCell(25, 5, $row['total_depart_jour'], 0, 'C', 0, 1, 30, (($offet_articles+1)), true,'',true);

			$pdf->MultiCell(25, 5, $row['stock'], 0, 'C', 0, 1, 55, (($offet_articles+1)), true,'',true);
			

			$pdf->MultiCell(95, 5, $row['designation'], 0, 'L', 0, 1, 80, (($offet_articles+1)), true,'',true);
			$pdf->MultiCell(25, 5, $row['quantite_reservee'], 0, 'C', 0, 1, 175, (($offet_articles+1)), true,'',true);
			$pdf->MultiCell(90, 5, $row['libelle'], 0, 'L', 0, 1, 200, (($offet_articles+1)), true,'',true);
			
			$compteur_article++;
		}
		else if($row['premier_affichage']==0 AND $row['quantite_reservee']>0)	
		{
			$pdf->SetFont('Helvetica', '', 10);

			$pdf->MultiCell(25, 5, '', 0, 'C', 0, 1, 5, (($offet_articles+1)), true,'',true);

			$pdf->MultiCell(25, 5, '', 0, 'C', 0, 1, 30, (($offet_articles+1)), true,'',true);

			$pdf->MultiCell(25, 5, '', 0, 'C', 0, 1, 55, (($offet_articles+1)), true,'',true);
			

			$pdf->MultiCell(95, 5, '', 0, 'L', 0, 1, 80, (($offet_articles+1)), true,'',true);
			$pdf->MultiCell(25, 5, $row['quantite_reservee'], 0, 'C', 0, 1, 175, (($offet_articles+1)), true,'',true);
			$pdf->MultiCell(90, 5, $row['libelle'], 0, 'L', 0, 1, 200, (($offet_articles+1)), true,'',true);
			
			$compteur_article++;
		}
		
		$id_article_temp = $row["id_article"];
	}
}	


// ---------------------------------------------------------
//Close and output PDF document

$pdf->Output('fiche-reservations-jour-'.$_POST["date_depart_article"].'-synthese.pdf', 'D');



//============================================================+
// END OF FILE
//============================================================+

