<?php
/* Smarty version 3.1.32, created on 2018-09-28 12:52:22
  from 'C:\wamp64\www\bootstrap\administrateur\templates\textes_liste.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5bae2406823969_29354225',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fcfec2b7c6d21d358796fa0519101a1ffb75a34c' => 
    array (
      0 => 'C:\\wamp64\\www\\bootstrap\\administrateur\\templates\\textes_liste.tpl',
      1 => 1538139136,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5bae2406823969_29354225 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

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
								<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php }?>
						
						<?php
$_smarty_tpl->tpl_vars['foo'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['foo']->step = 1;$_smarty_tpl->tpl_vars['foo']->total = (int) ceil(($_smarty_tpl->tpl_vars['foo']->step > 0 ? $_smarty_tpl->tpl_vars['nombre_de_pages']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['nombre_de_pages']->value)+1)/abs($_smarty_tpl->tpl_vars['foo']->step));
if ($_smarty_tpl->tpl_vars['foo']->total > 0) {
for ($_smarty_tpl->tpl_vars['foo']->value = 1, $_smarty_tpl->tpl_vars['foo']->iteration = 1;$_smarty_tpl->tpl_vars['foo']->iteration <= $_smarty_tpl->tpl_vars['foo']->total;$_smarty_tpl->tpl_vars['foo']->value += $_smarty_tpl->tpl_vars['foo']->step, $_smarty_tpl->tpl_vars['foo']->iteration++) {
$_smarty_tpl->tpl_vars['foo']->first = $_smarty_tpl->tpl_vars['foo']->iteration === 1;$_smarty_tpl->tpl_vars['foo']->last = $_smarty_tpl->tpl_vars['foo']->iteration === $_smarty_tpl->tpl_vars['foo']->total;?>
							<?php if ($_smarty_tpl->tpl_vars['foo']->value == $_smarty_tpl->tpl_vars['page_actuelle']->value) {?>
								<li class="page-item disabled">
									<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php } else { ?>
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php }?>
						<?php }
}
?>
					
						<?php if ($_smarty_tpl->tpl_vars['page_actuelle']->value == $_smarty_tpl->tpl_vars['nombre_de_pages']->value) {?>
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['nombre_de_pages']->value;?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['nombre_de_pages']->value;?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<?php }?>
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="textes_liste.php" method="post">
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
						<a href="textes_formulaire.php?action=ajouter" class="btn btn-success mr-3" role="button" aria-pressed="true"><i class="fas fa-plus-circle"></i></a>	 
					</div>
					
				</div>
			</div>
			
		</nav>
		
	</div>

	
	
	
	
	<div>
	  
		<table class="table table-hover table-striped" id="tableau">
			<thead class="thead-gris text-white">
				<tr  class="col-12">
					<th class="col-1 text-center">Ordre</th>
					<th class="col-10 text-left">Désignation</th>
					<th class="col-1 text-center">Actions</th>
				</tr>
			</thead>
			<tbody id="TableListe">
				
				<?php
$__section_liste_textes_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_textes']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_textes_0_total = $__section_liste_textes_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_textes'] = new Smarty_Variable(array());
if ($__section_liste_textes_0_total !== 0) {
for ($__section_liste_textes_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index'] = 0; $__section_liste_textes_0_iteration <= $__section_liste_textes_0_total; $__section_liste_textes_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index']++){
?>

				<tr class="col-12">
					<td class="col-1 text-center"><?php echo $_smarty_tpl->tpl_vars['liste_textes']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index'] : null)]['ordre'];?>
</td>
					<td class="col-10 text-left"><?php echo $_smarty_tpl->tpl_vars['liste_textes']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index'] : null)]['titre_fr'];?>
</td>
					<td class="col-1 text-right">
						<a href="textes_formulaire.php?action=modifier&id_texte=<?php echo $_smarty_tpl->tpl_vars['liste_textes']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index'] : null)]['id_texte'];?>
" class="btn btn-success" role="button" data-toggle="tooltip" data-placement="bottom" title="Modifier" aria-pressed="true"><i class="fas fa-edit"></i></a>
						
						<a href="textes_formulaire.php?action=copier&id_texte=<?php echo $_smarty_tpl->tpl_vars['liste_textes']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index'] : null)]['id_texte'];?>
" class="btn btn-primary" role="button" data-toggle="tooltip" data-placement="bottom" title="Copier" aria-pressed="true"><i class="fas fa-copy"></i></a>
						
						<a href="textes_liste.php?action=supprimer&id_texte=<?php echo $_smarty_tpl->tpl_vars['liste_textes']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_textes']->value['index'] : null)]['id_texte'];?>
" class="btn btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Supprimer" aria-pressed="true" onclick="return confirm('Confirmez-vous la suppression?');"><i class="fas fa-trash-alt"></i></a>
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
								<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<?php }?>
						
						<?php
$_smarty_tpl->tpl_vars['foo'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['foo']->step = 1;$_smarty_tpl->tpl_vars['foo']->total = (int) ceil(($_smarty_tpl->tpl_vars['foo']->step > 0 ? $_smarty_tpl->tpl_vars['nombre_de_pages']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['nombre_de_pages']->value)+1)/abs($_smarty_tpl->tpl_vars['foo']->step));
if ($_smarty_tpl->tpl_vars['foo']->total > 0) {
for ($_smarty_tpl->tpl_vars['foo']->value = 1, $_smarty_tpl->tpl_vars['foo']->iteration = 1;$_smarty_tpl->tpl_vars['foo']->iteration <= $_smarty_tpl->tpl_vars['foo']->total;$_smarty_tpl->tpl_vars['foo']->value += $_smarty_tpl->tpl_vars['foo']->step, $_smarty_tpl->tpl_vars['foo']->iteration++) {
$_smarty_tpl->tpl_vars['foo']->first = $_smarty_tpl->tpl_vars['foo']->iteration === 1;$_smarty_tpl->tpl_vars['foo']->last = $_smarty_tpl->tpl_vars['foo']->iteration === $_smarty_tpl->tpl_vars['foo']->total;?>
							<?php if ($_smarty_tpl->tpl_vars['foo']->value == $_smarty_tpl->tpl_vars['page_actuelle']->value) {?>
								<li class="page-item disabled">
									<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php } else { ?>
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
								</li>
							<?php }?>
						<?php }
}
?>
					
						<?php if ($_smarty_tpl->tpl_vars['page_actuelle']->value == $_smarty_tpl->tpl_vars['nombre_de_pages']->value) {?>
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['nombre_de_pages']->value;?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<?php } else { ?>
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="textes_liste.php?page=<?php echo $_smarty_tpl->tpl_vars['nombre_de_pages']->value;?>
" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<?php }?>
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="textes_liste.php" method="post">
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
