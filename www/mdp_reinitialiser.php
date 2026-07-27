<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

$smarty->assign('message_mdp',"");
$smarty->assign('cle',$_GET["cle"]);
$smarty->assign('email',$_GET["email"]);

if(isset($_POST["mdp1"]) && $_POST["mdp1"]!="" AND isset($_POST["mdp2"]) && $_POST["mdp2"]!="" AND isset($_POST["cle"]) && $_POST["cle"]!="" AND isset($_POST["email"]) && $_POST["email"]!="" AND $_POST["mdp1"]===$_POST["mdp2"])
{
	$connexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

	$sql=$connexion->prepare("SELECT t1.id_utilisateur FROM admin_utilisateurs AS t1 WHERE (t1.email_utilisateur = :email AND t1.cle_utilisateur= :cle)");
	$sql_exec=$sql->execute([":email"=>$_POST["email"], ":cle"=>$_POST["cle"]]);
	if(!$sql_exec) echo "VALIDER - DONNEES : Pb d'accès à la table utilisateurs";
	else
	{
		foreach ($sql->fetchAll() as $row)
		{

			$sql=$connexion->prepare("UPDATE admin_utilisateurs SET mdp_utilisateur=:mdp_utilisateur WHERE (id_utilisateur=:id_utilisateur)");
			$sql_exec=$sql->execute([":id_utilisateur"=>$row["id_utilisateur"], ":mdp_utilisateur"=>GestionHashage($_POST["mdp1"])]);
			if(!$sql_exec) echo "Update-Valider : Pb d'accès à la table admin_utilisateurs";
			else
			{
				$smarty->assign('message_mdp',"Votre mot de passe a été actualisé.");
				$smarty->assign('resultat',"1");
			}
		}
	}
}

$smarty->display('mdp_reinitialiser.tpl');
?>
