<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

/* TEST CONNEXION IDENTIFIANT */
if (!gestionIdentification($connexion))
{
	header("Location:index.php");
	exit();
}

if (!empty($_FILES['inputfile']) AND $_SESSION["droit"]==1)
{
	$m=0;
	$jason="[";
	$target="";
	if (!empty($_FILES["inputfile"]))
	{
		/* Upload du fichier */
		$target = "fichiers/".$_FILES["inputfile"]["name"][0];
		
		move_uploaded_file($_FILES['inputfile']['tmp_name'][0], $target);

		$nom_fichier_temp= explode(".", $_FILES["inputfile"]["name"][0]);
		$nom_fichier = $nom_fichier_temp[0];
		$extension_fichier = $nom_fichier_temp[1];
		$poids_fichier = $_FILES["inputfile"]["size"][0];
		$id_menu_fichier = $_POST["id_menu"];
		$id_parent_fichier = $_POST["id_parent"];
		
		$exif_image_temp = getimagesize("fichiers/".$_FILES["inputfile"]["name"][0]);
		$type_mime_fichier = mime_content_type("fichiers/".$_FILES["inputfile"]["name"][0]);	

	
		if(substr($type_mime_fichier, 0,5)=="image")
		{
			//print"A";
			$largeur_fichier = $exif_image_temp[0];
			$hauteur_fichier = $exif_image_temp[1];
		}
		else
		{
			//print"B";
			$largeur_fichier = 0;
			$hauteur_fichier = 0;
		}
		
		
		/* Insertion du fichier dans la BDD */
		$sql=$connexion->prepare("INSERT INTO fichiers (id_menu, id_parent, nom_fichier, poids, largeur, hauteur, extension, type_mime, date_creation, id_membre_auteur) VALUES (:id_menu, :id_parent, :nom_fichier, :poids, :largeur, :hauteur, :extension, :type_mime, :date_creation, :id_membre_auteur)");

		$sql_exec=$sql->execute([":id_menu"=>$id_menu_fichier, ":id_parent"=>$id_parent_fichier, ":nom_fichier"=>$nom_fichier, ":poids"=>$poids_fichier, ":largeur"=>$largeur_fichier, ":hauteur"=>$hauteur_fichier, ":extension"=>$extension_fichier, ":type_mime"=>$type_mime_fichier, ":date_creation"=>date("Y-m-d"), ":id_membre_auteur"=>$_SESSION["id_utilisateur"]]);	
		
		if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table fichiers";
	}
	$jason.=$target."]";
	echo json_encode(['uploaded' => $jason]); 
} 
else 
{
	echo json_encode(['error'=>'Aucun fichier à importer.']); 
}
?>