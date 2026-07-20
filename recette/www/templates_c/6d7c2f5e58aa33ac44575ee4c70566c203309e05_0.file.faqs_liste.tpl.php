<?php
/* Smarty version 3.1.32, created on 2019-02-19 11:46:31
  from 'C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\iscriptura2019\administrateur\templates\faqs_liste.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5c6bde8728b124_09082161',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6d7c2f5e58aa33ac44575ee4c70566c203309e05' => 
    array (
      0 => 'C:\\Program Files (x86)\\EasyPHP-Devserver-17\\eds-www\\iscriptura2019\\administrateur\\templates\\faqs_liste.tpl',
      1 => 1550572647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5c6bde8728b124_09082161 (Smarty_Internal_Template $_smarty_tpl) {
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
								<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=1" aria-label="Previous">
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
									<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php } else { ?>
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php }?>
						<?php }
}
?>
					
						<?php if ($_smarty_tpl->tpl_vars['page_actuelle']->value == $_SESSION['nombre_de_pages']) {?>
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=<?php echo $_SESSION['nombre_de_pages'];?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=<?php echo $_SESSION['nombre_de_pages'];?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<?php }?>
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="faqs_liste.php" method="post">
							<select class="form-control" id='messages_par_page' name='messages_par_page' onchange='if(this.value != 0) { this.form.submit(); }'>
								 <option value='<?php echo $_SESSION['messages_par_page'];?>
' selected>
									<?php if ($_SESSION['messages_par_page'] == 10000) {?>
										Tous les items
									<?php } else { ?>
										<?php echo $_SESSION['messages_par_page'];?>
 items par page
									<?php }?>
								 </option>
								 <option value='10'>10 items par page</option>
								 <option value='20'>20 items par page</option>
								 <option value='30'>30 items par page</option>
								 <option value='10000'>Tous les items</option>
							</select>
						</form>
					</div>
					
					
					<div class="pull-right mb-3">
						<?php if ($_SESSION['droit'] == 1) {?>
							<a href="<?php echo $_SESSION['nom_table'];?>
_formulaire.php?action=ajouter" class="btn btn-success mr-3" role="button" aria-pressed="true"><i class="fas fa-plus-circle"></i></a>
						<?php }?>		
					</div>
					
				</div>
			</div>
			
		</nav>
		
	</div>

	
	
	
	
	<div>
	  
		<table class="table table-hover table-striped" id="tableau">
			<thead class="thead-gris text-white">
				<tr  class="col-12">
					
					<?php
$__section_liste_page_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_page']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_page_0_total = $__section_liste_page_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_page'] = new Smarty_Variable(array());
if ($__section_liste_page_0_total !== 0) {
for ($__section_liste_page_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] = 0; $__section_liste_page_0_iteration <= $__section_liste_page_0_total; $__section_liste_page_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']++){
?>		
					
					<th class="col-<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['largeur'];?>
 text-left">
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
							<a href="faqs_liste.php?colonne=<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['colonne'];?>
&sens_tri=<?php echo $_smarty_tpl->tpl_vars['sens_tri']->value;?>
" class="btn btn-<?php echo $_smarty_tpl->tpl_vars['bouton']->value;?>
 text-white" role="button" data-toggle="tooltip" data-placement="bottom" title="Trier" aria-pressed="true"><i class="fas fa-sort-alpha-<?php echo $_smarty_tpl->tpl_vars['sens']->value;?>
"></i></a>							
						<?php } else { ?>
							<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['colonne_titre_fr'];?>

							&nbsp;
							<a href="faqs_liste.php?colonne=<?php echo $_smarty_tpl->tpl_vars['liste_page']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_page']->value['index'] : null)]['colonne'];?>
&sens_tri=ASC" class="btn btn-link text-white" role="button" data-toggle="tooltip" data-placement="bottom" title="Trier" aria-pressed="true"><i class="fas fa-sort-alpha-down"></i></a>
						<?php }?>											
						
					</th>
										
					<?php
}
}
?>
					
					
					
					
					<th class="col-1 text-center">Actions</th>
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

				<tr class="col-12">
					<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['ordre'];?>
</td>
					<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['question_fr'];?>
</td>
					<td class="text-center">
						<a href="faqs_formulaire.php?action=modifier&id_faq=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_faq'];?>
" class="btn btn-sm btn-success" role="button" data-toggle="tooltip" data-placement="bottom" title="Modifier" aria-pressed="true"><i class="fas fa-edit"></i></a>
						
						<?php if ($_SESSION['droit'] == 1) {?>
							<a href="faqs_formulaire.php?action=copier&id_faq=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_faq'];?>
" class="btn btn-sm btn-primary" role="button" data-toggle="tooltip" data-placement="bottom" title="Copier" aria-pressed="true"><i class="fas fa-copy"></i></a>
							
							<a href="faqs_liste.php?action=supprimer&id_faq=<?php echo $_smarty_tpl->tpl_vars['liste_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items']->value['index'] : null)]['id_faq'];?>
" class="btn btn-sm btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Supprimer" aria-pressed="true" onclick="return confirm('Confirmez-vous la suppression?');"><i class="fas fa-trash-alt"></i></a>
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
								<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=1" aria-label="Previous">
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
									<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php } else { ?>
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php }?>
						<?php }
}
?>
					
						<?php if ($_smarty_tpl->tpl_vars['page_actuelle']->value == $_SESSION['nombre_de_pages']) {?>
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=<?php echo $_SESSION['nombre_de_pages'];?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="faqs_liste.php?page=<?php echo $_SESSION['nombre_de_pages'];?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<?php }?>
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="faqs_liste.php" method="post">
							<select class="form-control" name='messages_par_page' onchange='if(this.value != 0) { this.form.submit(); }'>
								 <option value='<?php echo $_SESSION['messages_par_page'];?>
' selected>
									<?php if ($_SESSION['messages_par_page'] == 10000) {?>
										Tous les items
									<?php } else { ?>
										<?php echo $_SESSION['messages_par_page'];?>
 items par page
									<?php }?>
								 </option>
								 <option value='10'>10 items par page</option>
								 <option value='20'>20 items par page</option>
								 <option value='30'>30 items par page</option>
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
