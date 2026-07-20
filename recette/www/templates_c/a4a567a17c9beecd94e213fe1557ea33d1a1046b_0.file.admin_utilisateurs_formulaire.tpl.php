<?php
/* Smarty version 3.1.32, created on 2019-03-13 18:38:15
  from 'C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\cfg\fr\templates\admin_utilisateurs_formulaire.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5c894007253ba7_45719517',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a4a567a17c9beecd94e213fe1557ea33d1a1046b' => 
    array (
      0 => 'C:\\Program Files (x86)\\EasyPHP-Devserver-17\\eds-www\\cfg\\fr\\templates\\admin_utilisateurs_formulaire.tpl',
      1 => 1552498692,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5c894007253ba7_45719517 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>



<?php if ($_SESSION['id_utilisateur_groupe'] <= 1) {?>
	<?php $_smarty_tpl->_assignInScope('readonly', '');?> 
<?php } else { ?>
	<?php $_smarty_tpl->_assignInScope('readonly', "readonly");
}?>


	<form class="col-12 needs-validation mb-5" action="admin_utilisateurs_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
		<input type="hidden" id="id_utilisateur" name="id_utilisateur" value="<?php echo $_smarty_tpl->tpl_vars['id_utilisateur']->value;?>
">
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="date_creation">Date de création:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_creation" name="date_creation" value="<?php echo $_smarty_tpl->tpl_vars['date_creation']->value;?>
" readonly>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_modification">Date de modification:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_modification" name="date_modification" value="<?php echo $_smarty_tpl->tpl_vars['date_modification']->value;?>
" readonly>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="modifie_par">Par:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="modifie_par" name="modifie_par" value="<?php echo $_smarty_tpl->tpl_vars['modifie_par']->value;?>
" readonly>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="libelle_etat">Etat:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="libelle_etat" name="libelle_etat" value="<?php echo $_smarty_tpl->tpl_vars['libelle_etat']->value;?>
" readonly>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="nom_utilisateur">Nom:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="nom_utilisateur" name="nom_utilisateur" value="<?php echo $_smarty_tpl->tpl_vars['nom_utilisateur']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 required>
				<div class="invalid-feedback">Veuillez saisir un nom valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="prenom_utilisateur">Prenom:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="prenom_utilisateur" name="prenom_utilisateur" value="<?php echo $_smarty_tpl->tpl_vars['prenom_utilisateur']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
				<div class="invalid-feedback">Veuillez saisir un prenom valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="email_utilisateur">Email:</label>
			<div class="col-sm-10">
				<input type="email" class="form-control form-control-danger" id="email_utilisateur" name="email_utilisateur" value="<?php echo $_smarty_tpl->tpl_vars['email_utilisateur']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
				<div class="invalid-feedback">Veuillez saisir un email valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="mdp_utilisateur">Mot de passe:</label>
			<div class="col-sm-10">
				<input type="password" class="form-control form-control-danger" id="mdp_utilisateur" name="mdp_utilisateur" value="<?php echo $_smarty_tpl->tpl_vars['mdp_utilisateur']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 required>
				<div class="invalid-feedback">Veuillez saisir un mot de passe valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="groupe_utilisateur">Groupe</label>
			<div class="col-sm-10">
				<select class="form-control form-control-danger" id="id_utilisateur_groupe" name="id_utilisateur_groupe" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
					
					<?php if ($_smarty_tpl->tpl_vars['id_utilisateur_groupe']->value != '') {?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['id_utilisateur_groupe']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['libelle_utilisateur_groupe']->value;?>
</option>
					<?php }?>
					<?php
$__section_liste_groupes_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_groupes']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_groupes_0_total = $__section_liste_groupes_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_groupes'] = new Smarty_Variable(array());
if ($__section_liste_groupes_0_total !== 0) {
for ($__section_liste_groupes_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index'] = 0; $__section_liste_groupes_0_iteration <= $__section_liste_groupes_0_total; $__section_liste_groupes_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index']++){
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['liste_groupes']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index'] : null)]['id_utilisateur_groupe'];?>
"><?php echo $_smarty_tpl->tpl_vars['liste_groupes']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index'] : null)]['libelle_utilisateur_groupe'];?>
</option>
					<?php
}
}
?>

				</select>
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
