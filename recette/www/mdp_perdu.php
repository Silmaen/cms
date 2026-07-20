<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

$smarty->assign('message_mdp',"Vous allez recevoir un email avec un lien pour réinitialiser votre mot de passe"); 

if((isset($_POST["email"]) && $_POST["email"]!="") OR (isset($_GET["email"]) && $_GET["email"]!=""))
{
	if(isset($_POST["email"]) && $_POST["email"]!="")
	{
		$email_temp = $_POST["email"];
	}
	else if(isset($_GET["email"]) && $_GET["email"]!="")
	{
		$email_temp = $_GET["email"];
	}
	
 	// Recherche de l'email de l'utilisateur pour validation
	$sql=$connexion->prepare("SELECT nom_utilisateur, prenom_utilisateur, email_utilisateur, cle_utilisateur FROM admin_utilisateurs WHERE (email_utilisateur= :email)");
	$sql_exec=$sql->execute([":email"=>$email_temp]);	
	if(!$sql_exec) echo "Consultation: Pb d'accès à la table admin_utilisateurs";
	else
	{
		foreach ($sql->fetchAll() as $row) 
		{		
			$lien_mdp="http://www.cdf-genay.com/mdp_reinitialiser.php?cle=".$row["cle_utilisateur"]."&email=".$row["email_utilisateur"];
			$nom_utilisateur = $row["nom_utilisateur"];
			$prenom_utilisateur = $row["prenom_utilisateur"];
		}
	}

    $encoding = "utf-8";
	$from_name = "Comité des Fêtes de Genay";
	$from_mail = "no-reply@cdf-genay.com";
	$mail_subject = $prenom_utilisateur." ".$nom_utilisateur." : Réinitialisation de votre mot de passe sur le site www.cdf-genay.com";
	$mail_to = $email_temp;

	$mail_message =     
		" Bonjour ".$prenom_utilisateur." ".$nom_utilisateur.","
        ."<br /><br /> Vous avez demandé à réinitialiser votre mot de passe pour le site www.cdf-genay.com."
        ."<br /><br /> Si vous êtes à l'origine de cette demande, cliquez sur le lien ci-dessous (ou copiez-le sur la barre d'adresse de votre navigateur) pour obtenir votre nouveau mot de passe."
        ."<br /><br /> Lien pour réinitialiser votre mot de passe: ".$lien_mdp;
        $mail_message .= "<br /><br /> Cordialement, ";
        $mail_message .= "<br /><br /> Comité des Fêtes de Genay";
		$mail_message .= "<br /><br /> http://www.cdf-genay.com";
	
	
    // Preferences for Subject field
    $subject_preferences = array(
        "input-charset" => $encoding,
        "output-charset" => $encoding,
        "line-length" => 76,
        "line-break-chars" => "\r\n"
    );

    // Mail header
    $header = "Content-type: text/html; charset=".$encoding." \r\n";
    $header .= "From: ".$from_name." <".$from_mail."> \r\n";
    $header .= "MIME-Version: 1.0 \r\n";
    $header .= "Content-Transfer-Encoding: 8bit \r\n";
    $header.= "Bcc: no-reply@cdf-genay.com \r\n";
	$header .= "Date: ".date("r (T)")." \r\n";
    //$header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

    // Send mail
    mail($mail_to, $mail_subject, $mail_message, $header);	
	
	if(isset($_GET["email"]) && $_GET["email"]!="")
	{
		header("Location:admin_utilisateurs_liste.php?id_admin_menu=90&envoi=ok&email=".$_GET["email"]);
		exit();
	}
}
else
{
	$smarty->assign('message_mdp',"");
}

$smarty->display('mdp_perdu.tpl');
?>