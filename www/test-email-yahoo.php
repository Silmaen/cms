<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

/* TEST CONNEXION IDENTIFIANT */
if (!gestionIdentification($connexion))
{
	header("Location:index.php");
	exit();
}

$email_temp = "cc@capasso.fr";

if((isset($email_temp) && $email_temp!=""))
{	
	$email_temp = "cc@capasso.fr";

    $encoding = "utf-8";
	$from_name = "Comité des Fêtes de Genay";
	$from_mail = "contact@cdf-genay.com";
	$mail_subject = "Confirmation du test 4";
	$mail_to = $email_temp; 
	$mail_message =  "ceci est un test 4";


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
    $header .= "Date: ".date("r (T)")." \r\n";
    $header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

    // Send mail
    mail($mail_to, $mail_subject, $mail_message, $header);	
	
	if($email_temp!="")
	{
		Print "email envoyé cc@capasso 4";
	}
}
else
{
	Print "email échec";

}

?>