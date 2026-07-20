<?php
/* Smarty version 3.1.32, created on 2023-06-27 15:31:17
  from '/home/cdfgenaytb/www/templates/mdp_perdu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_649ae4a579f459_37335235',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4794925e258ac48ba2c978566dfc0aa388af6328' => 
    array (
      0 => '/home/cdfgenaytb/www/templates/mdp_perdu.tpl',
      1 => 1686676211,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header_mdp.tpl' => 1,
  ),
),false)) {
function content_649ae4a579f459_37335235 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header_mdp.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>



	<div class="container-fluid">
		<h1 class="text-center text-gris mt-1 mb-1">J'ai perdu mon mot de passe</h1>
		<br/>
		
	</div>
	
	<div class="container-fluid col-12" style="max-width: 450px;">
		<form class="needs-validation text-center" action="mdp_perdu.php" method="POST">
			
			<h3 class="mb-3 font-weight-normal">Veuillez saisir votre email</h3>
			
			<?php if ($_smarty_tpl->tpl_vars['message_mdp']->value != '') {?>
				<h6 class="mb-3 text-danger font-weight-bold"><?php echo $_smarty_tpl->tpl_vars['message_mdp']->value;?>
</h6>
			<?php } else { ?>
			
				<div class="form-group row justify-content-center">
					<div class="col-12">
						<input type="email" class="form-control form-control-danger" id="email" name="email" placeholder="Email" required autofocus>
						<div class="invalid-feedback">Veuillez saisir un email valide</div>
					</div>
				<div class="form-group row justify-content-center">
					<button id="btnSubmit" type="submit" class="btn btn-vert">Envoyer</button>
				</div>	
			<?php }?>

		</form>
	</div>
	

</body>
</html><?php }
}
