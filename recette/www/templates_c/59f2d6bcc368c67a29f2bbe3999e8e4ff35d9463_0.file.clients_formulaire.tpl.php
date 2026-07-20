<?php
/* Smarty version 3.1.32, created on 2023-06-13 19:11:04
  from '/home/cdfgenaytb/www/templates/clients_formulaire.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_6488a328e63678_79915317',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '59f2d6bcc368c67a29f2bbe3999e8e4ff35d9463' => 
    array (
      0 => '/home/cdfgenaytb/www/templates/clients_formulaire.tpl',
      1 => 1686676211,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_6488a328e63678_79915317 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php if ($_SESSION['id_utilisateur_groupe'] <= 2) {?>
	<?php $_smarty_tpl->_assignInScope('readonly', '');?>
	<?php $_smarty_tpl->_assignInScope('readonly_adhesion', '');
} else { ?>
	<?php $_smarty_tpl->_assignInScope('readonly', "readonly");?>
	<?php $_smarty_tpl->_assignInScope('readonly_adhesion', 'readonly');
}?>


	<form name="formulaire-clients" id="formulaire-clients" class="col-12 needs-validation mb-5" action="clients_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
		<input type="hidden" id="id_client" name="id_client" value="<?php echo $_smarty_tpl->tpl_vars['id_client']->value;?>
">
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="date_creation">Date de création:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_creation" name="date_creation" value="<?php echo $_smarty_tpl->tpl_vars['date_creation']->value;?>
" readonly>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_modification">Date de modification:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_modification" name="date_modification" value="<?php echo $_smarty_tpl->tpl_vars['date_modification']->value;?>
" readonly>
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
			<label class="col-sm-2 col-form-label text-left" for="id_statut_client">Statut:</label>
			<div class="col-sm-2">
				<select class="form-control form-control-danger" id="id_statut_client" name="id_statut_client" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
					
					<?php if ($_smarty_tpl->tpl_vars['id_statut_client']->value != '') {?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['id_statut_client']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['libelle_statut']->value;?>
</option>
					<?php }?>
					<?php
$__section_liste_statuts_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_statuts']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_statuts_0_total = $__section_liste_statuts_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_statuts'] = new Smarty_Variable(array());
if ($__section_liste_statuts_0_total !== 0) {
for ($__section_liste_statuts_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index'] = 0; $__section_liste_statuts_0_iteration <= $__section_liste_statuts_0_total; $__section_liste_statuts_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index']++){
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['liste_statuts']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index'] : null)]['id_statut_client'];?>
"><?php echo $_smarty_tpl->tpl_vars['liste_statuts']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index'] : null)]['libelle_statut'];?>
</option>
					<?php
}
}
?>
				</select>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="nom">Association / Société :</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="association" name="association" value="<?php echo $_smarty_tpl->tpl_vars['association']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
				<div class="invalid-feedback">Veuillez saisir une association valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="nom">Nom:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="nom" name="nom" value="<?php echo $_smarty_tpl->tpl_vars['nom']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 required>
				<div class="invalid-feedback">Veuillez saisir un nom valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="prenom">Prénom:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="prenom" name="prenom" value="<?php echo $_smarty_tpl->tpl_vars['prenom']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
				<div class="invalid-feedback">Veuillez saisir un prenom valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="adresse1">Adresse:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="adresse1" name="adresse1" value="<?php echo $_smarty_tpl->tpl_vars['adresse1']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
				<input type="text" class="form-control form-control-danger" id="adresse2" name="adresse2" value="<?php echo $_smarty_tpl->tpl_vars['adresse2']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
				<input type="text" class="form-control form-control-danger" id="adresse3" name="adresse3" value="<?php echo $_smarty_tpl->tpl_vars['adresse3']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
				<div class="invalid-feedback">Veuillez saisir une adresse valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="cp">CP:</label>
			<div class="col-sm-4">
				<input type="text" class="form-control form-control-danger" id="cp" name="cp" value="<?php echo $_smarty_tpl->tpl_vars['cp']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 required>
				<div class="invalid-feedback">Veuillez saisir un cp valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="ville">Ville:</label>
			<div class="col-sm-4">
				<input type="text" class="form-control form-control-danger" id="ville" name="ville" value="<?php echo $_smarty_tpl->tpl_vars['ville']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 required>
			<div class="invalid-feedback">Veuillez saisir une ville valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="telephone">Téléphone:</label>
			<div class="col-sm-4">
				<input type="tel" class="form-control form-control-danger" id="telephone" name="telephone" value="<?php echo $_smarty_tpl->tpl_vars['telephone']->value;?>
" pattern="^(?:0?)[1-9]([\s]\d\d){4}$" placeholder="ex: 00 00 00 00 00" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 >
		
				<div class="invalid-feedback">Veuillez saisir un telephone valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="email">Email:</label>
			<div class="col-sm-4">
				<input type="email" class="form-control form-control-danger" id="email" name="email" value="<?php echo $_smarty_tpl->tpl_vars['email']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
				<div class="invalid-feedback">Veuillez saisir un email valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="commentaire">Commentaire:</label>
			<div class="col-sm-10">
				<textarea class="form-control form-control-danger" id="commentaire=" name="commentaire" rows="1" maxlength="120"  <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
><?php echo $_smarty_tpl->tpl_vars['commentaire']->value;?>
</textarea>
				<div class="invalid-feedback">Veuillez saisir un commentaire valide</div>
			</div>
		</div>
		
		
		
		
		
		
		
		
		<div class="container-fluid">		
			<hr class="style-5 mt-1 bg-vert">
			<h2 class="text-left text-gris mb-3">Historique des réglements</h2>
		</div>
		
		
		<div class="form-group row">
			<div class="col-4"></div>

			<div class="col-4" id="tableau">
				<div class="row bg-gris text-white">
					<h5 class="col-4 text-left">&nbsp;Année</h5>
					<h5 class="col-4 text-center">Adhésion €</h5>
					<h5 class="col-4 text-center">Dons €</h5>
				</div>
				
				<?php $_smarty_tpl->_assignInScope('compteur', 0);?>
				
				<?php
$__section_liste_adhesions_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_adhesions']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_adhesions_1_total = $__section_liste_adhesions_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions'] = new Smarty_Variable(array());
if ($__section_liste_adhesions_1_total !== 0) {
for ($__section_liste_adhesions_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index'] = 0; $__section_liste_adhesions_1_iteration <= $__section_liste_adhesions_1_total; $__section_liste_adhesions_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index']++){
?>
				
					<?php if (($_smarty_tpl->tpl_vars['compteur']->value%2 == 0)) {?>
						<?php $_smarty_tpl->_assignInScope('couleur_fond', 'bg-light');?>
					<?php } else { ?>
						<?php $_smarty_tpl->_assignInScope('couleur_fond', 'bg-grey');?>
					<?php }?>
					
					
					
				
					<div id="ligne_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="ligne_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" class="row <?php echo $_smarty_tpl->tpl_vars['couleur_fond']->value;?>
">
						<div class="col-4 text-left">
							<label class="col-form-label" for="montant_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['liste_adhesions']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index'] : null)]['annee'];?>
</label>
						</div>
						<div class="col-4 text-center">
							<input type="hidden" id="id_adhesion_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="id_adhesion_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_adhesions']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index'] : null)]['id_adhesion'];?>
" >
							<input type="hidden" id="annee_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="annee_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_adhesions']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index'] : null)]['annee'];?>
" >

							<input type="number" class="form-control text-center" id="montant_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="montant_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_adhesions']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index'] : null)]['montant'];?>
" <?php echo $_smarty_tpl->tpl_vars['readonly_adhesion']->value;?>
>
						</div>
						<div class="col-4 text-center">
						
						<?php if ($_SESSION['id_utilisateur_groupe'] == 1) {?>
							<input type="number" class="form-control text-center" id="don_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="don_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_adhesions']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_adhesions']->value['index'] : null)]['don'];?>
" readonly>			
						<?php } else { ?>
							<input type="number" class="form-control text-center" id="don_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="don_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="" readonly>
						<?php }?>	
							
						</div>
					</div>
					<?php $_smarty_tpl->_assignInScope('compteur', ($_smarty_tpl->tpl_vars['compteur']->value+1));?>
					<?php if (($_smarty_tpl->tpl_vars['compteur']->value > 1)) {?>
						<?php $_smarty_tpl->_assignInScope('readonly_adhesion', "readonly");?>
					<?php }?>
				<?php
}
}
?>
			</div>
			<div class="col-4"></div>

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
	<?php echo '<script'; ?>
>
		$('#formulaire-clients').keypress(function(e){
			if( e.which == 13 ){e.preventDefault();}
		});
	<?php echo '</script'; ?>
>

</body>
</html><?php }
}
