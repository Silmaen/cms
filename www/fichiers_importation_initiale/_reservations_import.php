<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
/*
print "bloc 1";

// BLOC 1 - CREATION DES N° DE RESERVATION
// Initialisation des valeurs
$id_reservation=0;
$id_client_temp = 0;
$date_depart_temp = "";
 
 $connexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

// On parcoure la table reservations_test pour récupérer chaque ligne de réservation
$sql=$connexion->prepare("SELECT * from reservations_test ORDER BY id_client ASC, date_depart ASC");

$sql_exec=$sql->execute();	
if(!$sql_exec) echo "MODIFIER - DONNEES : Pb d'accès à la table reservations";
else
{
	foreach ($sql->fetchAll() as $row) 
	{
		print "1=> id_reservation=".$id_reservation."   id_client=".$row["id_client"]."   date_depart=".$row["date_depart"]."<br/>";
		if ($id_client_temp==$row["id_client"] AND $date_depart_temp==$row["date_depart"])
		{}		
		else
		{
			$id_client_temp = $row["id_client"];
			$date_depart_temp = $row["date_depart"];
			$id_reservation++;
			print "2 => id_reservation=".$id_reservation."   id_client=".$row["id_client"]."   date_depart=".$row["date_depart"]."<br/><br/>";
			
			$sql=$connexion->prepare("UPDATE reservations_test_avec_id SET id_reservation=:id_reservation WHERE (id_client=:id_client AND date_depart=:date_depart)");
				
			$sql_exec=$sql->execute([":id_reservation"=>$id_reservation, ":id_client"=>$id_client_temp, ":date_depart"=>$date_depart_temp]);	
			
			if(!$sql_exec) echo "MODIFIER - DONNEES : Pb d'accès à la table reservations_test_avec_id";		
			
		}
	}
}

// FIN BLOC 1
*/
/*
//BLOC 2 - calcul des dates de retour à partir des dates de départ
print "bloc 2";

// On parcoure la tablea reservations_test_avec_id pour récupérer chaque ligne de réservation
$sql=$connexion->prepare("SELECT * from reservations_test_avec_id ORDER BY date_depart ASC");

$sql_exec=$sql->execute();	
if(!$sql_exec) echo "MODIFIER - DONNEES : Pb d'accès à la table reservations_test_avec_id";
else
{
	foreach ($sql->fetchAll() as $row) 
	{
		$date_depart = DateTime::createFromFormat('Y-m-d', $row["date_depart"]);
		$resultat = $date_depart->format('N'); 
		
		$date_depart_temp = date_create($row["date_depart"]);
		date_add($date_depart_temp, date_interval_create_from_date_string('4 days'));
		$date_retour =  date_format($date_depart_temp, 'Y-m-d');
		
		if($resultat==1){date_add($date_depart, date_interval_create_from_date_string('4 days'));}
		if($resultat==2){date_add($date_depart, date_interval_create_from_date_string('3 days'));}
		if($resultat==3){date_add($date_depart, date_interval_create_from_date_string('2 days'));}
		if($resultat==4){date_add($date_depart, date_interval_create_from_date_string('1 days'));}
		if($resultat==5){date_add($date_depart, date_interval_create_from_date_string('3 days'));}
		if($resultat==6){date_add($date_depart, date_interval_create_from_date_string('2 days'));}
		if($resultat==7){date_add($date_depart, date_interval_create_from_date_string('1 days'));}
		
		$date_retour =  date_format($date_depart, 'Y-m-d');
		print "date_depart= ".$row["date_depart"]."   jour= ".$resultat."   date_detour= ".$date_retour."<br/>"; // jour=1 => lundi
		
		$sql=$connexion->prepare("UPDATE reservations_test_avec_id SET date_retour=:date_retour WHERE (id_reservation=:id_reservation)");
				
		$sql_exec=$sql->execute([":id_reservation"=>$row["id_reservation"], ":date_retour"=>$date_retour]);		
	}
}

// FIN BLOC 2
*/




//BLOC 3 - calcul des années de réservation à partir de la date de départ
print "bloc 3";

// On parcoure la tablea reservations_test_avec_id pour récupérer chaque ligne de réservation
$sql=$connexion->prepare("SELECT * from reservations_test_avec_id ORDER BY date_depart ASC");

$sql_exec=$sql->execute();	
if(!$sql_exec) echo "MODIFIER - DONNEES : Pb d'accès à la table reservations_test_avec_id";
else
{
	foreach ($sql->fetchAll() as $row) 
	{
		$date_depart = DateTime::createFromFormat('Y-m-d', $row["date_depart"]);
		$annee_reservation = $date_depart->format('Y'); 
		
		print "date_depart = ".$row["date_depart"]." année = ".$annee_reservation." <br />";
		
		$sql=$connexion->prepare("UPDATE reservations_test_avec_id SET annee_reservation=:annee_reservation, id_etat=1, id_statut_reservation=2, id_membre_auteur=1, date_modification='2019-03-27' WHERE (id_reservation=:id_reservation)");
				
		$sql_exec=$sql->execute([":id_reservation"=>$row["id_reservation"], ":annee_reservation"=>$annee_reservation]);		
	}
}

// FIN BLOC 3
 
?>