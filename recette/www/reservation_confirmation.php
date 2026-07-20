<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

/* TEST CONNEXION IDENTIFIANT */
if (!gestionIdentification($connexion))
{
	header("Location:index.php");
	exit();
}

if((isset($_GET["id_reservation"]) && $_GET["id_reservation"]!=""))
{	
	$nom = "";
	$prenom = "";
	$email_temp = "";
			
	// Recherche de l'email de l'utilisateur pour validation
	$sql=$connexion->prepare("SELECT t1.id_reservation, t1.id_client, t1.date_depart, t1.date_retour, t2.cle_client, t2.nom, t2.prenom, t2.email FROM reservations AS t1 LEFT JOIN clients AS t2 ON t2.id_client=t1.id_client WHERE (id_reservation= :id_reservation)");
	$sql_exec=$sql->execute([":id_reservation"=>$_GET["id_reservation"]]);	
	if(!$sql_exec) echo "Consultation: Pb d'accès à la table reservations et clients";
	else
	{
		foreach ($sql->fetchAll() as $row) 
		{		
			$lien="http://www.cdf-genay.com/fiche_reservation_client.php?action=imprimer&id_reservation=".$row["id_reservation"]."&cle_client=".$row["cle_client"];
			
			$nom = $row["nom"];
			$prenom = $row["prenom"];
			$email_temp = $row["email"];
		}
	}


    $encoding = "utf-8";
	$from_name = "Comité des Fêtes de Genay";
	$from_mail = "no-reply@cdf-genay.com";
	$mail_subject = $prenom." ".$nom." : Confirmation de votre réservation auprès du Comité des Fêtes de Genay";
	$mail_to = $email_temp;
	$mail_message =  "Bonjour ".$prenom." ".$nom.",
		<br /><br /> Nous vous confirmons votre réservation réalisée auprès du Comité des Fêtes de Genay.
		<br /><br /> Le matériel est réservé du ".GestionDate($row["date_depart"],'0')." jusqu'au ".GestionDate($row["date_retour"],'0').".
        <br /><br /> Vous pouvez consulter le détail du matériel réservé en téléchargeant votre confirmation sur le lien suivant : ".$lien." .
        <br /><br /><br /> Si la fiche ne s'affiche pas directement, merci de consulter les téléchargements sur votre ordinateur 
        <br /><br /> Cordialement, 
        <br /><br /> Comité des Fêtes de Genay
		<br /><br /> http://www.cdf-genay.com";


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
    $header .= "Bcc: no-reply@cdf-genay.com \r\n";
    $header .= "Date: ".date("r (T)")." \r\n";
    //$header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

    // Send mail
    mail($mail_to, $mail_subject, $mail_message, $header);	
	
	if($email_temp!="")
	{
		header("Location:reservations_liste.php?id_admin_menu=1&envoi=ok&email=".$_GET["email"]);
		exit();
	}
}
else
{
	header("Location:admin_utilisateurs_liste.php?id_admin_menu=1&envoi=erreur&email=".$_GET["email"]);
	exit();
}

?>