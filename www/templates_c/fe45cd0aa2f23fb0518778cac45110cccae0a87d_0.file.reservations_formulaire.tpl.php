<?php
/* Smarty version 3.1.32, created on 2019-09-16 12:28:02
  from 'D:\Dropbox\EasyPHP-Devserver-17\eds-www\cfg\www\templates\reservations_formulaire.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5d7f63b21f3a42_72790523',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fe45cd0aa2f23fb0518778cac45110cccae0a87d' => 
    array (
      0 => 'D:\\Dropbox\\EasyPHP-Devserver-17\\eds-www\\cfg\\www\\templates\\reservations_formulaire.tpl',
      1 => 1568620289,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5d7f63b21f3a42_72790523 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php if ($_SESSION['id_utilisateur_groupe'] <= 2) {?>
	<?php $_smarty_tpl->_assignInScope('readonly', '');?>
	<?php $_smarty_tpl->_assignInScope('readonly_adhesion', '');
} else { ?>
	<?php $_smarty_tpl->_assignInScope('readonly', "readonly");?>
	<?php $_smarty_tpl->_assignInScope('readonly_adhesion', 'readonly');
}?>



	<form name="formulaire-reservations" id="formulaire-reservations" class="col-12 needs-validation mb-5" action="reservations_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
		<input type="hidden" id="id_reservation" name="id_reservation" value="<?php echo $_smarty_tpl->tpl_vars['id_reservation']->value;?>
">
		<input type="hidden" id="envoyer" name="envoyer" value="non">
		
		
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
			<label class="col-sm-2 col-form-label" for="id_client_association">Association:</label>
			<div class="col-sm-2">
				<?php if ($_smarty_tpl->tpl_vars['date_retour_temp']->value >= $_smarty_tpl->tpl_vars['aujourdhui_temp']->value) {?>	
					<select class="form-control form-control-danger" id="id_client_association" name="id_client_association" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 required>
						<?php if ($_smarty_tpl->tpl_vars['id_client']->value != '0') {?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['id_client']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['libelle_association']->value;?>
</option>
						<?php } else { ?>
							<option value="" selected></option>
						<?php }?>
						<?php
$__section_liste_associations_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_associations']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_associations_0_total = $__section_liste_associations_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_associations'] = new Smarty_Variable(array());
if ($__section_liste_associations_0_total !== 0) {
for ($__section_liste_associations_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_associations']->value['index'] = 0; $__section_liste_associations_0_iteration <= $__section_liste_associations_0_total; $__section_liste_associations_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_associations']->value['index']++){
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['liste_associations']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_associations']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_associations']->value['index'] : null)]['id_client'];?>
"><?php echo $_smarty_tpl->tpl_vars['liste_associations']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_associations']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_associations']->value['index'] : null)]['libelle'];?>
</option>
						<?php
}
}
?>
					</select>
					
				<?php } else { ?>
					<select class="form-control form-control-danger" id="id_client" name="id_client" readonly required>
						<option value="<?php echo $_smarty_tpl->tpl_vars['id_client']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['libelle']->value;?>
</option>
					</select>
				<?php }?>				
			</div>			

			<label class="col-sm-2 col-form-label text-right" for="id_client">Adhérent:</label>
			<div class="col-sm-2">
				<?php if ($_smarty_tpl->tpl_vars['date_retour_temp']->value >= $_smarty_tpl->tpl_vars['aujourdhui_temp']->value) {?>	
					<select class="form-control form-control-danger" id="id_client" name="id_client" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 required>
						<?php if ($_smarty_tpl->tpl_vars['id_client']->value != '0') {?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['id_client']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['libelle']->value;?>
</option>
						<?php } else { ?>
							<option value="" selected></option>
						<?php }?>
						<?php
$__section_liste_clients_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_clients']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_clients_1_total = $__section_liste_clients_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_clients'] = new Smarty_Variable(array());
if ($__section_liste_clients_1_total !== 0) {
for ($__section_liste_clients_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_clients']->value['index'] = 0; $__section_liste_clients_1_iteration <= $__section_liste_clients_1_total; $__section_liste_clients_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_clients']->value['index']++){
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['liste_clients']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_clients']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_clients']->value['index'] : null)]['id_client'];?>
"><?php echo $_smarty_tpl->tpl_vars['liste_clients']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_clients']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_clients']->value['index'] : null)]['libelle'];?>
</option>
						<?php
}
}
?>
					</select>
					
				<?php } else { ?>
					<select class="form-control form-control-danger" id="id_client" name="id_client" readonly required>
						<option value="<?php echo $_smarty_tpl->tpl_vars['id_client']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['libelle']->value;?>
</option>
					</select>
				<?php }?>				
			</div>			
			
			<label class="col-sm-2 col-form-label text-right" for="libelle_etat">Etat:</label>
			<div class="col-sm-2">
				<input type="hidden" id="id_etat" name="id_etat" value="<?php echo $_smarty_tpl->tpl_vars['id_etat']->value;?>
">
				<input type="text" class="form-control form-control-danger" id="libelle_etat" name="libelle_etat" value="<?php echo $_smarty_tpl->tpl_vars['libelle_etat']->value;?>
" readonly>
			</div>
			



		</div>






		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="don">Don:</label>
			<div class="col-sm-2">
				<input type="number" class="form-control form-control-danger" id="don" name="don" value="<?php echo $_smarty_tpl->tpl_vars['don']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
				<div class="invalid-feedback">Veuillez saisir un don valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_depart">Date de départ:</label>

		<?php if ($_smarty_tpl->tpl_vars['action']->value != 'ajouter-valider') {?>
			<div class="col-sm-2">
				<?php if ($_smarty_tpl->tpl_vars['date_retour_temp']->value >= $_smarty_tpl->tpl_vars['aujourdhui_temp']->value) {?>	
					<input type="text" class="form-control form-control-danger" id="date_depart" name="date_depart" value="<?php echo $_smarty_tpl->tpl_vars['date_depart']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 required>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			<?php } else { ?>
				<input type="text" class="form-control form-control-danger" id="date_depart" name="date_depart" value="<?php echo $_smarty_tpl->tpl_vars['date_depart']->value;?>
" readonly required>
			<?php }?>
			
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_retour">Date de retour:</label>
			<div class="col-sm-2">
				<?php if ($_smarty_tpl->tpl_vars['date_retour_temp']->value >= $_smarty_tpl->tpl_vars['aujourdhui_temp']->value) {?>	
					<input type="text" class="form-control form-control-danger" id="date_retour" name="date_retour" value="<?php echo $_smarty_tpl->tpl_vars['date_retour']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<?php } else { ?>
					<input type="text" class="form-control form-control-danger" id="date_retour" name="date_retour" value="<?php echo $_smarty_tpl->tpl_vars['date_retour']->value;?>
" readonly required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<?php }?>
			</div>
		<?php } else { ?>
			<div class="col-sm-2">
				<?php if ($_smarty_tpl->tpl_vars['date_retour_temp']->value >= $_smarty_tpl->tpl_vars['aujourdhui_temp']->value) {?>	
					<input type="text" class="form-control form-control-danger" id="date_depart" name="date_depart" value="<?php echo $_smarty_tpl->tpl_vars['date_depart']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<?php } else { ?>
					<input type="text" class="form-control form-control-danger" id="date_depart" name="date_depart" value="<?php echo $_smarty_tpl->tpl_vars['date_depart']->value;?>
" readonly required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<?php }?>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_retour">Date de retour:</label>
			<div class="col-sm-2">
				<?php if ($_smarty_tpl->tpl_vars['date_retour_temp']->value >= $_smarty_tpl->tpl_vars['aujourdhui_temp']->value) {?>	
					<input type="text" class="form-control form-control-danger" id="date_retour" name="date_retour" value="<?php echo $_smarty_tpl->tpl_vars['date_retour']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<?php } else { ?>
					<input type="text" class="form-control form-control-danger" id="date_retour" name="date_retour" value="<?php echo $_smarty_tpl->tpl_vars['date_retour']->value;?>
" $readonly required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<?php }?>
			</div>
		<?php }?>
		</div>


		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="commentaire">Commentaire:</label>
			<div class="col-sm-6">
				<textarea class="form-control form-control-danger" id="commentaire" name="commentaire" rows="1" maxlength="280" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
 placeholder="280 caractères max." ><?php echo $_smarty_tpl->tpl_vars['commentaire']->value;?>
</textarea>
				<div class="invalid-feedback">Veuillez saisir un commentaire valide</div>
			</div>
			
			<label class="col-sm-2 col-form-label text-right" for="id_statut_reservation">Statut:</label>
			<div class="col-sm-2">
				<?php if ($_smarty_tpl->tpl_vars['date_retour_temp']->value >= $_smarty_tpl->tpl_vars['aujourdhui_temp']->value) {?>	
					<select class="form-control form-control-danger" id="id_statut_reservation" name="id_statut_reservation" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
					<?php if ($_smarty_tpl->tpl_vars['id_statut_reservation']->value != '') {?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['id_statut_reservation']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['libelle_statut']->value;?>
</option>
					<?php }?>
					<?php
$__section_liste_statuts_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_statuts']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_statuts_2_total = $__section_liste_statuts_2_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_statuts'] = new Smarty_Variable(array());
if ($__section_liste_statuts_2_total !== 0) {
for ($__section_liste_statuts_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index'] = 0; $__section_liste_statuts_2_iteration <= $__section_liste_statuts_2_total; $__section_liste_statuts_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index']++){
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['liste_statuts']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index'] : null)]['id_statut_reservation'];?>
"><?php echo $_smarty_tpl->tpl_vars['liste_statuts']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index'] : null)]['libelle_statut'];?>
</option>
					<?php
}
}
?>
					</select>

				<?php } else { ?>	
					<select class="form-control form-control-danger" id="id_statut_reservation" name="id_statut_reservation" readonly>
						<option value="<?php echo $_smarty_tpl->tpl_vars['id_statut_reservation']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['libelle_statut']->value;?>
</option>
					</select>
				<?php }?>				
			</div>
			
			
		</div>
	






	<?php if ($_smarty_tpl->tpl_vars['action']->value != 'ajouter-valider') {?>
		

		<div class="container-fluid">		
			<hr class="style-5 bg-vert">
			<h2 class="text-left text-gris mb-3">Informations Adhérent</h2>
		</div>
	

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="association_client">Association:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="association_client" name="association_client" value="<?php echo $_smarty_tpl->tpl_vars['association_client']->value;?>
" readonly>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="nom_client">Nom:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="nom_client" name="nom_client" value="<?php echo $_smarty_tpl->tpl_vars['nom_client']->value;?>
" readonly>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="prenom_client">Prénom:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="prenom_client" name="prenom_client" value="<?php echo $_smarty_tpl->tpl_vars['prenom_client']->value;?>
" readonly>
			</div>		
		</div>


		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="adresse1_client">Adresse:</label>
			<div class="col-sm-6">
				<input type="text" class="form-control form-control-danger" id="adresse1_client" name="adresse1_client" value="<?php echo $_smarty_tpl->tpl_vars['adresse1_client']->value;?>
" readonly >
			</div>
		
			<label class="col-sm-2 col-form-label text-right" for="cp_client">CP:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="cp_client" name="cp_client" value="<?php echo $_smarty_tpl->tpl_vars['cp_client']->value;?>
" readonly>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="adresse2_client">&nbsp;</label>
			<div class="col-sm-6">
				<input type="text" class="form-control form-control-danger" id="adresse2_client" name="adresse2_client" value="<?php echo $_smarty_tpl->tpl_vars['adresse2_client']->value;?>
" readonly >		
			</div>	
			<label class="col-sm-2 col-form-label text-right" for="ville_client">Ville:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="ville_client" name="ville_client" value="<?php echo $_smarty_tpl->tpl_vars['ville_client']->value;?>
" readonly>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="adresse3_client">&nbsp;</label>
			<div class="col-sm-6">
				<input type="text" class="form-control form-control-danger" id="adresse3_client" name="adresse3_client" value="<?php echo $_smarty_tpl->tpl_vars['adresse3_client']->value;?>
" readonly >
			</div>	
			<label class="col-sm-2 col-form-label text-right" for="adhesion_client">Adhésion:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="adhesion_client" name="adhesion_client" value="<?php echo $_smarty_tpl->tpl_vars['adhesion_client']->value;?>
" readonly>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="telephone_client">Téléphone:</label>
			<div class="col-sm-4">
				<input type="tel" class="form-control form-control-danger" id="telephone_client" name="telephone_client" value="<?php echo $_smarty_tpl->tpl_vars['telephone_client']->value;?>
" readonly >
			</div>
			<label class="col-sm-2 col-form-label text-right" for="email_client">Email:</label>
			<div class="col-sm-4">
				<input type="email" class="form-control form-control-danger" id="email_client" name="email_client" value="<?php echo $_smarty_tpl->tpl_vars['email_client']->value;?>
" readonly>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="commentaire_client">Commentaire:</label>
			<div class="col-sm-10">
				<textarea class="form-control form-control-danger" id="commentaire_client" name="commentaire_client" rows="1" maxlength="280" placeholder="280 caractères max." readonly><?php echo $_smarty_tpl->tpl_vars['commentaire_client']->value;?>
</textarea>
				<div class="invalid-feedback">Veuillez saisir un commentaire valide</div>
			</div>
		</div>


		<hr class="style-5 bg-vert">

	
		<div class="form-group row">		
			<div class="col-9">
				<h2 class="text-left text-gris mb-3">Liste des articles</h2>
			</div>		
			<div class="col-3 text-right">
			
				<?php if ($_smarty_tpl->tpl_vars['action']->value == 'modifier-valider-rc') {?>
					<a href="reservations_formulaire.php?action=modifier&id_reservation=<?php echo $_smarty_tpl->tpl_vars['id_reservation']->value;?>
" class="btn btn-lg btn-secondary" role="button" data-toggle="tooltip" data-placement="bottom" title="Passer en réservation normale" aria-pressed="true"><b>RC</b></a>
				<?php } else { ?>			
					<a href="reservations_formulaire.php?action=modifier-rc&id_reservation=<?php echo $_smarty_tpl->tpl_vars['id_reservation']->value;?>
" class="btn btn-lg btn-vert" role="button" data-toggle="tooltip" data-placement="bottom" title="Activer la Réservation Classique" aria-pressed="true"><b>RC</b></a>
				<?php }?>
			
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="<?php echo $_SESSION['nom_table'];?>
_liste.php?id_admin_menu=<?php echo $_SESSION['id_admin_menu_selectionne'];?>
" class="btn btn-lg btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Annuler" aria-pressed="true"><i class="fas fa-times-circle fa-lg"></i></a>
					<?php if ($_SESSION['droit'] == 1) {?>
						&nbsp;&nbsp;&nbsp;
						<button id="btnSubmit" type="submit" class="btn btn-lg btn-vert"><i class="fas fa-check-circle fa-lg"></i></button>
						
						<?php if ($_smarty_tpl->tpl_vars['email_client']->value != '') {?>
							&nbsp;&nbsp;&nbsp;
							<a href="" class="btn btn-info btn-lg" role="button" data-toggle="tooltip" data-placement="bottom" title="11 Valider et Envoyer la confirmation de la réservation" aria-pressed="true" onclick="Envoyer();"><i class="fas fa-envelope"></i></a>

						<?php }?>
						
						
						
					<?php }?>
					

			</div>

			
		</div>
	
		<div class="form-group row">

			<div class="col-12" id="tableau">
				<div class="row bg-gris text-white">
					<h5 class="col-4 text-left">&nbsp;Article</h5>
					<h5 class="col-2 text-center">Qté Inventaire</h5>
					<h5 class="col-2 text-center">Qté Dispo Avant</h5>
					<h5 class="col-2 text-center">Qté Réservée</h5>
					<h5 class="col-2 text-center">Qté Dispo Après</h5>
				</div>
				
				<?php $_smarty_tpl->_assignInScope('compteur', 0);?>
				<?php $_smarty_tpl->_assignInScope('i', 0);?>
				<?php
$__section_liste_articles_3_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_articles']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_articles_3_total = $__section_liste_articles_3_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_articles'] = new Smarty_Variable(array());
if ($__section_liste_articles_3_total !== 0) {
for ($__section_liste_articles_3_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] = 0; $__section_liste_articles_3_iteration <= $__section_liste_articles_3_total; $__section_liste_articles_3_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']++){
?>
				
					<?php if ($_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['premier_article'] == 1) {?>
						<hr class="style-5 bg-vert">

					<?php }?>		
			
			
			
				<?php if ($_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['quantite_reservee'] > 0) {?>
					<?php if ($_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['quantite_reservee'] > $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['quantite_actuelle']) {?>	
						<?php $_smarty_tpl->_assignInScope('couleur_fond', 'bg-danger');?>
					<?php } else { ?>
						<?php $_smarty_tpl->_assignInScope('couleur_fond', 'bg-vert');?>
					<?php }?>
					
				<?php } elseif ($_smarty_tpl->tpl_vars['i']->value == 0) {?>
					<?php $_smarty_tpl->_assignInScope('couleur_fond', 'bg-light');?>
					<?php $_smarty_tpl->_assignInScope('i', 1);?>
				<?php } else { ?>
					<?php $_smarty_tpl->_assignInScope('couleur_fond', 'bg-grey');?>
					<?php $_smarty_tpl->_assignInScope('i', 0);?>
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['id_etat_article'] == '1') {?>
					<?php $_smarty_tpl->_assignInScope('picto_etat', '<i class="fa fa-circle fa-xs text-success" aria-hidden="true"></i>');?>
				<?php } elseif ($_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['id_etat_article'] == '2') {?>	
					<?php $_smarty_tpl->_assignInScope('picto_etat', '<i class="fa fa-circle fa-xs text-warning" aria-hidden="true"></i>');?>
				<?php } else { ?>
					<?php $_smarty_tpl->_assignInScope('picto_etat', '<i class="fa fa-circle fa-xs text-danger" aria-hidden="true"></i>');?>
				<?php }?>

				
	
				

			
			
							
				
					<div id="ligne_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="ligne_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" class="row <?php echo $_smarty_tpl->tpl_vars['couleur_fond']->value;?>
">
						<div class="col-4 text-left">
							<label class="col-form-label" for="qtetotale_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
">&nbsp;<?php echo $_smarty_tpl->tpl_vars['picto_etat']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['designation'];?>
</label>
							
							<?php if ($_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['nom_fichier'] != '') {?>	
								<a href="fichiers/<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['nom_fichier'];?>
.<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['extension'];?>
" target="_blank" class="btn text-dark mr-3" role="button" aria-hidden="true"><i class="fa fa-file-image-o" ></i></a>
							<?php }?>
							
						</div>
						<div class="col-2 text-center">
							<input type="hidden" id="id_article_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="id_article_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['id_article'];?>
" >

						<?php if ($_smarty_tpl->tpl_vars['date_retour_temp']->value >= $_smarty_tpl->tpl_vars['aujourdhui_temp']->value) {?>	
							<input type="number" class="form-control text-center" id="qtetotale_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="qtetotale_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['quantite_totale'];?>
" readonly>
						<?php } else { ?>
							<input type="number" class="form-control text-center" id="qtetotale_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="qtetotale_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="" readonly>
						<?php }?>

						
						</div>
						<div class="col-2 text-center">
						<?php if ($_smarty_tpl->tpl_vars['date_retour_temp']->value >= $_smarty_tpl->tpl_vars['aujourdhui_temp']->value) {?>	
							<input type="number" class="form-control text-center" id="qtedispoavant_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="qtedispoavant_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['quantite_actuelle'];?>
" readonly>
						<?php } else { ?>
							<input type="number" class="form-control text-center" id="qtedispoavant_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="qtedispoavant_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="" readonly>
						<?php }?>

						</div>
						<div class="col-2 text-center">
						<?php if ($_smarty_tpl->tpl_vars['date_retour_temp']->value >= $_smarty_tpl->tpl_vars['aujourdhui_temp']->value) {?>	
							<input type="number" class="form-control text-center" id="qtereservee_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="qtereservee_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['quantite_reservee'];?>
" onChange="Soustraction(<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
);" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
						<?php } else { ?>
							<input type="number" class="form-control text-center" id="qtereservee_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="qtereservee_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['quantite_reservee'];?>
" onChange="Soustraction(<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
);" readonly>
						<?php }?>
						</div>
						<div class="col-2 text-center">
						<?php if ($_smarty_tpl->tpl_vars['date_retour_temp']->value >= $_smarty_tpl->tpl_vars['aujourdhui_temp']->value) {?>	
							<input type="number" class="form-control text-center" id="resultat_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="resultat_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['quantite_apres'];?>
" readonly>
						<?php } else { ?>
							<input type="number" class="form-control text-center" id="resultat_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="resultat_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="" readonly>
						<?php }?>
						</div>
					</div>
					<?php $_smarty_tpl->_assignInScope('compteur', ($_smarty_tpl->tpl_vars['compteur']->value+1));?>
				<?php
}
}
?>
				

		<?php }?>
				<?php echo '<script'; ?>
>
				
					function Envoyer()
					{
						$('#envoyer').val('oui');	
							
						$('#formulaire-reservations').submit().delay(3000);

						alert('Envoie un email.');
						//$("#formulaire-reservations").submit();						
					}

					
				
				
				
					function Soustraction(numero) 
					{ 
						var dispoavant = parseInt(document.getElementById("qtedispoavant_"+numero).value); 
						var reserve = parseInt(document.getElementById("qtereservee_"+numero).value); 
					
						if(dispoavant == reserve)
						{
							document.getElementById("resultat_"+numero).value = '0';
						}
						else if(reserve<0)
						{
							alert("Vous ne pouvez réserver une valeur < 0");
							document.getElementById("qtereservee_"+numero).value = 0;
							document.getElementById("resultat_"+numero).value = '0';
						}
						else if(dispoavant != "" && reserve != "" && dispoavant>=reserve) 
						{ 		   
						   var resultat_ = parseFloat(dispoavant) - parseFloat(reserve);
						   document.getElementById("resultat_"+numero).value = resultat_.toFixed(2);
						} 
						else if(dispoavant<reserve)
						{
							document.getElementById("qtereservee_"+numero).value = dispoavant;
							document.getElementById("resultat_"+numero).value = '0';
							alert("Vous ne pouvez réserver plus que le stock Dispo Avant");
						}
						else 
						{
						   document.getElementById("resultat_"+numero).value = "0";
						}
						
						if(document.getElementById("qtereservee_"+numero).value !="" && document.getElementById("qtereservee_"+numero).value>0)
						{
							document.getElementById("ligne_"+numero).className = "row bg-vert text-white";
						}
						else if(document.getElementById("qtereservee_"+numero).value !="" && document.getElementById("qtereservee_"+numero).value==0)
						{
							if(numero/2 == Math.round(numero/2)) 
							{
								document.getElementById("ligne_"+numero).className = "row bg-light";
							}
							else 
							{
								document.getElementById("ligne_"+numero).className = "row bg-grey";
							}
							   
						}
							
					}
					
					
					var aujourdhui = new Date();
					var datemin = new Date();
					datemin.setDate(aujourdhui.getDate()-5);	
					var datemax = new Date();
					datemax.setDate(aujourdhui.getDate()+365);		
					$('#date_depart').datepicker({ 
						uiLibrary: 'bootstrap4', 
						iconsLibrary: 'fontawesome', 
						modal: true, 
						header: true, 
						footer: true,
						locale: 'fr-fr',
						format: 'dd-mm-yyyy',
						weekStartDay: 1,
						disableDaysOfWeek: [0, 2, 3, 4, 6],
						minDate: datemin,
						maxDate: datemax,				
						keyboardNavigation: true,
					});		
					$('#date_retour').datepicker({ 
						uiLibrary: 'bootstrap4', 
						iconsLibrary: 'fontawesome', 
						modal: true, 
						header: true, 
						footer: true,
						locale: 'fr-fr',
						format: 'dd-mm-yyyy',
						weekStartDay: 1,
						disableDaysOfWeek: [0, 2, 3, 4, 6],
						minDate: datemin,
						maxDate: (datemax),				
						keyboardNavigation: true,
					});		


				<?php echo '</script'; ?>
>
			</div>			
			
		</div>
		
		
		<div class="form-group row mr-1 float-right">
			<a href="<?php echo $_SESSION['nom_table'];?>
_liste.php?id_admin_menu=<?php echo $_SESSION['id_admin_menu_selectionne'];?>
" class="btn btn-lg btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Annuler" aria-pressed="true"><i class="fas fa-times-circle fa-lg"></i></a>
			<?php if ($_SESSION['droit'] == 1) {?>
				&nbsp;&nbsp;&nbsp;
				<button id="btnSubmit" type="submit" class="btn btn-lg btn-vert"><i class="fas fa-check-circle fa-lg"></i></button>
				
				<?php if ($_smarty_tpl->tpl_vars['email_client']->value != '') {?>
					&nbsp;&nbsp;&nbsp;
							
					<a href="" class="btn btn-info btn-lg" role="button" data-toggle="tooltip" data-placement="bottom" title="33 Valider et Envoyer la confirmation de la réservation" aria-pressed="true" onclick="Envoyer();"><i class="fas fa-envelope"></i></a>


				<?php }?>
			<?php }?>
		</div>		


		<div class="container-fluid">		
			<hr class="style-5 mt-5 bg-vert">
			<div class="text-left text-gris">
				<b>Qté Totale :</b> stock au dernier inventaire <br />
				<b>Qté Disponible Avant :</b> quantité totale - (toutes les réservations sorties jusqu'à la veille et dont le retour est au moins au lendemain) <br />
				<b>Qté Réservée :</b> quantité à réserver pour ce client <br />
				<b>Qté Disponible Après :</b> quantité totale - (toutes les réservations sorties jusqu'à la veille et dont le retour est au moins au lendemain) - (Qté réservée) <br /><br />
			</div>
		</div>

		
	</form> 		
	<?php echo '<script'; ?>
>
		$('#formulaire-reservations').keypress(function(e){
			if( e.which == 13 ){e.preventDefault();}
		});
	<?php echo '</script'; ?>
>

</body>
</html><?php }
}
