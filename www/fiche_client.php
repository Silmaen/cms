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
if(isset($_GET["action"]) AND $_GET["action"]=='imprimer')
{
	$action_selectionne=$_GET["action"];
	if(isset($_GET["id_client"]))
	{
		$id_client_selectionne=$_GET["id_client"];
	}
	else
	{
		$id_client_selectionne=0;
	}
}

///////////////////////////////////////
// DEBUT DE LA REQUETE SUR LES CLIENTS //
///////////////////////////////////////
$sql=$connexion->prepare("SELECT t1.id_client, t1.association, t1.nom, t1.prenom, t1.adresse1, t1.adresse2, t1.adresse3, t1.cp, t1.ville, t1.telephone, t1.email, t1.commentaire, t1.date_creation, t1.date_modification, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat, t4.id_statut_client, t4.libelle_statut FROM clients AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN clients_statuts AS t4 ON t4.id_statut_client=t1.id_statut_client WHERE (t1.id_client= :id_client)");

$sql_exec=$sql->execute([":id_client"=>$id_client_selectionne]);
if(!$sql_exec) echo "VALIDER - DONNEES : Pb d'acc�s � la table clients";
else
{
	foreach ($sql->fetchAll() as $row)
	{

		$id_client = $row["id_client"];
		$association=$row["association"];
		$nom = $row["nom"];
		$prenom = $row["prenom"];
		$adresse1 = $row["adresse1"];
		$adresse2 = $row["adresse2"];
		$adresse3 = $row["adresse3"];
		$cp = $row["cp"];
		$ville = $row["ville"];
		$telephone = $row["telephone"];
		$email = $row["email"];
		$id_statut_client = $row["id_statut_client"];
		$libelle_statut = $row["libelle_statut"];
		$commentaire = $row["commentaire"];
		$libelle_etat = $row["libelle_etat"];
		$date_creation = GestionDate($row["date_creation"],'0');
		$date_modification = GestionDate($row["date_modification"],'0');
		$id_membre_auteur = $row["id_membre_auteur"];
		$nom_membre_auteur = $row["nom_membre_auteur"];
		$prenom_membre_auteur = $row["prenom_membre_auteur"];

		// LISTE DES DONS
		$compteur = 0;
		$total_don = 0;
		$liste_dons = array();
		$sql_dons=$connexion->prepare("
		SELECT t1.id_client, t1.id_reservation, t1.annee_reservation, t1.date_depart, t1.date_retour, t1.don
		FROM reservations AS t1
		LEFT JOIN clients AS t2 ON t2.id_client=t1.id_client
		WHERE (t2.id_client=:id_client AND t1.id_etat<>3 AND t1.don>0)
		ORDER BY t1.annee_reservation DESC, t1.date_depart DESC");
		$sql_exec=$sql_dons->execute([":id_client"=>$row["id_client"]]);
		if(!$sql_exec) echo "MODIFIER 2 - DONNEES : Pb d'acc�s � la table reservations";
		else
		{

			foreach ($sql_dons->fetchAll() as $row_dons)
			{

				$compteur ++;
				$row_dons["annee_reservation"] = $row_dons["annee_reservation"];
				$row_dons["don"] = $row_dons["don"];
				$row_dons["date_depart"] = $row_dons["date_depart"];
				$row_dons["date_retour"] = $row_dons["date_retour"];
				$total_don += $row_dons["don"];

				// LISTE DES ADHESIONS POUR CETTE ANNEE
				$sql_adhesions=$connexion->prepare("
				SELECT t1.id_client, t1.annee, t1.montant
				FROM clients_adhesions AS t1
				WHERE (t1.annee=:annee_reservation AND id_client=:id_client AND t1.montant>0)
				ORDER BY t1.annee DESC LIMIT 1");
				$sql_exec=$sql_adhesions->execute([":annee_reservation"=>$row_dons["annee_reservation"], ":id_client"=>$row["id_client"]]);

				if(!$sql_exec) echo "MODIFIER 2 - DONNEES : Pb d'acc�s � la table clients_adhesions";
				else
				{
					foreach ($sql_adhesions->fetchAll() as $row_adhesions)
					{
						$row_dons["montant_adhesion"] = $row_adhesions["montant"];
					}
				}

				array_push($liste_dons,$row_dons);
			}
		}
		// S'IL N'Y A PAS DE DONS ENREGISTRES
		if($compteur==0)
		{

			$row_dons["don"] = "";
			$row_dons["date_depart"] = "";
			$row_dons["date_retour"] = "";
			$row_dons["montant_adhesion"] = "";
			array_push($liste_dons,$row_dons);
		}
	}
}

///////////////////////////////////////
// FIN DE LA REQUETE SUR LES CLIENTS //
///////////////////////////////////////

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('COMITE DES FETES DE GENAY');
$pdf->SetTitle('RESERVATIONS DU JOUR');
$pdf->SetSubject('RESERVATIONS DU JOUR');
$pdf->SetKeywords('RESERVATIONS DU JOUR');

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
// Cr�ation de la page 1
$pdf->AddPage();

$posy = 0;

$pdf->SetFont('Helvetica', '', 16);
$pdf->SetTextColor(75, 75, 75);
$pdf->MultiCell(90, 10, $association, 0, 'L', 0, 1, 15, ($posy+5), true,'',true);
$pdf->MultiCell(90, 10, $nom.' '.$prenom, 0, 'R', 0, 1, 105, ($posy+5), true,'',true);

$pdf->SetFont('Helvetica', '', 12);
$pdf->MultiCell(90, 5, "<b>Coordonn�es</b>", 0, 'L', 0, 1, 15, ($posy+30), true,'',true);

// Adresse
$offset_adresse = 35;
$pdf->SetDrawColor(180, 180, 180);
$pdf->Rect(15, ($posy+35), 180, 30, 'all');

if(isset($adresse1) AND $adresse1!='')
{
	$pdf->MultiCell(90, 5, $adresse1, 0, 'L', 0, 1, 15, ($posy+$offset_adresse), true,'',true);
	$offset_adresse = ($offset_adresse + 5);
}
if(isset($adresse2) AND $adresse2!='')
{
	$pdf->MultiCell(90, 5, $adresse2, 0, 'L', 0, 1, 15, ($posy+$offset_adresse), true,'',true);
	$offset_adresse = ($offset_adresse + 5);
}
if(isset($adresse3) AND $adresse3!='')
{
	$pdf->MultiCell(90, 5, $adresse3, 0, 'L', 0, 1, 15, ($posy+$offset_adresse), true,'',true);
	$offset_adresse = ($offset_adresse + 5);
}
$pdf->MultiCell(90, 5, $cp.' '.$ville, 0, 'L', 0, 1, 15, ($posy+$offset_adresse), true,'',true);

// T�l & Email
$offset_tel = 35;
if(isset($telephone) AND $telephone!='')
{
	$pdf->MultiCell(90, 5, utf8_encode("<b>T�l :</b> ").$telephone, 0, 'R', 0, 1, 105, ($posy+$offset_tel), true,'',true);
	$offset_tel = ($offset_tel + 5);
}
if(isset($email) AND $email!='')
{
	$pdf->MultiCell(90, 5, utf8_encode("<b>Email :</b> ").$email, 0, 'R', 0, 1, 105, ($posy+$offset_tel), true,'',true);
}

$offset_commentaire = ($offset_adresse + 30);
if(isset($commentaire) AND $commentaire!='')
{
	$pdf->MultiCell(90, 5, "<b>Commentaire</b>", 0, 'L', 0, 1, 15, ($posy+$offset_commentaire), true,'',true);
	$offset_commentaire = ($offset_commentaire + 5);
	$pdf->MultiCell(180, 5, $commentaire, 0, 'L', 0, 1, 15, ($posy+$offset_commentaire), true,'',true);

	$pdf->SetDrawColor(180, 180, 180);
	$pdf->Rect(15, ($posy+$offset_commentaire), 180, 20, 'all');
}

// R�glements
$offset_reglements = ($posy+$offset_commentaire + 30);
$pdf->SetFont('Helvetica', '', 16);
$pdf->MultiCell(180, 5, utf8_encode("<b>Historique des r�glements</b>"), 0, 'C', 0, 1, 15, ($posy+$offset_reglements), true,'',true);
$offset_reglements = ($offset_reglements + 10);

$pdf->Rect(20, ($posy+$offset_reglements), 160, 5, 'F', array(),array(245, 245, 245));
$pdf->SetFont('Helvetica', '', 12);
$pdf->MultiCell(40, 5, utf8_encode("<b>Ann�e</b>"), 0, 'C', 0, 1, 20, ($posy+$offset_reglements), true,'',true);
$pdf->MultiCell(30, 5, utf8_encode("<b>Adh�sion �</b>"), 0, 'C', 0, 1, 60, ($posy+$offset_reglements), true,'',true);
$pdf->MultiCell(30, 5, utf8_encode("<b>Don �</b>"), 0, 'C', 0, 1, 90, ($posy+$offset_reglements), true,'',true);
$pdf->MultiCell(40, 5, utf8_encode("<b>Date d�part</b>"), 0, 'C', 0, 1, 120, ($posy+$offset_reglements), true,'',true);
$pdf->MultiCell(40, 5, utf8_encode("<b>Date retour</b>"), 0, 'C', 0, 1, 160, ($posy+$offset_reglements), true,'',true);

$pdf->SetDrawColor(180, 180, 180);
$pdf->Line(20, ($posy+$offset_reglements + 5), 160, ($posy+$offset_reglements + 5));

$compteur=0;
$annee_reservation_temp = "";
foreach($liste_dons as $cle => $element)
{

	$offset_reglements = ($offset_reglements + 5);

	if ($compteur==0)
	{
		$compteur++;
	}
	else
	{
		$pdf->Rect(20, ($posy+$offset_reglements), 160, 5, 'F', array(),array(245, 245, 245));
		$compteur=0;
	}

	if($annee_reservation_temp!=$element['annee_reservation'])
	{

		$pdf->MultiCell(40, 5, $element['annee_reservation'], 0, 'C', 0, 1, 20, ($posy+$offset_reglements), true,'',true);
		$pdf->MultiCell(30, 5, $element['montant_adhesion'], 0, 'C', 0, 1, 60, ($posy+$offset_reglements), true,'',true);
	}

	if($_SESSION["id_utilisateur_groupe"]==1)
	{
		$pdf->MultiCell(30, 5, $element['don'], 0, 'C', 0, 1, 90, ($posy+$offset_reglements), true,'',true);
		$pdf->MultiCell(40, 5, GestionDate($element['date_depart'],0), 0, 'C', 0, 1, 120, ($posy+$offset_reglements), true,'',true);
		$pdf->MultiCell(40, 5, GestionDate($element['date_retour'],0), 0, 'C', 0, 1, 160, ($posy+$offset_reglements), true,'',true);

	}
	$annee_reservation_temp = $element['annee_reservation'];

}

// ---------------------------------------------------------

//Close and output PDF document

$nom = strtr($nom,"�����������������������������������������������������'","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn_");
$prenom = strtr($prenom,"�����������������������������������������������������'","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn_");

$pdf->Output('fiche_adherent_'.$nom.'_'.$prenom.'.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
