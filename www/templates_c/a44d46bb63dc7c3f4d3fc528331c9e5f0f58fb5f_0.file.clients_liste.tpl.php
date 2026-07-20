<?php
/* Smarty version 3.1.32, created on 2019-04-03 13:17:01
  from 'C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\cfg\www\templates\clients_liste.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5ca4962d34aae3_87971299',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a44d46bb63dc7c3f4d3fc528331c9e5f0f58fb5f' => 
    array (
      0 => 'C:\\Program Files (x86)\\EasyPHP-Devserver-17\\eds-www\\cfg\\www\\templates\\clients_liste.tpl',
      1 => 1554193405,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5ca4962d34aae3_87971299 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\ProgramFiles(x86)\\EasyPHP-Devserver-17\\eds-www\\cfg\\cgi-bin\\smarty\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
$_smarty_tpl->_assignInScope('sens', 'down');?>

	<div class="container-fluid">

		<nav aria-label="Pagination">
			<div class="row">

				<div class="col-4">
					<form class="form-inline justify-content-left">
						<input class="form-control col-10 mr-2" id="recherche" type="text" placeholder="Recherche ...">
					</form>
				</div>

				<div class="col-8">
					<ul class="pagination pull-right">
					
						<?php if ($_smarty_tpl->tpl_vars['page_actuelle']->value == 1) {?>
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=1" aria-label="Previous">
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
									<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php } else { ?>
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php }?>
						<?php }
}
?>
					
						<?php if ($_smarty_tpl->tpl_vars['page_actuelle']->value == $_SESSION['nombre_de_pages']) {?>
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['nombre_de_pages']->value;?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=<?php echo $_SESSION['nombre_de_pages'];?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<?php }?>
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="clients_liste.php" method="post">
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
								 <option value='10000'>Tous les items</option>
							</select>
						</form>
					</div>
					
					
					<div class="pull-right mb-3">
						<?php if ($_SESSION['droit'] == 1) {?>
							<a href="clients_formulaire.php?action=ajouter" class="btn btn-success mr-3" role="button" aria-pressed="true"><i class="fas fa-plus-circle"></i></a>
						<?php }?>		
					</div>
					
				</div>
			</div>
			
		</nav>
		
	</div>

	
	
	
	
	<div>
	  
		<table class="table table-hover table-striped" id="tableau">
			<thead class="thead-gris text-white">
				<tr  class="row">
						
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
							<a href="clients_liste.php?colonne=<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['colonne'];?>
&sens_tri=<?php echo $_smarty_tpl->tpl_vars['sens_tri']->value;?>
" class="btn btn-<?php echo $_smarty_tpl->tpl_vars['bouton']->value;?>
 text-white" role="button" data-toggle="tooltip" data-placement="bottom" title="Trier" aria-pressed="true"><i class="fas fa-sort-alpha-<?php echo $_smarty_tpl->tpl_vars['sens']->value;?>
"></i></a>							
						<?php } else { ?>
							<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['colonne_titre_fr'];?>

							&nbsp;
							<a href="clients_liste.php?colonne=<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['colonne'];?>
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
					<td class="col-2 text-left">&nbsp;<?php echo $_smarty_tpl->tpl_vars['picto_etat']->value;?>
 &nbsp;<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['association'];?>
</td>
					<td class="col-2 text-left"><?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['nom'];?>
 <?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['prenom'];?>
</td>
					<td class="col-2 text-left"><?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['ville'];?>
</td>
					<td class="col-2 text-left"><?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['telephone'];?>
</td>
					<td class="col-2 text-left"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['date_modification'],"%d-%m-%Y");?>
</td>
					<td class="col-2 text-center">
						<a href="reservations_liste.php?id_admin_menu=1&id_client=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_client'];?>
" class="btn btn-info btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Réservations" aria-pressed="true"><i class="far fa-list-alt"></i></a>
						
						<a href="clients_formulaire.php?action=modifier&id_client=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_client'];?>
" class="btn btn-primary btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Modifier" aria-pressed="true"><i class="fas fa-edit"></i></a>
						
						<a href="fiche_client.php?action=imprimer&id_client=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_client'];?>
" class="btn btn-orange btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Imprimer" aria-pressed="true"><i class="fas fa-print"></i></a>
						
						<?php if ($_SESSION['id_utilisateur_groupe'] <= 2) {?>
							<a href="clients_formulaire.php?action=copier&id_client=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_client'];?>
" class="btn btn-secondary btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Copier" aria-pressed="true"><i class="fas fa-copy"></i></a>
							
								&nbsp;
								
								<a href="clients_liste.php?action=activer&id_client=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_client'];?>
" class="btn btn-success btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Activer" aria-pressed="true"><i class="fa fa-play" aria-hidden="true"></i></a>
							
								<a href="clients_liste.php?action=archiver&id_client=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_client'];?>
" class="btn btn-warning btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Archiver" aria-pressed="true"><i class="fa fa-pause" aria-hidden="true"></i></a>

							<?php if ($_SESSION['id_utilisateur_groupe'] <= 1) {?>

							<a href="clients_liste.php?action=supprimer&id_client=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_client'];?>
" class="btn btn-danger btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Supprimer" aria-pressed="true" onclick="return confirm('Confirmez-vous la suppression?');"><i class="fas fa-trash-alt"></i></a>
							
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
								<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=1" aria-label="Previous">
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
									<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php } else { ?>
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php }?>
						<?php }
}
?>
					
						<?php if ($_smarty_tpl->tpl_vars['page_actuelle']->value == $_SESSION['nombre_de_pages']) {?>
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=<?php echo $_SESSION['nombre_de_pages'];?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="clients_liste.php?page=<?php echo $_SESSION['nombre_de_pages'];?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<?php }?>
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="clients_liste.php" method="post">
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
					

				</div>
			</div>
			
		</nav>
		
	

	

</body>
</html><?php }
}
