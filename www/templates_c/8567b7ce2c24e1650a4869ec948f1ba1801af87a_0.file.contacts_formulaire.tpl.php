<?php
/* Smarty version 3.1.32, created on 2019-02-19 11:45:57
  from 'C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\iscriptura2019\administrateur\templates\contacts_formulaire.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5c6bde65725779_43020925',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8567b7ce2c24e1650a4869ec948f1ba1801af87a' => 
    array (
      0 => 'C:\\Program Files (x86)\\EasyPHP-Devserver-17\\eds-www\\iscriptura2019\\administrateur\\templates\\contacts_formulaire.tpl',
      1 => 1550572807,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5c6bde65725779_43020925 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<form class="col-12 needs-validation mb-5" action="contacts_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
		<input type="hidden" id="id_contact" name="id_contact" value="<?php echo $_smarty_tpl->tpl_vars['id_contact']->value;?>
">
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="nom">Nom:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="nom" name="nom" value="<?php echo $_smarty_tpl->tpl_vars['nom']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir un nom valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="prenom">Prenom:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="prenom" name="prenom" value="<?php echo $_smarty_tpl->tpl_vars['prenom']->value;?>
">
				<div class="invalid-feedback">Veuillez saisir un prenom valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="telephone">Téléphone:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="telephone" name="telephone" value="<?php echo $_smarty_tpl->tpl_vars['telephone']->value;?>
">
				<div class="invalid-feedback">Veuillez saisir un telephone valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="email">Email:</label>
			<div class="col-sm-10">
				<input type="email" class="form-control form-control-danger" id="email" name="email" value="<?php echo $_smarty_tpl->tpl_vars['email']->value;?>
">
				<div class="invalid-feedback">Veuillez saisir un email valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label text-left" for="date_contact">Date:</label>
			<div class="col-sm-2">
				<input type="date" class="form-control form-control-danger" id="date_contact" name="date_contact" value="<?php echo $_smarty_tpl->tpl_vars['date_contact']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="message">Message:</label>
			<div class="col-sm-10">
				<textarea class="form-control form-control-danger" id="message=" name="message"><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</textarea>
				<div class="invalid-feedback">Veuillez saisir un message valide</div>
			</div>
		</div>
		
		<div class="form-group row mr-1 float-right">
			<a href="<?php echo $_SESSION['nom_table'];?>
_liste.php?id_admin_menu=<?php echo $_SESSION['id_admin_menu_selectionne'];?>
" class="btn btn-lg btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Annuler" aria-pressed="true"><i class="fas fa-times-circle fa-lg"></i></a>
			<?php if ($_SESSION['droit'] == 1) {?>
				&nbsp;&nbsp;&nbsp;
				<button id="btnSubmit" type="submit" class="btn btn-lg btn-vert"><i class="fas fa-check-circle fa-lg"></i></button>
			<?php }?>
		</div>		
	
		
	</form> 	

	

</body>
</html><?php }
}
