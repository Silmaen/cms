<?php
/* Smarty version 3.1.32, created on 2019-09-16 13:42:13
  from 'D:\Dropbox\EasyPHP-Devserver-17\eds-www\cfg\www\templates\reservations_liste.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5d7f7515a9afa6_80119937',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '64a2a00837f05ca8f82485fb05172caefc9e60c2' => 
    array (
      0 => 'D:\\Dropbox\\EasyPHP-Devserver-17\\eds-www\\cfg\\www\\templates\\reservations_liste.tpl',
      1 => 1568634129,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5d7f7515a9afa6_80119937 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'D:\\Dropbox\\EasyPHP-Devserver-17\\eds-www\\cfg\\cgi-bin\\smarty\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
$_smarty_tpl->_assignInScope('sens', 'down');?>
	<div class="container-fluid">

		<nav aria-label="Pagination">
			
		
			<div class="row">
				<div class="col-7">
				
					<form class="form-inline justify-content-left">
						<div class="form-group col-5">
							<input class="form-control col-12" id="recherche" type="text" placeholder="Recherche ...">
						</div>

						<?php if ($_SESSION['id_utilisateur_groupe'] <= 2) {?>
						
							<div class="col-2 text-left">
								<input class="form-control form-check-input" type="radio" value="1" id="filtre_reservations_1" name="filtre_reservations" <?php if ($_SESSION['filtre_statut'] == 1) {?> checked <?php }?>onchange='this.form.submit();'>
								<label class="form-control form-check-label text-secondary text-left" for="filtre_reservations">Actives</label>
							</div>
							<div class="col-2 text-left">
								<input class="form-control form-check-input" type="radio" value="3" id="filtre_reservations_3" name="filtre_reservations" <?php echo $_SESSION['filtre_statut'];?>
 <?php if ($_SESSION['filtre_statut'] == 3) {?> checked <?php }?> onchange='this.form.submit();'>
								<label class="form-control form-check-label text-secondary text-left" for="filtre_reservations">Toutes</label>
							</div>
							<div class="col-2 text-left">
								<input class="form-control form-check-input" type="radio" value="4" id="filtre_reservations_4" name="filtre_reservations" <?php echo $_SESSION['filtre_statut'];?>
 <?php if ($_SESSION['filtre_statut'] == 4) {?> checked <?php }?> onchange='this.form.submit();'>
								<label class="form-control form-check-label text-secondary text-left text" for="filtre_reservations">A valider</label>
							</div>
							<div class="col-1">
								&nbsp;
							</div>
						<?php }?>
						
					</form>
				</div>

			

				<div class="col-5">
					<ul class="pagination pull-right">
					
						<?php if ($_smarty_tpl->tpl_vars['page_actuelle']->value == 1) {?>
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php }?>
						
						<?php
$_smarty_tpl->tpl_vars['foo'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['foo']->step = 1;$_smarty_tpl->tpl_vars['foo']->total = (int) ceil(($_smarty_tpl->tpl_vars['foo']->step > 0 ? $_SESSION['nombre_de_pages']+1 - (1) : 1-($_SESSION['nombre_de_pages'])+1)/abs($_smarty_tpl->tpl_vars['foo']->step));
if ($_smarty_tpl->tpl_vars['foo']->total > 0) {
for ($_smarty_tpl->tpl_vars['foo']->value = 1, $_smarty_tpl->tpl_vars['foo']->iteration = 1;$_smarty_tpl->tpl_vars['foo']->iteration <= $_smarty_tpl->tpl_vars['foo']->total;$_smarty_tpl->tpl_vars['foo']->value += $_smarty_tpl->tpl_vars['foo']->step, $_smarty_tpl->tpl_vars['foo']->iteration++) {
$_smarty_tpl->tpl_vars['foo']->first = $_smarty_tpl->tpl_vars['foo']->iteration === 1;$_smarty_tpl->tpl_vars['foo']->last = $_smarty_tpl->tpl_vars['foo']->iteration === $_smarty_tpl->tpl_vars['foo']->total;?>
							<?php if ($_smarty_tpl->tpl_vars['foo']->value == $_smarty_tpl->tpl_vars['page_actuelle']->value) {?>
								<li class="page-item disabled">
									<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php } else { ?>
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php }?>
						<?php }
}
?>
					
						<?php if ($_smarty_tpl->tpl_vars['page_actuelle']->value == $_SESSION['nombre_de_pages']) {?>
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['nombre_de_pages']->value;?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<?php echo $_SESSION['nombre_de_pages'];?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<?php }?>
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="reservations_liste.php" method="post">
							<select class="form-control" id='items_par_page' name='items_par_page' onchange='if(this.value != 0) { this.form.submit(); }'>
								 <option value='<?php echo $_SESSION['items_par_page'];?>
' selected>
									<?php if ($_SESSION['items_par_page'] == 10000) {?>
										Tous les items
									<?php } else { ?>
										<?php echo $_SESSION['items_par_page'];?>
 par page
									<?php }?>
								 </option>
								 <option value='100'>100 par page</option>
								 <option value='200'>200 par page</option>
								 <option value='300'>300 par page</option>
								 <option value='10000'>Tous</option>
							</select>
						</form>
					</div>
					
					
					<div class="pull-right mb-3">
						<?php if ($_SESSION['droit'] == 1) {?>
							<a href="reservations_formulaire.php?action=ajouter" class="btn btn-success mr-3" role="button" aria-pressed="true"><i class="fas fa-plus-circle"></i></a>
						<?php }?>		
					</div>
				</div>				
			</div>			
		</nav>
	<?php if ($_smarty_tpl->tpl_vars['designation_article_selectionne']->value != '') {?>
		<div class="row bg-light">
			<div class="col-12">
				Liste des réservations pour l'article : <b><?php echo $_smarty_tpl->tpl_vars['designation_article_selectionne']->value;?>
</b>
			</div>
		</div>
	<?php }?>
		
	</div>


	
	<div>
		<table class="table table-hover table-striped " id="tableau">
			<thead class="thead-gris text-white">
				<tr class="row">
				
				
					<?php
$__section_liste_page_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_page']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_page_0_total = $__section_liste_page_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_page'] = new Smarty_Variable(array());
if ($__section_liste_page_0_total !== 0) {
for ($__section_liste_page_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] = 0; $__section_liste_page_0_iteration <= $__section_liste_page_0_total; $__section_liste_page_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']++){
?>		
					
					<th class="col-<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['largeur'];?>
 text-left align-text-bottom">
						<?php if ($_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['ordre'] == 1) {?>
							&nbsp;
						<?php }?>

						<?php if ($_SESSION['colonne'] == $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['colonne']) {?>
							<?php $_smarty_tpl->_assignInScope('bouton', 'info');?>
							<?php if ($_SESSION['sens_tri'] == 'DESC') {?>
								<?php $_smarty_tpl->_assignInScope('sens', 'up');?>
								<?php $_smarty_tpl->_assignInScope('sens_tri', 'ASC');?>
							<?php } else { ?>
								<?php $_smarty_tpl->_assignInScope('sens', 'down');?>
								<?php $_smarty_tpl->_assignInScope('sens_tri', 'DESC');?>
							<?php }?>
							<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['colonne_titre_fr'];?>

							&nbsp;
							<a href="reservations_liste.php?colonne=<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['colonne'];?>
&sens_tri=<?php echo $_smarty_tpl->tpl_vars['sens_tri']->value;?>
" class="btn btn-<?php echo $_smarty_tpl->tpl_vars['bouton']->value;?>
 text-white" role="button" data-toggle="tooltip" data-placement="bottom" title="Trier" aria-pressed="true"><i class="fas fa-sort-alpha-<?php echo $_smarty_tpl->tpl_vars['sens']->value;?>
"></i></a>							
						<?php } else { ?>
							<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['colonne_titre_fr'];?>

							&nbsp;
							<a href="reservations_liste.php?colonne=<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['colonne'];?>
&sens_tri=ASC" class="btn btn-link text-white" role="button" data-toggle="tooltip" data-placement="bottom" title="Trier" aria-pressed="true"><i class="fas fa-sort-alpha-down"></i></a>
						<?php }?>											
						
					</th>
										
					<?php
}
}
?>
					
					
					<th class="col-2 text-center align-text-bottom">Actions</th>
				</tr>
			</thead>
			<tbody id="TableListe">
				
				<?php
$__section_liste_items_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_items']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_items_1_total = $__section_liste_items_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_items'] = new Smarty_Variable(array());
if ($__section_liste_items_1_total !== 0) {
for ($__section_liste_items_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] = 0; $__section_liste_items_1_iteration <= $__section_liste_items_1_total; $__section_liste_items_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']++){
?>

				<?php if ($_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_etat'] == '1') {?>
					<?php $_smarty_tpl->_assignInScope('picto_etat', '<i class="fa fa-circle fa-xs text-success" aria-hidden="true"></i>');?>
				<?php } elseif ($_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_etat'] == '2') {?>	
					<?php $_smarty_tpl->_assignInScope('picto_etat', '<i class="fa fa-circle fa-xs text-warning" aria-hidden="true"></i>');?>
				<?php } else { ?>
					<?php $_smarty_tpl->_assignInScope('picto_etat', '<i class="fa fa-circle fa-xs text-danger" aria-hidden="true"></i>');?>
				<?php }?>

				
				
				<tr class="row">
					<td class="col-3 text-left">
						&nbsp;<?php echo $_smarty_tpl->tpl_vars['picto_etat']->value;?>
 
						&nbsp; <?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['quantite_reservee'];?>
 
						&nbsp; <?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['association'];?>

					</td>
					<td class="col-3 text-left"><?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['nom'];?>
 <?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['prenom'];?>
</td>
					<td class="col-2 text-left"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['date_depart'],"%d-%m-%Y");?>
</td>
					<td class="col-2 text-left"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['date_retour'],"%d-%m-%Y");?>
</td>
					<td class="col-2 text-center">
							<a href="reservations_formulaire.php?action=modifier&id_reservation=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_reservation'];?>
" class="btn btn-primary btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Modifier" aria-pressed="true"><i class="fas fa-edit"></i></a>
						
						<?php if ($_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['email'] != '') {?>
							<a href="reservation_confirmation.php?action=envoyer&id_reservation=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_reservation'];?>
" class="btn btn-info btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Envoyer la confirmation de la réservation" aria-pressed="true"><i class="fas fa-envelope"></i></a>
						<?php }?>
							<a href="fiche_reservation.php?action=imprimer&id_reservation=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_reservation'];?>
" class="btn btn-orange btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Imprimer une réservation" aria-pressed="true"><i class="fas fa-print"></i></a>

						<?php if ($_SESSION['id_utilisateur_groupe'] <= 2) {?>
							<a href="reservations_formulaire.php?action=copier&id_reservation=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_reservation'];?>
" class="btn btn-secondary btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Copier" aria-pressed="true"><i class="fas fa-copy"></i></a>
							
							&nbsp;
							
							<a href="reservations_liste.php?action=activer&id_reservation=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_reservation'];?>
" class="btn btn-success btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Activer" aria-pressed="true"><i class="fa fa-play" aria-hidden="true"></i></a>
							
							<a href="reservations_liste.php?action=archiver&id_reservation=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_reservation'];?>
" class="btn btn-warning btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Archiver" aria-pressed="true"><i class="fa fa-archive" aria-hidden="true"></i></a>
							
							<?php if ($_SESSION['id_utilisateur_groupe'] <= 2) {?>
							
							<button class="confirmer<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_reservation'];?>
" >ICI</button>
							
						
						
						<?php echo '<script'; ?>
>	
						$('.confirmer<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_reservation'];?>
').on('click', function(){
								$.confirm({
									title: 'Suppression',
									content: 'Choisissez une option',
									buttons: {
										confirm: function(){
											window.location.href = 'reservations_liste.php?action=supprimer&id_reservation=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_reservation'];?>
';
										},
										cancel: function()
										{},
										somethingElse: {
											text: 'Email',
											action: function(){
												this.$content // reference to the content
												window.location.href = 'reservations_liste.php?action=supprimer-envoyer&id_reservation=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_reservation'];?>
';
											}
										}
									}
								});
							});
						<?php echo '</script'; ?>
>
							
							<a href="" class="btn btn-danger btn-sm checked" role="button" data-toggle="tooltip" data-placement="bottom" title="Supprimer" aria-pressed="true" onclick="Confirmer();"><i class="fas fa-trash-alt"></i></a>
							
							<?php }?>
							
						<?php }?>
					</td>
				</tr>
				<?php
}
}
?>
			</tbody>
		</table>
	</div>

		<nav aria-label="Pagination">
			<div class="row">	
				
				<div class="col-12">
					<ul class="pagination pull-right">
					
						<?php if ($_smarty_tpl->tpl_vars['page_actuelle']->value == 1) {?>
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php }?>
						
						<?php
$_smarty_tpl->tpl_vars['foo'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['foo']->step = 1;$_smarty_tpl->tpl_vars['foo']->total = (int) ceil(($_smarty_tpl->tpl_vars['foo']->step > 0 ? $_SESSION['nombre_de_pages']+1 - (1) : 1-($_SESSION['nombre_de_pages'])+1)/abs($_smarty_tpl->tpl_vars['foo']->step));
if ($_smarty_tpl->tpl_vars['foo']->total > 0) {
for ($_smarty_tpl->tpl_vars['foo']->value = 1, $_smarty_tpl->tpl_vars['foo']->iteration = 1;$_smarty_tpl->tpl_vars['foo']->iteration <= $_smarty_tpl->tpl_vars['foo']->total;$_smarty_tpl->tpl_vars['foo']->value += $_smarty_tpl->tpl_vars['foo']->step, $_smarty_tpl->tpl_vars['foo']->iteration++) {
$_smarty_tpl->tpl_vars['foo']->first = $_smarty_tpl->tpl_vars['foo']->iteration === 1;$_smarty_tpl->tpl_vars['foo']->last = $_smarty_tpl->tpl_vars['foo']->iteration === $_smarty_tpl->tpl_vars['foo']->total;?>
							<?php if ($_smarty_tpl->tpl_vars['foo']->value == $_smarty_tpl->tpl_vars['page_actuelle']->value) {?>
								<li class="page-item disabled">
									<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php } else { ?>
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php }?>
						<?php }
}
?>
					
						<?php if ($_smarty_tpl->tpl_vars['page_actuelle']->value == $_SESSION['nombre_de_pages']) {?>
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<?php echo $_SESSION['nombre_de_pages'];?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<?php echo $_SESSION['nombre_de_pages'];?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<?php }?>
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="reservations_liste.php" method="post">
							<select class="form-control" name='items_par_page' onchange='if(this.value != 0) { this.form.submit(); }'>
								 <option value='<?php echo $_SESSION['items_par_page'];?>
' selected>
									<?php if ($_SESSION['items_par_page'] == 10000) {?>
										Tous les items
									<?php } else { ?>
										<?php echo $_SESSION['items_par_page'];?>
 par page
									<?php }?>
								 </option>
								 <option value='100'>100 par page</option>
								 <option value='200'>200 par page</option>
								 <option value='300'>300 par page</option>
								 <option value='10000'>Tous les items</option>
							</select>
						</form>
					</div>
					

				


					<div id="dialog-confirm" title="Suppression d'une réservation">
					  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Etes-vous sûr de vouloir confirmer cette réservation?</p>
					</div>


				</div>
			</div>
			
		</nav>

</body>
</html><?php }
}
