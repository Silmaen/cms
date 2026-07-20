<?php
/* Smarty version 3.1.32, created on 2019-02-11 14:28:28
  from 'C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\iscriptura2019\administrateur\templates\utilisateurs_formulaire.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5c61787cad7cd7_40656126',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '46c931b1e765656f946e6468a5cd8e02316c47fe' => 
    array (
      0 => 'C:\\Program Files (x86)\\EasyPHP-Devserver-17\\eds-www\\iscriptura2019\\administrateur\\templates\\utilisateurs_formulaire.tpl',
      1 => 1549891704,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5c61787cad7cd7_40656126 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<form class="col-12 needs-validation mb-5" action="utilisateurs_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
		<input type="hidden" id="id_utilisateur" name="id_utilisateur" value="<?php echo $_smarty_tpl->tpl_vars['id_utilisateur']->value;?>
">
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="nom_utilisateur">Nom:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="nom_utilisateur" name="nom_utilisateur" value="<?php echo $_smarty_tpl->tpl_vars['nom_utilisateur']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir un nom valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="prenom_utilisateur">Prenom:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="prenom_utilisateur" name="prenom_utilisateur" value="<?php echo $_smarty_tpl->tpl_vars['prenom_utilisateur']->value;?>
">
				<div class="invalid-feedback">Veuillez saisir un prenom valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="email_utilisateur">Email:</label>
			<div class="col-sm-10">
				<input type="email_utilisateur" class="form-control form-control-danger" id="email_utilisateur" name="email_utilisateur" value="<?php echo $_smarty_tpl->tpl_vars['email_utilisateur']->value;?>
">
				<div class="invalid-feedback">Veuillez saisir un email valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="mdp_utilisateur">Mot de passe:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="mdp_utilisateur" name="mdp_utilisateur" value="<?php echo $_smarty_tpl->tpl_vars['mdp_utilisateur']->value;?>
">
				<div class="invalid-feedback">Veuillez saisir un mot de passe valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="groupe_utilisateur">Groupe</label>
			<div class="col-sm-10">
				<select class="form-control form-control-danger" id="id_groupe_utilisateur" name="id_groupe_utilisateur">
					
					<?php if ($_smarty_tpl->tpl_vars['id_groupe']->value != '') {?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['id_groupe_utilisateur']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['libelle_groupe_utilisateur']->value;?>
</option>
					<?php }?>
					<?php
$__section_liste_groupes_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_groupes']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_groupes_0_total = $__section_liste_groupes_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_groupes'] = new Smarty_Variable(array());
if ($__section_liste_groupes_0_total !== 0) {
for ($__section_liste_groupes_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index'] = 0; $__section_liste_groupes_0_iteration <= $__section_liste_groupes_0_total; $__section_liste_groupes_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index']++){
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['liste_groupes']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index'] : null)]['id_groupe_utilisateur'];?>
"><?php echo $_smarty_tpl->tpl_vars['liste_groupes']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_groupes']->value['index'] : null)]['libelle_groupe_utilisateur'];?>
</option>
					<?php
}
}
?>

				</select>
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
			<button id="btnSubmit" type="submit" class="btn btn-vert">Valider</button>
		</div>		
	
		
	</form> 	

	

</body>
</html><?php }
}
