<?php
/* Smarty version 3.1.32, created on 2023-06-27 15:26:46
  from '/home/cdfgenaytb/www/templates/mdp_reinitialiser.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_649ae396d53ca7_18062935',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '860b497fbde55eac00ff277a7e7c2ff016eeaf64' => 
    array (
      0 => '/home/cdfgenaytb/www/templates/mdp_reinitialiser.tpl',
      1 => 1686676211,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header_mdp.tpl' => 1,
  ),
),false)) {
function content_649ae396d53ca7_18062935 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header_mdp.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>



	<div class="container-fluid">
		<h1 class="text-center text-gris mt-1 mb-1">Réinitialisation de votre mot de passe</h1>
		<br/>
	</div>
	
	<?php if ($_smarty_tpl->tpl_vars['resultat']->value != "1") {?>
	
		<div class="container-fluid col-12" style="max-width: 600px;">
			<form id="reinitialisation" name="reinitialisation" class="needs-validation text-center" action="mdp_reinitialiser.php" method="POST">
			<input type="hidden" id="email" name="email" value="<?php echo $_smarty_tpl->tpl_vars['email']->value;?>
">
			<input type="hidden" id="cle" name="cle" value="<?php echo $_smarty_tpl->tpl_vars['cle']->value;?>
">
				
				<h3 class="mb-3 font-weight-normal">Veuillez saisir votre mot de passe</h3>
				
				<?php if ($_smarty_tpl->tpl_vars['message_mdp']->value != '') {?>
					<h6 class="mb-3 text-danger font-weight-bold"><?php echo $_smarty_tpl->tpl_vars['message_mdp']->value;?>
</h6>
				<?php }?>
						
				<div class="form-group row justify-content-center">
					<div class="col-12">
						<label class="col-form-label" for="mdp1">Nouveau mot de passe</label>
						<input type="password" class="form-control form-control-danger" id="mdp1" name="mdp1" required autofocus pattern="(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])\S{8,}">
						<div class="invalid-feedback">Veuillez saisir un email valide</div>
					</div>
					<div class="col-12">
						<label class="col-form-label" for="mdp2">Confirmer votre mot de passe</label>
						<input type="password" class="form-control form-control-danger" id="mdp2" name="mdp2" required pattern="(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])\S{8,}">
						<div class="invalid-feedback">Veuillez saisir un email valide</div>
					</div>
				</div>


				<div class="container-fluid">		
					<hr class="col-12 style-5 mt-5 bg-vert">
					<div class="row text-left text-gris">
						Votre mot de passe doit contenir 8 caractères<br />
						Votre mot de passe doit contenir au moins 1 lettre majuscule <br />
						Votre mot de passe doit contenir au moins 1 nombre 
					</div>
					<hr class="style-5 bg-vert">
				</div>
				
				<div class="form-group row justify-content-center">
					<button id="btnSubmit" type="submit" class="btn btn-vert">Réinitialiser</button>
				</div>		
			</form>
		</div>
		
	<?php } else { ?>
	
		<?php if ($_smarty_tpl->tpl_vars['message_mdp']->value != '') {?>
			<div class="container-fluid col-12 text-center" style="max-width: 600px;"
				<h6 class="mb-3 text-danger font-weight-bold text-center"><?php echo $_smarty_tpl->tpl_vars['message_mdp']->value;?>
</h6>
				<br />
				<h6 class="mb-3 text-success font-weight-bold text-center">
					<br />
					<a href="index.php">Cliquer ici pour vous connecter.</a>
				</h6>
			</div>
		<?php }?>	
		
	<?php }?>
	
	
	
	
	
	
<?php echo '<script'; ?>
 type="text/javascript">
$('#reinitialisation').submit(function(e) {
    if ($('#mdp1').val() !== $('#mdp2').val()) {
        alert('Attention, le mot de passe de confirmation est différent du mot de passe !');
        e.preventDefault();
        return false;
    }
});
<?php echo '</script'; ?>
> 	
	

</body>
</html><?php }
}
