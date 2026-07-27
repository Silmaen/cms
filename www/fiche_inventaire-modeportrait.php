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
		$this->SetY(-25);
		// Set font
		$this->SetFont('helvetica', 'I', 8);

		// set color for text
		$this->SetTextColor(0, 0, 0);
		$this->writeHTMLCell(170, 10, 15, $this->getY(), utf8_encode("Le mat�riel pr�t� par le Comit� des F�tes de Genay est sous la responsabilit� de l'adh�rent et doit �tre rendu propre. <b>Tout article manquant ou d�t�rior� vous sera factur�.</b>"), 0, 0, 0, true, 'J', true);

		$this->SetDrawColor(0, 0, 0);
		$this->SetY(-15);

		$this->Line(PDF_MARGIN_LEFT, $this->getY(), $this->getPageWidth()-PDF_MARGIN_LEFT, $this->getY());

		// Page number
		$this->Cell(0, 10, utf8_encode("Imprim� le ").date("d-m-Y")." - Page ".$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

	}
}

// TRAITEMENT DES VARIABLES

/////////////////////////////////////////////
// DEBUT DE LA REQUETE SUR LES INVENTAIRES //
/////////////////////////////////////////////

if (isset ($_GET["type"]) AND $_GET["type"]=='vide')
{
	// RECUPERATION DE L'INVENTAIRE LE PLUS RECENT
	$sql=$connexion->prepare("SELECT id_inventaire, date_inventaire FROM inventaires WHERE id_etat='1' ORDER BY date_inventaire DESC LIMIT 1");
	$sql_exec=$sql->execute();
	if(!$sql_exec) echo "INVENTAIRE : Pb d'acc�s � la table INVENTAIRES";
	else
	{
		foreach ($sql->fetchAll() as $row)
		{
			$id_inventaire_selectionne = $row["id_inventaire"];
		}
	}
}
else if(isset($_GET["action"]) AND $_GET["action"]=='imprimer')
{
	$action_selectionne=$_GET["action"];
	if(isset($_GET["id_inventaire"]))
	{
		$id_inventaire_selectionne=$_GET["id_inventaire"];
	}
	else
	{
		$id_inventaire_selectionne=0;
	}
}

$sql=$connexion->prepare("SELECT t1.id_inventaire, t1.commentaire, t1.date_inventaire,t1.date_creation, t1.date_modification, t1.id_statut_inventaire, t4.libelle_statut, t5.id_type_inventaire, t5.libelle_type, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat FROM inventaires AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN inventaires_statuts AS t4 ON t4.id_statut_inventaire=t1.id_statut_inventaire LEFT JOIN inventaires_types AS t5 ON t5.id_type_inventaire=t1.id_type_inventaire WHERE (t1.id_inventaire= :id_inventaire)");
$sql_exec=$sql->execute([":id_inventaire"=>$id_inventaire_selectionne]);
if(!$sql_exec) echo "MODIFIER - DONNEES : Pb d'acc�s � la table inventaires";
else
{
	foreach ($sql->fetchAll() as $row)
	{
		$id_inventaire = $row["id_inventaire"];
		$date_inventaire = GestionDate($row["date_inventaire"],'0');
		$commentaire = $row["commentaire"];
		$libelle_etat = $row["libelle_etat"];
		$id_statut_inventaire = $row["id_statut_inventaire"];
		$libelle_statut = $row["libelle_statut"];
		$id_type_inventaire = $row["id_type_inventaire"];
		$libelle_type = $row["libelle_type"];
		$date_creation = GestionDate($row["date_creation"],'0');
		$date_modification = GestionDate($row["date_modification"],'0');
		$id_membre_auteur = $row["id_membre_auteur"];
		$nom_membre_auteur = $row["nom_membre_auteur"];
		$prenom_membre_auteur = $row["prenom_membre_auteur"];

		// cr�ation de la liste des articles
		$liste_articles = array();
		$sql_articles=$connexion->prepare("SELECT t1.id_article, t1.designation FROM articles AS t1 WHERE t1.id_etat='1' ORDER BY t1.designation");
		$sql_exec=$sql_articles->execute();
		if(!$sql_exec) echo "LISTE : Pb d'acc�s � la table articles";
		else
		{
			foreach ($sql_articles->fetchAll() as $row_articles)
			{
				$sql_inventaires=$connexion->prepare("SELECT t1.id_article, t1.quantite_precedent, t1.quantite_totale, t1.commentaire FROM inventaires_articles AS t1 LEFT JOIN inventaires AS t2 ON t2.id_inventaire=t1.id_inventaire WHERE t2.id_inventaire=:id_inventaire AND t1.id_article=:id_article");
				$sql_exec_inventaires=$sql_inventaires->execute([":id_inventaire"=>$id_inventaire_selectionne, ":id_article"=>$row_articles["id_article"]]);
				if(!$sql_exec) echo "LISTE INVENTAIRES: Pb d'acc�s � la table inventaires";
				else
				{
					$row_articles["commentaire"] = ""; // valeur par defaut
					$row_articles["quantite_precedent"] = 0;
					$row_articles["quantite_totale"] = 0;
					$row_articles["ecart"] = 0; // valeur par defaut

					foreach ($sql_inventaires->fetchAll() as $row_inventaires)
					{
						$row_articles["quantite_precedent"] = $row_inventaires["quantite_precedent"];
						$row_articles["quantite_totale"] = $row_inventaires["quantite_totale"];
						$row_articles["commentaire"] = $row_inventaires["commentaire"];
						$row_articles["ecart"] = $row_articles["quantite_totale"] - $row_articles["quantite_precedent"];
					}
					array_push($liste_articles,$row_articles);
				}
			}
		}
	}
}

///////////////////////////////////////////
// FIN DE LA REQUETE SUR LES INVENTAIRES //
///////////////////////////////////////////

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
	$compteur=0;
	foreach($liste_articles as $cle => $element)
	{

		if (fmod($compteur, 48)==0)
		{
			$pdf->AddPage();
			$posy = 0;

			$pdf->SetFont('Helvetica', '', 16);
			$pdf->SetTextColor(75, 75, 75);
			$pdf->MultiCell(90, 10, utf8_encode("Inventaire"), 0, 'L', 0, 1, 15, ($posy+5), true,'',true);
			$pdf->MultiCell(90, 10, $date_inventaire, 0, 'R', 0, 1, 105, ($posy+5), true,'',true);

			$pdf->SetFont('Helvetica', '', 8);
			$pdf->MultiCell(170, 5, utf8_encode("Cr�� le ").$date_creation.utf8_encode(" - Modifi� le ").$date_modification.utf8_encode(" par ").$nom_membre_auteur." ".$prenom_membre_auteur, 0, 'L', 0, 1, 15, ($posy+15), true,'',true);

			$pdf->SetFont('Helvetica', '', 12);

			// articles
			$offset_articles = ($posy + 20);

			$pdf->Rect(15, ($offset_articles), 180, 5, 'F', array(),array(245, 245, 245));

			$pdf->SetFont('Helvetica', '', 8);
			$pdf->MultiCell(80, 5, utf8_encode("<b>Article</b>"), 1, 'L', 0, 1, 15, ($offset_articles), true,'',true);
			$pdf->MultiCell(20, 5, utf8_encode("<b>Qt�. Avant</b>"), 1, 'C', 0, 1, 75, ($offset_articles), true,'',true);
			$pdf->MultiCell(20, 5, utf8_encode("<b>Qt�. Actuelle</b>"), 1, 'C', 0, 1, 95, ($offset_articles), true,'',true);
			$pdf->MultiCell(20, 5, utf8_encode("<b>Ecart</b>"), 1, 'C', 1, 0, 115, ($offset_articles), true,'',true);
			$pdf->MultiCell(60, 5, utf8_encode("<b>Commentaire</b>"), 1, 'C', 0, 1, 135, ($offset_articles), true,'',true);

			$pdf->SetDrawColor(180, 180, 180);
			$pdf->Line(15, ($offset_articles + 5), 195, ($offset_articles + 5));
		}

		$offset_articles = ($offset_articles + 5);

		if (fmod($compteur, 2)!=0)
		{
			$pdf->Rect(15, ($offset_articles), 180, 5, 'F', array(),array(245, 245, 245));
		}

		if(!isset($_GET["type"]) OR $_GET["type"]!='vide')
		{
			$pdf->MultiCell(80, 5, $element['designation'], 0, 'L', 0, 1, 15, ($offset_articles), true,'',true);
			$pdf->MultiCell(20, 5, $element['quantite_precedent'], 0, 'C', 0, 1, 75, ($offset_articles), true,'',true);
			$pdf->MultiCell(20, 5, $element['quantite_totale'], 0, 'C', 0, 1, 95, ($offset_articles), true,'',true);
			$pdf->MultiCell(20, 5, $element['ecart'], 0, 'C', 0, 1, 115, ($offset_articles), true,'',true);
			$pdf->MultiCell(60, 5, $element['commentaire'], 0, 'C', 0, 1, 135, ($offset_articles), true,'',true);
		}
		else
		{
			$pdf->MultiCell(60, 5, $element['designation'], 0, 'L', 0, 1, 15, ($offset_articles), true,'',true);
			$pdf->MultiCell(20, 5, $element['quantite_totale'], 0, 'C', 0, 1, 75, ($offset_articles), true,'',true);
			$pdf->MultiCell(20, 5, '', 0, 'C', 0, 1, 95, ($offset_articles), true,'',true);
			$pdf->MultiCell(20, 5, '', 0, 'C', 0, 1, 115, ($offset_articles), true,'',true);
			$pdf->MultiCell(60, 5, '', 0, 'C', 0, 1, 135, ($offset_articles), true,'',true);
		}

		$compteur++;
	}

// ---------------------------------------------------------

//Close and output PDF document

$pdf->Output('fiche-inventaire-'.$date_inventaire.'.pdf', 'D');
