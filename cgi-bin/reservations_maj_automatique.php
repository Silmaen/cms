<?php

// Identifiants BDD résolus par le mécanisme unifié (variables d'environnement
// CDF_DB_* en local, sinon config_secrets.php sur OVH).
require_once(__DIR__ . '/config/identifiants_bdd.php'); // définit serveur/bdd/utilisateur/mdp

$connexion="mysql:dbname=".bdd.";host=".serveur;
try{
	$connexion=new PDO($connexion,utilisateur,mdp, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
}
catch(PDOException $e){
	printf("Échec de la connexion : %s\n", $e->getMessage());
	exit();
}

$resultat_cron ="fichier appelé <br/>";

// CHANGEMENT DE L'ETAT DES RESERVATIONS DONT LA DATE DE RETOUR > AUJOURD'HUI
$sql=$connexion->prepare("SELECT id_reservation FROM reservations WHERE date_retour<='".date("Y-m-d")."' AND id_etat=2");
$sql_exec=$sql->execute();
if(!$sql_exec) echo "SELECT - DONNEES : Pb d'accès à la table reservations";
else
{

	foreach ($sql->fetchAll() as $row)
	{
		$sql_update=$connexion->prepare("UPDATE reservations SET id_etat=2 WHERE (id_reservation=:id_reservation AND id_etat=1)");
		$sql_exec=$sql_update->execute([":id_reservation"=>$row["id_reservation"]]);
		if(!$sql_exec)
		{
			$resultat_cron = "cron erreur BDD";
		}
		else
		{
			$sql_insert=$connexion->prepare("INSERT INTO log_cron (date, id_reservation) VALUES (:date, :id_reservation)");
			$sql_exec=$sql_insert->execute([":date"=>date("Y-m-d"), ":id_reservation"=>$row["id_reservation"]]);
			if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table log_cron";
			else
			{
				$resultat_cron .= "cron réussi : MAJ => ID_reservation = ".$row["id_reservation"]."<br />";
			}
		}
	}

	$encoding = "utf-8";
	$from_name = "Comité des Fêtes de Genay";
	$from_mail = "no-reply@cdf-genay.com";
	$mail_subject = "Cron du ".date("d-m-Y");
	$mail_to = "no-reply@cdf-genay.com";
	$mail_message =  $resultat_cron;
	$mail_message .= "<br /><br />";
	$mail_message .= "<br /><br />";
	$mail_message .= "<br />Télécharger le PDF avec les réservations au départ le ".date("d-m-Y")." en cliquant sur le lien suivant :<br />";
	$mail_message .= "http://www.cdf-genay.com/impressions_automatique_reservations_jour.php?action=imprimer&date_depart_reservation=".date("Y-m-d");
	$mail_message .= "<br /><br />";
	$mail_message .= "<br />Télécharger le PDF avec les retours du  en cliquant sur le lien suivant :<br />";
	$mail_message .= "https://www.cdf-genay.com/impressions_reservations_retour.php?date_retour_reservation=".date("Y-m-d");

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
}

?>
