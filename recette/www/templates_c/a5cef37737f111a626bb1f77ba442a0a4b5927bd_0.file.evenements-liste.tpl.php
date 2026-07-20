<?php
/* Smarty version 3.1.32, created on 2018-08-29 07:21:08
  from 'C:\wamp64\www\bootstrap\administrateur\templates\evenements-liste.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5b8649644dc7c3_34587998',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a5cef37737f111a626bb1f77ba442a0a4b5927bd' => 
    array (
      0 => 'C:\\wamp64\\www\\bootstrap\\administrateur\\templates\\evenements-liste.tpl',
      1 => 1535527267,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5b8649644dc7c3_34587998 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>



	
	

	
	
	
	<div class="container-fluid table-responsive">
		<h1 class="text-center text-gris mt-3 mb-5">Liste des événements</h1>

		<nav aria-label="Pagination">
		  <ul class="pagination justify-content-center">
			<li class="page-item ">
				<a class="page-link text-vert font-weight-bold bg-gris" href="#" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
					<span class="sr-only ">Previous</span>
				</a>
			</li>
			<li class="page-item disabled"><a class="page-link text-vert font-weight-bold bg-gris" href="#">1</a></li>
			<li class="page-item"><a class="page-link text-vert font-weight-bold bg-gris" href="#">2</a></li>
			<li class="page-item"><a class="page-link text-vert font-weight-bold bg-gris" href="#">3</a></li>
			<li class="page-item ">
				<a class="page-link text-vert font-weight-bold bg-gris" href="#" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
					<span class="sr-only">Next</span>
				</a>
			</li>
		  </ul>
		</nav>

		<input class="form-control mb-1" id="recherche" type="text" placeholder="Recherche ...">
	  
		<table class="table table-hover table-striped">
			<thead class="thead-gris text-white">
				<tr>
					<th class="col-1">Ordre</th>
					<th class="col-7">Désignation</th>
					<th class="col-1">Date</th>
					<th class="col-1">Valide du </th>
					<th class="col-1">Au</th>
					<th class="col-1">Actions</th>
				</tr>
			</thead>
			<tbody id="TableListe">
				
				<?php
$__section_liste_evenements_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_evenements']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_evenements_0_total = $__section_liste_evenements_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_evenements'] = new Smarty_Variable(array());
if ($__section_liste_evenements_0_total !== 0) {
for ($__section_liste_evenements_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index'] = 0; $__section_liste_evenements_0_iteration <= $__section_liste_evenements_0_total; $__section_liste_evenements_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index']++){
?>

				<tr>
					<td><?php echo $_smarty_tpl->tpl_vars['liste_evenements']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index'] : null)]['id_evenement'];?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['liste_evenements']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index'] : null)]['titre_fr'];?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['liste_evenements']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index'] : null)]['date_evenement'];?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['liste_evenements']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index'] : null)]['date_debut'];?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['liste_evenements']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index'] : null)]['date_fin'];?>
</td>
					<td>
						<a class="text-success" href="evenements-formulaire.php?action=modifier&id_evenement=<?php echo $_smarty_tpl->tpl_vars['liste_evenements']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index'] : null)]['id_evenement'];?>
" data-toggle="tooltip"  title="Modifier"><i class="fas fa-edit"></i></a>&nbsp;&nbsp;
						<a class="text-blue" href="evenements-formulaire.php?action=copier&id_evenement=<?php echo $_smarty_tpl->tpl_vars['liste_evenements']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index'] : null)]['id_evenement'];?>
" data-toggle="tooltip" title="Dupliquer"><i class="fas fa-copy"></i></a>&nbsp;&nbsp;
						<a class="text-danger" href="evenements-formulaire.php?action=supprimer&id_evenement=<?php echo $_smarty_tpl->tpl_vars['liste_evenements']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_evenements']->value['index'] : null)]['id_evenement'];?>
" data-toggle="tooltip" title="Supprimer"><i class="fas fa-trash-alt"></i></a>&nbsp;&nbsp; 
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
		<ul class="pagination justify-content-center">
			<li class="page-item ">
				<a class="page-link text-vert font-weight-bold bg-gris" href="#" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
					<span class="sr-only ">Previous</span>
				</a>
			</li>
			<li class="page-item disabled">
				<a class="page-link text-vert font-weight-bold bg-gris" href="#">1</a>
			</li>
			<li class="page-item">
				<a class="page-link text-vert font-weight-bold bg-gris" href="#">2</a>
			</li>
			<li class="page-item">
				<a class="page-link text-vert font-weight-bold bg-gris" href="#">3</a>
			</li>
			<li class="page-item ">
				<a class="page-link text-vert font-weight-bold bg-gris" href="#" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
					<span class="sr-only">Next</span>
				</a>
			</li>
		</ul>
	</nav>
	

</body>
</html><?php }
}
