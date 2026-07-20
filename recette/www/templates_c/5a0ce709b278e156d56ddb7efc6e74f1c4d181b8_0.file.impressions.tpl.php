<?php
/* Smarty version 3.1.32, created on 2025-09-22 09:39:11
  from '/home/cdfgenaytb/www/templates/impressions.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_68d0fd1fb07060_31475155',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5a0ce709b278e156d56ddb7efc6e74f1c4d181b8' => 
    array (
      0 => '/home/cdfgenaytb/www/templates/impressions.tpl',
      1 => 1758526750,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_68d0fd1fb07060_31475155 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<div class="container-fluid">

		<div class="row">
			<div class="col-5 border border-vert bg-light h-50 pt-3">
				<form class="col-12 needs-validation" action="impressions_reservations_jour.php" method="POST">
					<input type="hidden" id="action" name="action" value="imprimer">			
					<div class="row col-12 form-group">			
						<div class="col-1">&nbsp;</div>
						<label class="col-5 col-form-label" for="date_depart_reservation"><b>Réservations au départ le</b></label>			
						<div class="col-4">
							<input type="text" class="form-control form-control-danger" id="date_depart_reservation" name="date_depart_reservation" value="<?php echo $_smarty_tpl->tpl_vars['aujourdhui']->value;?>
" onkeydown="return false" >
						</div>				
						<div class="col-1">							
							<button id="btnSubmit" type="submit" class="btn btn-vert"><i class="fas fa-print fa-lg"></i></button>
						</div>
						<div class="col-1">&nbsp;</div>
					</div>
	
					<?php echo '<script'; ?>
>
						$('#date_depart_reservation').datepicker({ 
								uiLibrary: 'bootstrap4', 
								iconsLibrary: 'fontawesome', 
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
			
			<div class="col-5 border border-vert bg-light h-50 pt-3">
				<?php if ($_SESSION['id_utilisateur_groupe'] <= 1) {?>
						

					<form class="col-12 needs-validation" action="impressions_reglements_recus.php" method="POST">
						<input type="hidden" id="action" name="action" value="imprimer">			
						<div class="row col-12 form-group">			
							
							
							<label class="col-4 col-form-label" for="date_debut_reglements_synthese"><b>Dons perçus entre le</b></label>			
							<div class="col-3 text-center bg-light">
								<input type="text" class="form-control form-control-danger" id="date_debut_reglements_synthese" name="date_debut_reglements_synthese" value="<?php echo $_smarty_tpl->tpl_vars['aujourdhui']->value;?>
" onkeydown="return false" >
							</div>				

							<label class="col-1 col-form-label" for="date_fin_reglements_synthese"><b>et le</b></label>			
							<div class="col-3 text-center bg-light">
								<input type="text" class="form-control form-control-danger" id="date_fin_reglements_synthese" name="date_fin_reglements_synthese" value="<?php echo $_smarty_tpl->tpl_vars['aujourdhui']->value;?>
" onkeydown="return false" >
							</div>				


							<div class="col-1 text-right bg-light">							
								<button id="btnSubmit" type="submit" class="btn btn-vert"><i class="fas fa-print fa-lg"></i></button>
							</div>				
						</div>			
						<?php echo '<script'; ?>
>
							$('#date_debut_reglements_synthese').datepicker({ 
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

							$('#date_fin_reglements_synthese').datepicker({ 
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
				
				<?php }?>
			</div>

		</div>
		
		<div class="row">&nbsp;</div>
		
		
		<div class="row">
			<div class="col-5 border border-vert bg-light h-50 pt-3">
				<form class="col-12 needs-validation" action="impressions_reservations_retour.php" method="POST">
					<input type="hidden" id="action" name="action" value="imprimer">			
					<div class="row col-12 form-group">			
						<div class="col-1">&nbsp;</div>
						<label class="col-5 col-form-label" for="date_retour_reservation"><b>Dons au retour le</b></label>			
						<div class="col-4">
							<input type="text" class="form-control form-control-danger" id="date_retour_reservation" name="date_retour_reservation" value="<?php echo $_smarty_tpl->tpl_vars['aujourdhui']->value;?>
" onkeydown="return false" >
						</div>				
						<div class="col-1">							
							<button id="btnSubmit" type="submit" class="btn btn-vert"><i class="fas fa-print fa-lg"></i></button>
						</div>
						<div class="col-1">&nbsp;</div>
					</div>
					<?php echo '<script'; ?>
>
						$('#date_retour_reservation').datepicker({ 
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
			
			<div class="col-5 border border-vert bg-light h-50 pt-3">
				<?php if ($_SESSION['id_utilisateur_groupe'] <= 1) {?>

					<form class="col-12 needs-validation" action="impressions_reglements_detail.php" method="POST">
						<input type="hidden" id="action" name="action" value="imprimer">			
						<div class="row col-12 form-group">			
	

							<label class="col-4 col-form-label" for="date_debut_reglements_detail"><b>Détail des dons des retours entre le </b></label>			
							<div class="col-3 text-center bg-light">
								<input type="text" class="form-control form-control-danger" id="date_debut_reglements_detail" name="date_debut_reglements_detail" value="<?php echo $_smarty_tpl->tpl_vars['aujourdhui']->value;?>
" onkeydown="return false" >
							</div>				

							<label class="col-1 col-form-label" for="date_fin_reglements_detail"><b>et le</b></label>			
							<div class="col-3 text-center bg-light">
								<input type="text" class="form-control form-control-danger" id="date_fin_reglements_detail" name="date_fin_reglements_detail" value="<?php echo $_smarty_tpl->tpl_vars['aujourdhui']->value;?>
" onkeydown="return false" >
							</div>				


							<div class="col-1 text-right bg-light">							
								<button id="btnSubmit" type="submit" class="btn btn-vert"><i class="fas fa-print fa-lg"></i></button>
							</div>				
						</div>			
						<?php echo '<script'; ?>
>
							$('#date_debut_reglements_detail').datepicker({ 
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

							$('#date_fin_reglements_detail').datepicker({ 
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
				
				<?php }?>
					
			</div>			
			
		</div>		
		
		
		
		
		
		<div class="row">&nbsp;</div>	
		
		
		
		
		
		
		<div class="row">
			<div class="col-5 border border-vert bg-light h-50 pt-3">
				<form class="col-12 needs-validation" action="impressions_articles_jour.php" method="POST">
					<input type="hidden" id="action" name="action" value="imprimer">			
					<div class="row col-12 form-group">			
						<div class="col-1">&nbsp;</div>
						<label class="col-5 col-form-label" for="date_depart_article"><b>Articles au départ le</b></label>			
						<div class="col-4">
							<input type="text" class="form-control form-control-danger" id="date_depart_article" name="date_depart_article" value="<?php echo $_smarty_tpl->tpl_vars['aujourdhui']->value;?>
" onkeydown="return false" >
						</div>				
						<div class="col-1">							
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
			
			<div class="col-2">&nbsp;</div>
			
			<div class="col-5 border border-vert bg-light h-50 pt-3">
				<?php if ($_SESSION['id_utilisateur_groupe'] <= 1) {?>
						
					<form class="col-12 needs-validation" action="impressions_reservations_sans_adhesions.php" method="POST">
						<input type="hidden" id="action" name="action" value="imprimer">			
						<div class="row col-12 form-group">			

							<label class="col-8 col-form-label" for="annee_fiscale_reference_adhesion"><b>Réservations SANS ADHESION sur l'année fiscale de référence</b></label>			
							
							<div class="col-3 text-center bg-light">
								<select class="form-control form-control-danger" id="annee_fiscale_reference_adhesion" name="annee_fiscale_reference_adhesion">
									<option value="2026">2026-2027</option>
									<option value="2025">2025-2026</option>
									<option value="2024" selected>2024-2025</option>
									<option value="2023">2023-2024</option>
									<option value="2022">2022-2023</option>
									<option value="2021">2021-2022</option>
									<option value="2020">2020-2021</option>
									<option value="2019">2019-2020</option>
									<option value="2018">2018-2019</option>
								</select>
							</div>				

							<div class="col-1 text-right bg-light">							
								<button id="btnSubmit" type="submit" class="btn btn-vert"><i class="fas fa-print fa-lg"></i></button>
							</div>				
						</div>			
			
					</form>
				
				<?php }?>						
			</div>			
			
		</div>			
		

		<div class="row">&nbsp;</div>	


		<div class="row">
			<div class="col-5">
				&nbsp;
			</div>	
			
			<div class="col-2">&nbsp;</div>
			
			<div class="col-5 border border-vert bg-light h-50 pt-3">
				<?php if ($_SESSION['id_utilisateur_groupe'] <= 1) {?>
						
					<form class="col-12 needs-validation" action="impressions_reservations_sans_dons.php" method="POST">
						<input type="hidden" id="action" name="action" value="imprimer">			
						<div class="row col-12 form-group">			

							<label class="col-8 col-form-label" for="annee_fiscale_reference_dons"><b>Réservations SANS DONS sur l'année fiscale de référence</b></label>			
							
							<div class="col-3 text-center bg-light">
								<select class="form-control form-control-danger" id="annee_fiscale_reference_dons" name="annee_fiscale_reference_dons">
									<option value="2026">2026-2027</option>
									<option value="2025">2025-2026</option>
									<option value="2024" selected>2024-2025</option>
									<option value="2023">2023-2024</option>
									<option value="2022">2022-2023</option>
									<option value="2021">2021-2022</option>
									<option value="2020">2020-2021</option>
									<option value="2019">2019-2020</option>
									<option value="2018">2018-2019</option>
								</select>
							</div>				

							<div class="col-1 text-right bg-light">							
								<button id="btnSubmit" type="submit" class="btn btn-vert"><i class="fas fa-print fa-lg"></i></button>
							</div>				
						</div>			
			
					</form>
				
				<?php }?>						
			</div>			



			
		</div>	


		<div class="row">&nbsp;</div>	


		<div class="row">
			<div class="col-5">
				&nbsp;
			</div>	
			
			<div class="col-2">&nbsp;</div>
			
			<div class="col-5 border border-vert bg-light h-50 pt-3">
				<?php if ($_SESSION['id_utilisateur_groupe'] <= 1) {?>
						
					<form class="col-12 needs-validation" action="impressions_adhesions_exercice.php" method="POST">
						<input type="hidden" id="action" name="action" value="imprimer">			
						<div class="row col-12 form-group">			

							<label class="col-8 col-form-label" for="annee_fiscale_reference_adhesion"><b>Adhésions affectées à l'exercice</b></label>			
							
							<div class="col-3 text-center bg-light">
								<select class="form-control form-control-danger" id="annee_fiscale_reference_adhesion" name="annee_fiscale_reference_adhesion">
									<option value="2026">2026-2027</option>
									<option value="2025">2025-2026</option>
									<option value="2024" selected>2024-2025</option>
									<option value="2023">2023-2024</option>
									<option value="2022">2022-2023</option>
									<option value="2021">2021-2022</option>
									<option value="2020">2020-2021</option>
									<option value="2019">2019-2020</option>
									<option value="2018">2018-2019</option>
								</select>
							</div>				

							<div class="col-1 text-right bg-light">							
								<button id="btnSubmit" type="submit" class="btn btn-vert"><i class="fas fa-print fa-lg"></i></button>
							</div>				
						</div>			

					</form>
				
				<?php }?>						
			</div>			
			
		</div>	
		
		
		
	</div>

</body>
</html><?php }
}
