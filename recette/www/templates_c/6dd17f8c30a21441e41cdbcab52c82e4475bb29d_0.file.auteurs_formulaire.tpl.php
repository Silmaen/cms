<?php
/* Smarty version 3.1.32, created on 2019-02-19 11:28:24
  from 'C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\iscriptura2019\administrateur\templates\auteurs_formulaire.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5c6bda48138572_78755949',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6dd17f8c30a21441e41cdbcab52c82e4475bb29d' => 
    array (
      0 => 'C:\\Program Files (x86)\\EasyPHP-Devserver-17\\eds-www\\iscriptura2019\\administrateur\\templates\\auteurs_formulaire.tpl',
      1 => 1550572101,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5c6bda48138572_78755949 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<form class="col-12 needs-validation mb-5" action="auteurs_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
		<input type="hidden" id="id_auteur" name="id_auteur" value="<?php echo $_smarty_tpl->tpl_vars['id_auteur']->value;?>
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
			<label class="col-sm-2 col-form-label" for="email">Email:</label>
			<div class="col-sm-10">
				<input type="email" class="form-control form-control-danger" id="email" name="email" value="<?php echo $_smarty_tpl->tpl_vars['email']->value;?>
">
				<div class="invalid-feedback">Veuillez saisir un email valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="site">Site:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="site" name="site" value="<?php echo $_smarty_tpl->tpl_vars['site']->value;?>
">
				<div class="invalid-feedback">Veuillez saisir un site valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="atelier">Atelier:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="atelier" name="atelier" value="<?php echo $_smarty_tpl->tpl_vars['atelier']->value;?>
">
				<div class="invalid-feedback">Veuillez saisir un atelier valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="ordre">Ordre:</label>
			<div class="col-sm-10">
				<input type="number" class="form-control form-control-danger" id="ordre" name="ordre" value="<?php echo $_smarty_tpl->tpl_vars['ordre']->value;?>
" required>
				<small id="aide_ordre" class="form-text text-muted">
					Ordre d'affichage dans la liste des événéments. Si vous souhaitez intercaler votre événement à la 3è place, saisissez 3. Les événements suivants seront automatiquement décalés.
				</small>		
				<div class="invalid-feedback">Veuillez saisir un nombre valide</div>
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
