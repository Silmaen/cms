<?php
/* Smarty version 3.1.32, created on 2019-02-19 11:51:51
  from 'C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\iscriptura2019\administrateur\templates\admin_utilisateurs_groupes_formulaire.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5c6bdfc7207499_35877143',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '52bc436ae259da0ff8b5eacf03b814ad5bd1df30' => 
    array (
      0 => 'C:\\Program Files (x86)\\EasyPHP-Devserver-17\\eds-www\\iscriptura2019\\administrateur\\templates\\admin_utilisateurs_groupes_formulaire.tpl',
      1 => 1550573444,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5c6bdfc7207499_35877143 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<form class="col-12 needs-validation mb-5" action="admin_utilisateurs_groupes_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
		<input type="hidden" id="id_utilisateur_groupe" name="id_utilisateur_groupe" value="<?php echo $_smarty_tpl->tpl_vars['id_utilisateur_groupe']->value;?>
">
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="libelle_utilisateur_groupe">Libellé:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="libelle_utilisateur_groupe" name="libelle_utilisateur_groupe" value="<?php echo $_smarty_tpl->tpl_vars['libelle_utilisateur_groupe']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir un libellé valide</div>
			</div>
		</div>

		<div class="form-group row">
			<div class="col-sm-10">
				<input type="hidden" class="form-control form-control-danger" id="ordre" name="ordre" value="<?php echo $_smarty_tpl->tpl_vars['ordre']->value;?>
" required>	
			</div>
		</div>


		<div class="container-fluid">		
			<hr class="style-5 mt-5 bg-vert">
			<h2 class="text-left text-gris mb-5">Droits des utilisateurs</h2>
		</div>
		
		
		
		<div class="form-group row">
			
			<?php
$__section_liste_menus_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_menus']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_menus_0_total = $__section_liste_menus_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_menus'] = new Smarty_Variable(array());
if ($__section_liste_menus_0_total !== 0) {
for ($__section_liste_menus_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index'] = 0; $__section_liste_menus_0_iteration <= $__section_liste_menus_0_total; $__section_liste_menus_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index']++){
?>
				<label class="col-sm-2 col-form-label" for="id_amin_menu_<?php echo $_smarty_tpl->tpl_vars['liste_menus']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index'] : null)]['id_admin_menu'];?>
"><?php echo $_smarty_tpl->tpl_vars['liste_menus']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index'] : null)]['titre_menu'];?>
</label>
				<div class="col-sm-10">
					<select class="form-control form-control-danger" id="id_amin_menu_<?php echo $_smarty_tpl->tpl_vars['liste_menus']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index'] : null)]['id_admin_menu'];?>
" name="id_amin_menu_<?php echo $_smarty_tpl->tpl_vars['liste_menus']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index'] : null)]['id_admin_menu'];?>
">
						
						<?php if ($_smarty_tpl->tpl_vars['liste_menus']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index'] : null)]['droit'] == '0') {?>
							<option value="0" selected>Aucun droit</option>
						<?php } elseif ($_smarty_tpl->tpl_vars['liste_menus']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index'] : null)]['droit'] == '1') {?>
							<option value="1" selected>Tous les droits</option>
						<?php } elseif ($_smarty_tpl->tpl_vars['liste_menus']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_menus']->value['index'] : null)]['droit'] == '2') {?>
							<option value="1" selected>Lecture seule</option>
						<?php }?>
						<option value="0">Aucun droit</option>
						<option value="1">Tous les droits</option>
						<option value="2">Lecture seule</option>
					</select>
				</div>
			
			<?php
}
}
?>
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
