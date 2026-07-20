<?php
/* Smarty version 3.1.32, created on 2023-06-14 08:00:38
  from '/home/cdfgenaytb/www/templates/inventaires_formulaire.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_64895786df3d69_23834423',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3b9952f66cc514d4ad9214ee7b5c51a6f4662eb1' => 
    array (
      0 => '/home/cdfgenaytb/www/templates/inventaires_formulaire.tpl',
      1 => 1686676211,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_64895786df3d69_23834423 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>



<?php if ($_SESSION['id_utilisateur_groupe'] <= 1) {?>
	<?php $_smarty_tpl->_assignInScope('readonly', '');
} else { ?>
	<?php $_smarty_tpl->_assignInScope('readonly', "readonly");
}?>


	<form name="formulaire-inventaires" id="formulaire-inventaires" class="col-12 needs-validation mb-5" action="inventaires_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
		<input type="hidden" id="id_inventaire" name="id_inventaire" value="<?php echo $_smarty_tpl->tpl_vars['id_inventaire']->value;?>
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
			<label class="col-sm-2 col-form-label text-left" for="libelle_etat">Etat:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="libelle_etat" name="libelle_etat" value="<?php echo $_smarty_tpl->tpl_vars['libelle_etat']->value;?>
" readonly>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_inventaire">Date d'inventaire:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_inventaire" name="date_inventaire" value="<?php echo $_smarty_tpl->tpl_vars['date_inventaire']->value;?>
" onkeydown="return false" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="id_statut_inventaire">Statut:</label>
			<div class="col-sm-2">
				<select class="form-control form-control-danger" id="id_statut_inventaire" name="id_statut_inventaire" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
					
					<?php if ($_smarty_tpl->tpl_vars['id_statut_inventaire']->value != '') {?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['id_statut_inventaire']->value;?>
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
						<option value="<?php echo $_smarty_tpl->tpl_vars['liste_statuts']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index'] : null)]['id_statut_inventaire'];?>
"><?php echo $_smarty_tpl->tpl_vars['liste_statuts']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_statuts']->value['index'] : null)]['libelle_statut'];?>
</option>
					<?php
}
}
?>
				</select>
			</div>

		</div>
	
		<div class="form-group row">
			<label class="col-sm-2 col-form-label text-left" for="id_type_inventaire">Type:</label>
			<div class="col-sm-2">
				<select class="form-control form-control-danger" id="id_type_inventaire" name="id_type_inventaire" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
					
					<?php if ($_smarty_tpl->tpl_vars['id_type_inventaire']->value != '') {?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['id_type_inventaire']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['libelle_type']->value;?>
</option>
					<?php }?>
					<?php
$__section_liste_types_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_types']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_types_1_total = $__section_liste_types_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_types'] = new Smarty_Variable(array());
if ($__section_liste_types_1_total !== 0) {
for ($__section_liste_types_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_types']->value['index'] = 0; $__section_liste_types_1_iteration <= $__section_liste_types_1_total; $__section_liste_types_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_types']->value['index']++){
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['liste_types']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_types']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_types']->value['index'] : null)]['id_type_inventaire'];?>
"><?php echo $_smarty_tpl->tpl_vars['liste_types']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_types']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_types']->value['index'] : null)]['libelle_type'];?>
</option>
					<?php
}
}
?>
				</select>
			</div>
		
			<label class="col-sm-2 col-form-label text-right" for="commentaire">Commentaire:</label>
			<div class="col-sm-6">
				<textarea class="form-control form-control-danger" id="commentaire" name="commentaire" rows="2" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
><?php echo $_smarty_tpl->tpl_vars['commentaire']->value;?>
</textarea>
				<div class="invalid-feedback">Veuillez saisir un commentaire valide</div>
			</div>
		</div>
	
		<div class="form-group row">		
			<hr class="style-5 mt-5 bg-vert">
			<div class="col-9">
				<h2 class="text-left text-gris mb-3">Liste des articles</h2>
			</div>		
			<div class="col-3 text-right">					
				<a href="<?php echo $_SESSION['nom_table'];?>
_liste.php?id_admin_menu=<?php echo $_SESSION['id_admin_menu_selectionne'];?>
" class="btn btn-lg btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Annuler" aria-pressed="true"><i class="fas fa-times-circle fa-lg"></i></a>
				<?php if ($_SESSION['droit'] == 1) {?>
					&nbsp;&nbsp;&nbsp;
					<button id="btnSubmit" type="submit" class="btn btn-lg btn-vert"><i class="fas fa-check-circle fa-lg"></i></button>
				<?php }?>
			</div>		
		</div>		
		
		<div class="form-group row">			
			<div class="col-12 text-gris">
				<b>Qté Précédente :</b> stock au dernier inventaire <br />
				<b>Qté Actuelle :</b> stock lors de cet inventaire <br /><br />
			</div>
		</div>	
	
		<div class="form-group row">
			<div class="col-1"></div>

			<div class="col-10" id="tableau">
				<div class="row bg-dark text-white">
					<h5 class="col-3 text-left">&nbsp;Article</h5>
					<h5 class="col-2 text-center">Qté Précédente</h5>
					<h5 class="col-2 text-center">Qté Actuelle</h5>
					<h5 class="col-2 text-center">Ecart</h5>
					<h5 class="col-3 text-center">Commentaire</h5>					
				</div>
				
				<?php $_smarty_tpl->_assignInScope('compteur', 0);?>
				<?php $_smarty_tpl->_assignInScope('i', 0);?>
				<?php
$__section_liste_articles_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_articles']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_articles_2_total = $__section_liste_articles_2_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_articles'] = new Smarty_Variable(array());
if ($__section_liste_articles_2_total !== 0) {
for ($__section_liste_articles_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] = 0; $__section_liste_articles_2_iteration <= $__section_liste_articles_2_total; $__section_liste_articles_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']++){
?>		

				<?php if ($_smarty_tpl->tpl_vars['i']->value == 0) {?>
					<?php $_smarty_tpl->_assignInScope('couleur_fond', 'bg-light');?>
					<?php $_smarty_tpl->_assignInScope('i', 1);?>
				<?php } else { ?>
					<?php $_smarty_tpl->_assignInScope('couleur_fond', 'bg-grey');?>
					<?php $_smarty_tpl->_assignInScope('i', 0);?>
				<?php }?>
				
				
				
					<div id="ligne_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="ligne_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" class="row <?php echo $_smarty_tpl->tpl_vars['couleur_fond']->value;?>
">
						<div class="col-3 text-left">
							<label class="col-form-label" for="qtetotale_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['designation'];?>
</label>
						</div>
						<div class="col-2 text-center">
							<input type="hidden" id="id_article_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="id_article_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['id_article'];?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>

							<input type="number" class="form-control text-center" id="qteprecedent_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="qteprecedent_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['quantite_precedent'];?>
" readonly>
						</div>
						<div class="col-2 text-center">
							<input type="number" class="form-control text-center" id="qtetotale_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="qtetotale_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['quantite_totale'];?>
" onChange="Ecart(<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
);" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
						</div>
						<div class="col-2 text-center">
							<input type="number" class="form-control text-center" id="resultat_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="resultat_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['ecart'];?>
" readonly>
						</div>
						<div class="col-3 text-left">
							<input type="text" class="form-control text-center" id="commentaire_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" name="commentaire_<?php echo $_smarty_tpl->tpl_vars['compteur']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['liste_articles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_articles']->value['index'] : null)]['commentaire'];?>
" <?php echo $_smarty_tpl->tpl_vars['readonly']->value;?>
>
						</div>
						
						
					</div>
					<?php $_smarty_tpl->_assignInScope('compteur', ($_smarty_tpl->tpl_vars['compteur']->value+1));?>
				<?php
}
}
?>
				

				<?php echo '<script'; ?>
>
					function Ecart(numero) 
					{ 
						var dispoavant = parseInt(document.getElementById("qteprecedent_"+numero).value); 
						var stock = parseInt(document.getElementById("qtetotale_"+numero).value); 
					
						if(dispoavant == stock)
						{
							document.getElementById("resultat_"+numero).value = 0;
						}
						else if(stock<0)
						{
							alert("Vous ne pouvez pas avoir un stock < 0");
							document.getElementById("qtetotale_"+numero).value = 0;
							document.getElementById("resultat_"+numero).value = 0;
						}
						else 
						{ 		   
						   var resultat_ = parseFloat(stock) - parseFloat(dispoavant);
						   document.getElementById("resultat_"+numero).value = resultat_.toFixed(2);
						} 
											
					}
								
					$('#date_inventaire').datepicker({ 
							uiLibrary: 'bootstrap4', 
							iconsLibrary: 'fontawesome', 
							modal: true, 
							header: true, 
							footer: true,
							locale: 'fr-fr',
							format: 'dd-mm-yyyy',
							weekStartDay: 1,
							minDate: '<?php echo $_smarty_tpl->tpl_vars['date_dernier_inventaire']->value;?>
',
							keyboardNavigation: true
					});									
										
				<?php echo '</script'; ?>
>
			</div>
			<div class="col-1"></div>
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
		$('#formulaire-inventaires').keypress(function(e){
			if( e.which == 13 ){e.preventDefault();}
		});
	<?php echo '</script'; ?>
>


</body>
</html><?php }
}
