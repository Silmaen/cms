<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
require_once('../metier/utilisateurs.php');

// Le lien arrive en GET ; le formulaire renvoie la clé et l'e-mail en POST.
$cle = isset($_POST["cle"]) ? $_POST["cle"] : (isset($_GET["cle"]) ? $_GET["cle"] : "");
$email = isset($_POST["email"]) ? $_POST["email"] : (isset($_GET["email"]) ? $_GET["email"] : "");

$smarty->assign('message_mdp',"");
$smarty->assign('cle',$cle);
$smarty->assign('email',$email);

if(isset($_POST["mdp1"]) && $_POST["mdp1"]!="" AND isset($_POST["mdp2"]) && $_POST["mdp2"]!="")
{
	if($_POST["mdp1"]!==$_POST["mdp2"])
	{
		$smarty->assign('message_mdp',"Les deux mots de passe saisis sont différents.");
	}
	else if(!MdpRespectePolitique($_POST["mdp1"]))
	{
		$smarty->assign('message_mdp',"Le mot de passe ne respecte pas les règles ci-dessous.");
	}
	else
	{
		$id_utilisateur = MdpJetonVerifier($connexion, $email, $cle, time());
		if($id_utilisateur===null)
		{
			$smarty->assign('message_mdp',"Ce lien est invalide ou a expiré. Refaites une demande depuis « J'ai perdu mon mot de passe ».");
		}
		else if(!MdpReinitialiser($connexion, $id_utilisateur, GestionHashage($_POST["mdp1"])))
		{
			echo "Update-Valider : Pb d'accès à la table admin_utilisateurs";
		}
		else
		{
			$smarty->assign('message_mdp',"Votre mot de passe a été actualisé.");
			$smarty->assign('resultat',"1");
		}
	}
}

$smarty->display('mdp_reinitialiser.tpl');
?>
