<?php
/* Smarty version 3.1.32, created on 2018-09-20 08:58:49
  from 'C:\wamp64\www\bootstrap\administrateur\templates\login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5ba361494928f0_66172800',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '13fa7cf5dbf9e10999c78e55a25a7fcce65b4193' => 
    array (
      0 => 'C:\\wamp64\\www\\bootstrap\\administrateur\\templates\\login.tpl',
      1 => 1537433625,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5ba361494928f0_66172800 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>



	<div class="container-fluid">
		<h1 class="text-center text-gris mt-1 mb-1">Identification</h1>
		<br/>
		
	</div>
	
	<div class="container-fluid col-12" style="max-width: 450px;">
		<form class="needs-validation text-center" action="accueil.php" method="POST">
			<h1 class="h3 mb-3 font-weight-normal">Veuillez vous connecter</h1>
			
			<div class="form-group row justify-content-center">
				<div class="col-12">
					<input type="email" class="form-control form-control-danger" id="email" name="email" placeholder="Email" required autofocus>
					<div class="invalid-feedback">Veuillez saisir un email valide</div>
				</div>
			</div>
			<div class="form-group row justify-content-center">
				<div class="col-12">
					<input type="password" class="form-control form-control-danger" id="mdp" name="mdp" placeholder="Mot de passe" required autofocus>
					<div class="invalid-feedback">Veuillez saisir un mot de passe valide</div>
				</div>
			</div>
			
			<div class="row justify-content-center">
				<div class="col-12 mb-3"><a class="text-vert" href="mdp_perdu.php">J'ai perdu mon mot de passe</a></div>
			</div>
			
			<div class="form-group row justify-content-center">
				<button id="btnSubmit" type="submit" class="btn btn-vert">Valider</button>
			</div>		
		</form>
	</div>
	

</body>
</html><?php }
}
