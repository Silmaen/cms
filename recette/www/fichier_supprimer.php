<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

/* TEST CONNEXION IDENTIFIANT */
if (!gestionIdentification($connexion))
{
	header("Location:index.php");
	exit();
}

$sql="SELECT id_fichier, nom_fichier, extension FROM fichiers WHERE (id_fichier='".$_POST["key"]."')";

if(!$connexion->query($sql))
{
	echo json_encode(['error'=>'Supprimer : Pb d\'accès à la table fichiers ou Aucun fichier à supprimer.']);
}
elseif ($_SESSION["droit"]==1)
{
	foreach ($connexion->query($sql) as $row) 
	{	
		$fichier = "fichiers/".$row["nom_fichier"].".".$row["extension"];
		if (!unlink($fichier))
		{
			echo json_encode(['error'=>'Aucun fichier à supprimer.']);
		}
		else
		{
			$sql=$connexion->prepare("DELETE FROM fichiers WHERE (id_fichier=:id_fichier)");
			$sql_suppression=$sql->execute([":id_fichier"=>$_POST["key"]]);
			if(!$sql_suppression) echo "Supprimer : Pb d'accès à la table evenements";
			else
			{
				$message="Suppression enregistrée";		
			}
		}
	}
	echo json_encode(['supprime' => 'supprime']);
}
?>