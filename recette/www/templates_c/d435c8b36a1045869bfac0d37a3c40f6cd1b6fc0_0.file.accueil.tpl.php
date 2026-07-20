<?php
/* Smarty version 3.1.32, created on 2018-10-01 06:37:36
  from 'C:\wamp64\www\bootstrap\administrateur\templates\accueil.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5bb1c0b0198c97_72068684',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd435c8b36a1045869bfac0d37a3c40f6cd1b6fc0' => 
    array (
      0 => 'C:\\wamp64\\www\\bootstrap\\administrateur\\templates\\accueil.tpl',
      1 => 1538375853,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5bb1c0b0198c97_72068684 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


	
	<div class="container-fluid">
		<?php echo $_SESSION['messages_par_page'];?>
<br />
		<?php echo $_SESSION['id_admin_menu_selectionne'];?>

	
		<div class="col-12 text-center">
			<h1 class="d-inline text-gris  align-top">Bienvenu(e) à votre CMS</h1>
		</div>	

		<div id="chart_div" style="width:400px; height:250px"></div>
		<?php echo '<script'; ?>
 type="text/javascript">

			// Load the Visualization API and the corechart package.
			google.charts.load('current', {'packages':['corechart']});
			google.charts.load('current', {'packages':['corechart'], 'language': 'fr'});

			// Set a callback to run when the Google Visualization API is loaded.
			google.charts.setOnLoadCallback(drawChart);

			// Callback that creates and populates a data table,
			// instantiates the pie chart, passes in the data and
			// draws it.
			function drawChart() {

			// Create the data table.
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Topping');
			data.addColumn('number', 'Slices');
			data.addRows([
				<?php
$__section_liste_tables_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_tables']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_tables_0_total = $__section_liste_tables_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_tables'] = new Smarty_Variable(array());
if ($__section_liste_tables_0_total !== 0) {
for ($__section_liste_tables_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_tables']->value['index'] = 0; $__section_liste_tables_0_iteration <= $__section_liste_tables_0_total; $__section_liste_tables_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_tables']->value['index']++){
$_smarty_tpl->tpl_vars['__smarty_section_liste_tables']->value['last'] = ($__section_liste_tables_0_iteration === $__section_liste_tables_0_total);
?>
					['<?php echo $_smarty_tpl->tpl_vars['liste_tables']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_tables']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_tables']->value['index'] : null)]['nom_table'];?>
', <?php echo $_smarty_tpl->tpl_vars['liste_tables']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_tables']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_tables']->value['index'] : null)]['nombre_items'];?>
]
					<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_liste_tables']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_tables']->value['last'] : null)) {
} else { ?>,<?php }?>
				<?php
}
}
?>		
			]);

			// Set chart options
			var options = {'title':'Répartition des saisies',
							'is3D':true};

			// Instantiate and draw our chart, passing in some options.
			var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
			chart.draw(data, options);
			}
		<?php echo '</script'; ?>
> 

	</div>

</body>
</html><?php }
}
