<?php
/* Smarty version 3.1.32, created on 2019-04-02 10:53:32
  from 'C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\cfg\fr\templates\impressions.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5ca3230c652234_11764864',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5fabe672734e63cec9fa0e70c36dae00dd3705ee' => 
    array (
      0 => 'C:\\Program Files (x86)\\EasyPHP-Devserver-17\\eds-www\\cfg\\fr\\templates\\impressions.tpl',
      1 => 1554195208,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5ca3230c652234_11764864 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-5 text-center border border-vert bg-light h-50 pt-3">
				<form class="row text-center needs-validation" action="impressions_reservations_jour.php" method="POST">
					<input type="hidden" id="action" name="action" value="imprimer">			
					<div class="form-group row">			
						<label class="col-6 col-form-label" for="date_depart"><b>Réservations au départ le :</b></label>			
						<div class="col-4 text-center bg-light">
							<input type="text" class="form-control form-control-danger" id="date_depart_reservation" name="date_depart_reservation" value="<?php echo $_smarty_tpl->tpl_vars['aujourdhui']->value;?>
" onkeydown="return false" >
						</div>				
						<div class="col-1 text-right bg-light">							
							<button id="btnSubmit" type="submit" class="btn btn-vert"><i class="fas fa-print fa-lg"></i></button>
						</div>
						<div class="col-1">&nbsp;</div>							
					</div>			
					<?php echo '<script'; ?>
>
						$('#date_depart_reservation').datepicker({ 
								uiLibrary: 'bootstrap4', 
								iconsLibrary: 'fontawesome', 
								modal: true, 
								header: true, 
								footer: true,
								locale: 'fr-fr',
								format: 'dd-mm-yyyy',
								weekStartDay: 1,
								keyboardNavigation: true
						});												
					<?php echo '</script'; ?>
>				
				</form>
			</div>	
			
			<div class="col-2">&nbsp;</div>
			
			<div class="col-5 text-center border border-vert bg-light h-50 pt-3">
				<form class="row needs-validation" action="impressions_articles_jour.php" method="POST">
					<input type="hidden" id="action" name="action" value="imprimer">			
					<div class="form-group row">			
						<label class="col-6 col-form-label" for="date_depart_article"><b>Articles au départ le :</b></label>			
						<div class="col-4 text-center  bg-light">
							<input type="text" class="form-control form-control-danger" id="date_depart_article" name="date_depart_article" value="<?php echo $_smarty_tpl->tpl_vars['aujourdhui']->value;?>
" onkeydown="return false" >
						</div>				
						<div class="col-1 text-right  bg-light">							
							<button id="btnSubmit" type="submit" class="btn btn-vert"><i class="fas fa-print fa-lg"></i></button>
						</div>				
						<div class="col-1">&nbsp;</div>							
					</div>			
					<?php echo '<script'; ?>
>
						$('#date_depart_article').datepicker({ 
								uiLibrary: 'bootstrap4', 
								iconsLibrary: 'fontawesome', 
								modal: true, 
								header: true, 
								footer: true,
								locale: 'fr-fr',
								format: 'dd-mm-yyyy',
								weekStartDay: 1,
								keyboardNavigation: true
						});												
					<?php echo '</script'; ?>
>				
				</form>
			</div>

		</div>
	</div>

</body>
</html><?php }
}
