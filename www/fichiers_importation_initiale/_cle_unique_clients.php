<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

// Cette page sert à ajouter une clé unique générée par PHP dans un champ de la BBD table clients

$sql=$connexion->prepare("SELECT id_client FROM clients_import ORDER BY id_client");
$sql_exec=$sql->execute();	
if(!$sql_exec) echo "SELECT - DONNEES : Pb d'accès à la table clients";
else
{
	foreach ($sql->fetchAll() as $row) 
	{
		print "id_client=".$row["id_client"]."  clé=".uniqid()." <br/>"; 
		
		//$connexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		
		$sql=$connexion->prepare("UPDATE clients_import SET cle_client=:cle_client WHERE (id_client=:id_client)");
		$sql_exec=$sql->execute([":id_client"=>$row["id_client"], ":cle_client"=>uniqid()]);	
		if(!$sql_exec) echo "Modifier-Valider : Pb d'accès à la table clients_import";
	}
}

?>