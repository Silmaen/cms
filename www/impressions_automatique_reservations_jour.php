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
		$this->SetY(-40);
		// Set font
		$this->SetFont('helvetica', 'I', 8);

		// set color for text
		$this->SetTextColor(0, 0, 0);
		$this->writeHTMLCell(180, 5, 15, $this->getY(), utf8_encode("Les donn�es de ce formulaire sont recueillies avec votre accord implicite lors d'une premi�re r�servation de mat�riel.	Ces donn�es sont recueillies en vue de tenir � jour notre fichier d'adh�rents et faire le suivi de vos r�servations. En aucun cas ces donn�es ne seront c�d�es ou vendues � des tiers. Le responsable de leur traitement est la Coll�giale. Tous les membres de la coll�giale et de l'�quipe des administrateurs en charge de la gestion des r�servations peuvent �tre destinataires de vos donn�es. Le droit d'acc�s et de rectification en vertu du R�glement europ�en sur la protection des donn�es personnelles, en vigueur depuis le 25/05/2018 vous donne acc�s aux donn�es vous concernant. Vous pouvez demander leur rectification et leur suppression � la fin de l'exercice annuel. Ces d�marches s'effectuent aupr�s de la Coll�giale du CDF (relation-association@cdf-genay.com). Les donn�es sont conserv�es jusqu'� 4 ans apr�s la fin de votre adh�sion."), 0, 0, 0, true, 'J', true);

		$this->SetDrawColor(0, 0, 0);
		$this->SetY(-15);

		$this->Line(PDF_MARGIN_LEFT, $this->getY(), $this->getPageWidth()-PDF_MARGIN_LEFT, $this->getY());

		// Page number
		$this->Cell(0, 10, utf8_encode("Cr�� le ").date("d-m-Y").utf8_encode(" - Imprim� le ").date("d-m-Y")." - Page ".$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('COMITE DES FETES DE GENAY');
$pdf->SetTitle('RESERVATIONS DU JOUR');
$pdf->SetSubject('RESERVATIONS DU JOUR');
$pdf->SetKeywords('RESERVATIONS DU JOUR');

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

$nb_reservations = 0;
// On v�rifie s'il y a des r�sultats => des r�servations � sortir ce jour l�
$sql_nb=$connexion->prepare("SELECT COUNT(DISTINCT t1.id_reservation) as resultat FROM reservations_articles AS t1 LEFT JOIN reservations AS t2 ON t2.id_reservation=t1.id_reservation WHERE (t2.date_depart=:date_depart AND t1.quantite_reservee>0 AND t2.id_etat=1)");
$sql_exec=$sql_nb->execute([":date_depart"=>$_GET["date_depart_reservation"]]);
if(!$sql_exec) echo "DONNEES : Pb d'acc�s � la table reservations 1";
else
{
	foreach ($sql_nb->fetchAll() as $row_nb)
	{

		$nb_reservations = $row_nb["resultat"];
	}
}
if($nb_reservations==0)
{
	// CREATION PDF
	$pdf->AddPage();

	$_GET["date_depart_reservation"] = GestionDate($_GET["date_depart_reservation"],'0');

	$pdf->SetFont('Helvetica', '', 16);
	$pdf->MultiCell(180, 5, utf8_encode("<b>Aucune r�servation en date du ".$_GET["date_depart_reservation"]." </b>"), 0, 'C', 0, 1, 15, 100, true,'',true);

}
else
{
	$sql=$connexion->prepare("SELECT t1.id_reservation, t1.id_client, t1.date_depart, t1.date_retour, t1.commentaire, t1.don, t1.date_creation, t1.date_modification, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat, t4.nom as nom_client, t4.prenom as prenom_client, t4.association as association_client, t4.telephone as telephone_client, t4.email as email_client, t4.adresse1 as adresse1_client, t4.adresse2 as adresse2_client, t4.adresse3 as adresse3_client, t4.cp as cp_client, t4.ville as ville_client, t5.id_statut_reservation, t5.libelle_statut FROM reservations AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN clients AS t4 ON t4.id_client=t1.id_client LEFT JOIN reservations_statuts AS t5 ON t5.id_statut_reservation=t1.id_statut_reservation WHERE (t1.date_depart= :date_depart AND t1.id_etat=1) ORDER BY t4.association, t4.nom, t4.prenom");
	$sql_exec=$sql->execute([":date_depart"=>$_GET["date_depart_reservation"]]);
	if(!$sql_exec) echo "DONNEES : Pb d'acc�s � la table reservations";
	else
	{
		foreach ($sql->fetchAll() as $row)
		{
			// TRAITEMENT DES DONNEES
			$row["date_depart"] = GestionDate($row["date_depart"],'0');
			$row["date_retour"] = GestionDate($row["date_retour"],'0');
			$row["date_creation"] = GestionDate($row["date_creation"],'0');
			$row["date_modification"] = GestionDate($row["date_modification"],'0');

			// CREATION PDF
			$pdf->AddPage();
			$nb_articles_par_page = 20;
			$compteur_page ++;
			$compteur_article = 0;
			$compteur_article_global = 0;
			$posy = 0;

			// On v�rifie si la r�servation comporte bien des articles r�serv�s
			$sql_articles_nombre=$connexion->prepare("SELECT COUNT(t1.id_article) as nb_articles, t1.quantite_reservee FROM reservations_articles AS t1 WHERE (t1.id_reservation=:id_reservation AND t1.quantite_reservee!=0)");
			$sql_exec=$sql_articles_nombre->execute([":id_reservation"=>$row["id_reservation"]]);
			if(!$sql_exec) echo "LISTE : Pb d'acc�s � la table articles";
			else
			{
				foreach ($sql_articles_nombre->fetchAll() as $row_articles_nombre)
				{

					if($row_articles_nombre["nb_articles"]==0)
					{
						$pdf->SetFont('Helvetica', '', 12);
						$pdf->SetTextColor(75, 75, 75);
						$pdf->Rect(15, ($posy+5), 180, 5, 'F', array(),array(245, 245, 245));
						$pdf->MultiCell(90, 5, utf8_encode("<b>D�part le </b>").$row["date_depart"], 0, 'L', 0, 1, 15, ($posy+5), true,'',true);
						$pdf->MultiCell(90, 5, utf8_encode("<b>Retour le </b>").$row["date_retour"], 0, 'R', 0, 1, 105, ($posy+5), true,'',true);

						$pdf->SetFont('Helvetica', '', 16);
						$pdf->SetTextColor(0, 0, 255);
						$pdf->MultiCell(90, 10, $row["nom_client"].' '.$row["prenom_client"], 0, 'R', 0, 1, 105, ($posy+12), true,'',true);
						$pdf->MultiCell(90, 10, $row["association_client"], 0, 'R', 0, 1, 105, ($posy+22), true,'',true);

						$pdf->SetTextColor(0, 0, 0);
						$pdf->MultiCell(200, 5, utf8_encode("PROBLEME : aucun article r�serv� pour la r�servation num�ro ".$row["id_reservation"]), 0, 'C', 0, 1, 0, ($posy+30), true,'',true);
					}
				}
			}

			// cr�ation de la liste des articles r�serv�s
			$liste_articles = array();
			$sql_articles=$connexion->prepare("SELECT t1.id_article, t1.quantite_reservee, t2.designation FROM reservations_articles AS t1 LEFT JOIN articles AS t2 ON t2.id_article=t1.id_article WHERE (t1.id_reservation=:id_reservation AND t1.quantite_reservee!=0) ORDER BY t2.designation ASC");
			$sql_exec=$sql_articles->execute([":id_reservation"=>$row["id_reservation"]]);
			if(!$sql_exec) echo "LISTE : Pb d'acc�s � la table articles";
			else
			{
				foreach ($sql_articles->fetchAll() as $row_articles)
				{
					// Affichage de l'en-t�te

					if($compteur_article==0)
					{
						$pdf->SetFont('Helvetica', '', 12);
						$pdf->SetTextColor(75, 75, 75);
						$pdf->Rect(15, ($posy+5), 180, 5, 'F', array(),array(245, 245, 245));
						$pdf->MultiCell(90, 5, utf8_encode("<b>D�part le </b>").$row["date_depart"], 0, 'L', 0, 1, 15, ($posy+5), true,'',true);
						$pdf->MultiCell(90, 5, utf8_encode("<b>Retour le </b>").$row["date_retour"], 0, 'R', 0, 1, 105, ($posy+5), true,'',true);

						$pdf->SetFont('Helvetica', '', 16);
						$pdf->SetTextColor(0, 0, 255);
						$pdf->MultiCell(90, 10, $row["nom_client"].' '.$row["prenom_client"], 0, 'R', 0, 1, 105, ($posy+12), true,'',true);
						$pdf->MultiCell(90, 10, $row["association_client"], 0, 'R', 0, 1, 105, ($posy+22), true,'',true);

						$pdf->SetFont('Helvetica', '', 12);
						$pdf->SetTextColor(0, 0, 0);
						$pdf->MultiCell(90, 5, utf8_encode("<b>Coordonn�es</b>"), 0, 'L', 0, 1, 15, ($posy+25), true,'',true);

						// Coordonn�es
						$offset_adresse = 30;
						$pdf->SetDrawColor(180, 180, 180);
						$pdf->Line(15, ($posy+30), 195, ($posy+30));

						if(isset($row["adresse1_client"]) AND $row["adresse1_client"]!='')
						{
							$pdf->MultiCell(90, 5, $row["adresse1_client"], 0, 'L', 0, 1, 15, ($posy+$offset_adresse), true,'',true);
							$offset_adresse = ($offset_adresse + 5);
						}
						if(isset($row["adresse2_client"]) AND $row["adresse2_client"]!='')
						{
							$pdf->MultiCell(90, 5, $row["adresse2_client"], 0, 'L', 0, 1, 15, ($posy+$offset_adresse), true,'',true);
							$offset_adresse = ($offset_adresse + 5);
						}
						if(isset($row["adresse3_client"]) AND $row["adresse3_client"]!='')
						{
							$pdf->MultiCell(90, 5, $row["adresse3_client"], 0, 'L', 0, 1, 15, ($posy+$offset_adresse), true,'',true);
							$offset_adresse = ($offset_adresse + 5);
						}
						$pdf->MultiCell(90, 5, $row["cp_client"].' '.$row["ville_client"], 0, 'L', 0, 1, 15, ($posy+$offset_adresse), true,'',true);

						// T�l & Email
						$offset_tel = 30;
						if(isset($row["telephone_client"]) AND $row["telephone_client"]!='')
						{
							$pdf->MultiCell(90, 5, utf8_encode("<b>T�l :</b> ").$row["telephone_client"], 0, 'R', 0, 1, 105, ($posy+$offset_tel), true,'',true);
							$offset_tel = ($offset_tel + 5);
						}
						if(isset($row["email_client"]) AND $row["email_client"]!='')
						{
							$pdf->MultiCell(125, 5, utf8_encode("<b>Email :</b> ").$row["email_client"], 0, 'R', 0, 1, 70, ($posy+$offset_tel), true,'',true);
						}

						// Commentaire
						$pdf->SetFont('Helvetica', '', 12);
						$offset_commentaire = 50;
						if(isset($row["commentaire"]) AND $row["commentaire"]!='')
						{
							$pdf->MultiCell(180, 5, utf8_encode("<b>Commentaire :</b> "), 0, 'L', 0, 1, 15, ($posy+$offset_commentaire), true,'',true);

							$pdf->SetFont('Helvetica', '', 10);
							$pdf->MultiCell(180, 10, nl2br($row["commentaire"]), 0, 'L', 0, 1, 15, ($posy+$offset_commentaire+5), true,'',true);
						}

					}

					// MESSAGE DONS
					$offset_message_don = 70;
					$pdf->SetDrawColor(0, 0, 0);
					$pdf->Line(15, ($posy+$offset_message_don), 195, ($posy+$offset_message_don));

					$pdf->SetFont('Helvetica', 'B', 10);
					$pdf->SetTextColor(0, 0, 255);
					$pdf->MultiCell(180, 10, utf8_encode("Suite � un pr�t de mat�riel, l'adh�sion annuelle (5�) et  le don permettent d'entretenir le mat�riel et son renouvellement, merci pour votre don lors du retour du mat�riel."), 0, 'J', 0, 1, 15, $posy+$offset_message_don, true,'',true);

					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Helvetica', '', 10);
					$pdf->MultiCell(180, 10, utf8_encode("
					Le mat�riel pr�t� par le Comit� des F�tes de Genay est sous la responsabilit� de l'adh�rent et doit �tre rendu propre. Tout article manquant ou d�t�rior� vous sera factur�.
					<br />
					Les barnums sont destin�s exclusivement aux implantations de courtes dur�es, en ayant consult� au pr�alable la m�t�o. Ils doivent �tre d�mont�s et �vacu�s � partir de 50 km/h de vent et en cas de forte pluie."), 0, 'J', 0, 1, 15, $posy+$offset_message_don+10, true,'',true);
					$pdf->SetDrawColor(0, 0, 0);
					$pdf->Line(15, ($posy+$offset_message_don+28), 195, ($posy+$offset_message_don+28));

					// Affichage des articles
					$offset_titre_articles = 100;

					$pdf->SetFont('Helvetica', '', 16);
					$pdf->MultiCell(180, 5, utf8_encode("<b>Liste d'articles r�serv�s</b>"), 0, 'C', 0, 1, 15, ($offset_titre_articles), true,'',true);

					// Articles
					$offet_articles = ($offset_titre_articles + 10 + ($compteur_article*7));

					if(($compteur_page==1 AND $compteur_article==0) OR ($compteur_page>1 AND (fmod($compteur_article, $nb_articles_par_page)==0)))
					{
						$pdf->SetFont('Helvetica', '', 12);
						$pdf->MultiCell(105, 7, utf8_encode("<b>Article</b>"), 0, 'L', 0, 1, 20, ($offet_articles), true,'',true);
						$pdf->MultiCell(20, 7, utf8_encode("<b>Quantit�</b>"), 0, 'C', 0, 1, 125, ($offet_articles), true,'',true);
						$pdf->MultiCell(20, 7, utf8_encode("<b>D�part</b>"), 0, 'C', 0, 1, 145, ($offet_articles), true,'',true);
						$pdf->MultiCell(20, 7, utf8_encode("<b>Retour</b>"), 0, 'C', 0, 1, 165, ($offet_articles), true,'',true);

					}

					$offet_articles = ($offet_articles + 7);

					$pdf->SetFont('Helvetica', '', 12);
					$pdf->MultiCell(105, 7, $row_articles['designation'], 0, 'L', 0, 1, 20, (($offet_articles+1)), true,'',true);
					$pdf->MultiCell(20, 7, $row_articles['quantite_reservee'], 0, 'C', 0, 1, 125, (($offet_articles+1)), true,'',true);
					$pdf->MultiCell(20, 7, "[&nbsp;&nbsp;]", 0, 'C', 0, 1, 145, (($offet_articles+1)), true,'',true);
					$pdf->MultiCell(20, 5, "[&nbsp;&nbsp;]", 0, 'C', 0, 1, 165, (($offet_articles+1)), true,'',true);

					$pdf->SetDrawColor(180, 180, 180);
					$pdf->Line(15, ($offet_articles+7), 195, ($offet_articles+7));

					$compteur_article++;

					if(fmod($compteur_article, $nb_articles_par_page)==0)
					{
						$pdf->AddPage();
						$compteur_page++;
						$compteur_article=0;
					}
				}
			}
			$compteur_page = 0;
		}
	}
}

// ---------------------------------------------------------

//Close and output PDF document

$pdf->Output('fiche-reservations-jour-'.$_GET["date_depart_reservation"].'.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
