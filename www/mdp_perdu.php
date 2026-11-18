<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
require_once('../metier/utilisateurs.php');

// Même message que l'adresse corresponde ou non à un compte : la page ne doit pas
// révéler quels comptes existent.
$smarty->assign('message_mdp',"");
$email_temp = "";
$forcer = false;

if(isset($_GET["email"]) && $_GET["email"]!="")
{
	// Envoi depuis la liste des utilisateurs : réservé aux utilisateurs connectés.
	if (!gestionIdentification($connexion))
	{
		header("Location:index.php");
		exit();
	}
	$email_temp = $_GET["email"];
	$forcer = true;
}
else if(isset($_POST["email"]) && $_POST["email"]!="")
{
	// Champ piège invisible : un humain le laisse vide, un robot le remplit.
	if(!isset($_POST["site_web"]) || $_POST["site_web"]=="")
	{
		$email_temp = $_POST["email"];
	}
	$smarty->assign('message_mdp',"Si cette adresse correspond à un compte, vous allez recevoir un email avec un lien pour réinitialiser votre mot de passe. Le lien est valable une heure.");
}

$envoi = false;
if($email_temp!="")
{
	$demande = MdpPerduDemander($connexion, $email_temp, time(), $forcer);
	if($demande)
	{
		$hote = isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : "";
		$lien_mdp = cdfUrlSite(CDF_ENV, $hote)."/mdp_reinitialiser.php?cle=".urlencode($demande["jeton"])."&email=".urlencode($demande["email_utilisateur"]);
		$nom_utilisateur = $demande["nom_utilisateur"];
		$prenom_utilisateur = $demande["prenom_utilisateur"];

		$mail_subject = $prenom_utilisateur." ".$nom_utilisateur." : Réinitialisation de votre mot de passe sur le site www.cdf-genay.com";
		$mail_to = $demande["email_utilisateur"];

		$mail_message =
			" Bonjour ".$prenom_utilisateur." ".$nom_utilisateur.","
			."<br /><br /> Vous avez demandé à réinitialiser votre mot de passe pour le site www.cdf-genay.com."
			."<br /><br /> Si vous êtes à l'origine de cette demande, cliquez sur le lien ci-dessous (ou copiez-le sur la barre d'adresse de votre navigateur) pour obtenir votre nouveau mot de passe. Ce lien est valable une heure et ne sert qu'une fois."
			."<br /><br /> Lien pour réinitialiser votre mot de passe: ".$lien_mdp;
			$mail_message .= "<br /><br /> Si vous n'êtes pas à l'origine de cette demande, ignorez ce message : votre mot de passe reste inchangé.";
			$mail_message .= "<br /><br /> Cordialement, ";
			$mail_message .= "<br /><br /> Comité des Fêtes de Genay";
			$mail_message .= "<br /><br /> http://www.cdf-genay.com";

		// Pas de copie cachée : la copie contiendrait le lien de réinitialisation.
		$envoi = GestionEnvoiMail($mail_to, $mail_subject, $mail_message, false);
	}
}

if(isset($_GET["email"]) && $_GET["email"]!="")
{
	header("Location:admin_utilisateurs_liste.php?id_admin_menu=90&envoi=".($envoi ? "ok" : "echec")."&email=".urlencode($_GET["email"]));
	exit();
}

$smarty->display('mdp_perdu.tpl');
?>
