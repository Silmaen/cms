<?php
/* Smarty version 3.1.32, created on 2018-09-27 09:11:16
  from 'C:\wamp64\www\bootstrap\administrateur\templates\evenements_formulaire.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5bac9eb42b5e57_33375152',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '97565f7000c797b9d06ce69485f5892e3f238624' => 
    array (
      0 => 'C:\\wamp64\\www\\bootstrap\\administrateur\\templates\\evenements_formulaire.tpl',
      1 => 1538039445,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5bac9eb42b5e57_33375152 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<form class="col-12 needs-validation mb-5" action="evenements_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
		<input type="hidden" id="id_evenement" name="id_evenement" value="<?php echo $_smarty_tpl->tpl_vars['id_evenement']->value;?>
">
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="titre_fr">Titre:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="titre_fr" name="titre_fr" value="<?php echo $_smarty_tpl->tpl_vars['titre_fr']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir un titre valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="contenu_fr">Contenu:</label>
			<div class="col-sm-10">
				<textarea class="form-control form-control-danger" id="contenu_fr" name="contenu_fr"><?php echo $_smarty_tpl->tpl_vars['contenu_fr']->value;?>
</textarea>
				<div class="invalid-feedback">Veuillez saisir un contenu valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="titre">Date de l'événement:</label>
			<div class="col-sm-2">
				<input type="date" class="form-control form-control-danger" id="date_evenement" name="date_evenement" value="<?php echo $_smarty_tpl->tpl_vars['date_evenement']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_debut">Affiché du:</label>
			<div class="col-sm-2">
				<input type="date" class="form-control form-control-danger" id="date_debut" name="date_debut" value="<?php echo $_smarty_tpl->tpl_vars['date_debut']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_fin">Jusqu'au:</label>
			<div class="col-sm-2">
				<input type="date" class="form-control form-control-danger" id="date_fin" name="date_fin" value="<?php echo $_smarty_tpl->tpl_vars['date_fin']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="ordre">Ordre:</label>
			<div class="col-sm-10">
				<input type="number" class="form-control form-control-danger" id="ordre" name="ordre" value="<?php echo $_smarty_tpl->tpl_vars['ordre']->value;?>
" required>
				<small id="aide_ordre" class="form-text text-muted">
					Ordre d'affichage dans la liste des événéments. Si vous souhaitez intercaler votre événement à la 3è place, saisissez 3. Les événements suivants seront automatiquement décalés.
				</small>		
				<div class="invalid-feedback">Veuillez saisir un nombre valide</div>
			</div>
		</div>
		
		
		
		
	

		
		<div class="container-fluid">		
			<hr class="style-5 mt-5 bg-vert">
			<h2 class="text-left text-gris mb-5">Données pour le référencement</h2>
		</div>
	
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="meta_title_fr">URL:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="meta_url_fr" name="meta_url_fr" value="<?php echo $_smarty_tpl->tpl_vars['meta_url_fr']->value;?>
" required>
				<small id="aide_meta_url_fr" class="form-text text-muted">
					L'url est l'adresse UNIQUE de la page écrite en minuscule. Attention, n'utilisez ni accents ni caracteres spéciaux. Vous devriez retrouver vos mots clés principaux dans l'url. Ne rentrez pas d'extension.
				</small>				
				<div class="invalid-feedback">Veuillez saisir une url valide</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="meta_title_fr">Meta Title:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="meta_title_fr" name="meta_title_fr" value="<?php echo $_smarty_tpl->tpl_vars['meta_title_fr']->value;?>
" required>
				<small id="aide_meta_title_fr" class="form-text text-muted">
					Un Title optimisé comporte environ 70 caractères 
				</small>				
				<div class="invalid-feedback">Veuillez saisir un title valide</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="meta_title_fr">Meta Keywords:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="meta_keywords_fr" name="meta_keywords_fr" value="<?php echo $_smarty_tpl->tpl_vars['meta_keywords_fr']->value;?>
" required>
				<small id="aide_meta_keywords_fr" class="form-text text-muted">
					Pensez à optimiser les mots clés utilisés par rapport au contenu de votre page 
				</small>				
				<div class="invalid-feedback">Veuillez saisir des mots clés valides</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="meta_description_fr">Meta Description:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="meta_description_fr" name="meta_description_fr" value="<?php echo $_smarty_tpl->tpl_vars['meta_description_fr']->value;?>
" required>
				<small id="aide_meta_description_fr" class="form-text text-muted">
					Une Description optimisée comporte environ 150 caractères 
				</small>				
				<div class="invalid-feedback">Veuillez saisir une description valide</div>
			</div>
		</div>
		
		<div class="form-group row mr-1 float-right">
			<button id="btnSubmit" type="submit" class="btn btn-vert">Valider</button>
		</div>		

		
		
		
		
		
		
		
		
		
		<?php if ($_smarty_tpl->tpl_vars['id_evenement']->value != 0) {?>
		
		

			<div class="container-fluid">		
				<hr class="style-5 mt-5 bg-vert">
				<h2 class="text-left text-gris mb-5">Images et fichiers joints</h2>
			</div>
			
			
			<label for="inputfile">Utilisez cet espace pour gérer vos images et pièces jointes</label>
			<div class="file-loading">
				<input id="inputfile" name="inputfile[]" type="file" multiple>
			</div>


							
					
			<?php echo '<script'; ?>
>

				<?php
$__section_liste_initialPreviewImages_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_initialPreviewImages_0_total = $__section_liste_initialPreviewImages_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages'] = new Smarty_Variable(array());
if ($__section_liste_initialPreviewImages_0_total !== 0) {
for ($__section_liste_initialPreviewImages_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] = 0; $__section_liste_initialPreviewImages_0_iteration <= $__section_liste_initialPreviewImages_0_total; $__section_liste_initialPreviewImages_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']++){
$_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['first'] = ($__section_liste_initialPreviewImages_0_iteration === 1);
$_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['last'] = ($__section_liste_initialPreviewImages_0_iteration === $__section_liste_initialPreviewImages_0_total);
?>
					<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['first'] : null)) {?>var <?php }?>
						url<?php echo $_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] : null)]['ordre'];?>
='fichiers/<?php echo $_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] : null)]['nom_fichier'];?>
.<?php echo $_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] : null)]['extension'];?>
'
					<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['last'] : null)) {?>;<?php } else { ?>,<?php }?>
				<?php
}
}
?>
					
					
				$("#inputfile").fileinput({
					language: 'fr',
					theme: 'fas',
					uploadUrl: 'fichier_ajouter.php',
					<?php
$__section_liste_initialPreviewImages_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_initialPreviewImages_1_total = $__section_liste_initialPreviewImages_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages'] = new Smarty_Variable(array());
if ($__section_liste_initialPreviewImages_1_total !== 0) {
for ($__section_liste_initialPreviewImages_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] = 0; $__section_liste_initialPreviewImages_1_iteration <= $__section_liste_initialPreviewImages_1_total; $__section_liste_initialPreviewImages_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']++){
$_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['first'] = ($__section_liste_initialPreviewImages_1_iteration === 1);
$_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['last'] = ($__section_liste_initialPreviewImages_1_iteration === $__section_liste_initialPreviewImages_1_total);
?>
						<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['first'] : null)) {?>initialPreview: [<?php }?>
							url<?php echo $_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] : null)]['ordre'];?>

						<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['last'] : null)) {?>],<?php } else { ?>,<?php }?>
					<?php
}
}
?>		

					initialPreviewAsData: true,

					<?php
$__section_liste_initialPreviewImages_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_initialPreviewImages_2_total = $__section_liste_initialPreviewImages_2_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages'] = new Smarty_Variable(array());
if ($__section_liste_initialPreviewImages_2_total !== 0) {
for ($__section_liste_initialPreviewImages_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] = 0; $__section_liste_initialPreviewImages_2_iteration <= $__section_liste_initialPreviewImages_2_total; $__section_liste_initialPreviewImages_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']++){
$_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['first'] = ($__section_liste_initialPreviewImages_2_iteration === 1);
$_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['last'] = ($__section_liste_initialPreviewImages_2_iteration === $__section_liste_initialPreviewImages_2_total);
?>
						<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['first'] : null)) {?>initialPreviewConfig: [<?php }?>
							{caption: '<?php echo $_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] : null)]['nom_fichier'];?>
.<?php echo $_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] : null)]['extension'];?>
', downloadUrl: url<?php echo $_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] : null)]['ordre'];?>
, size: <?php echo $_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] : null)]['poids'];?>
, width: '<?php echo $_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] : null)]['largeur'];?>
px', key: <?php echo $_smarty_tpl->tpl_vars['liste_initialPreviewImages']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['index'] : null)]['id_fichier'];?>
}
						<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_initialPreviewImages']->value['last'] : null)) {?>],<?php } else { ?>,<?php }?>
					<?php
}
}
?>
					browseClass: 'btn btn-vert',
					removeClass: 'btn btn-danger',
					uploadClass: 'btn btn-primary',		
					deleteUrl: 'fichier_supprimer.php',
					overwriteInitial: false,
					maxFileSize: 1000,
					uploadExtraData: {
						id_menu: '<?php echo $_SESSION['id_admin_menu_selectionne'];?>
',
						id_parent: '<?php echo $_smarty_tpl->tpl_vars['id_evenement']->value;?>
'			 
					},
					initialCaption: 'Cliquer sur parcourir pour ajouter vos fichiers',
					
					preferIconicPreview: true, // this will force thumbnails to display icons for following file extensions
					previewFileIconSettings: { // configure your icon file extensions
						'doc': '<i class="fa fa-file-word-o text-primary"></i>',
						'xls': '<i class="fa fa-file-excel-o text-success"></i>',
						'ppt': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
						'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
						'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
						'htm': '<i class="fa fa-file-code-o text-info"></i>',
						'txt': '<i class="fa fa-file-text-o text-info"></i>',
						'mov': '<i class="fa fa-file-movie-o text-warning"></i>',
						'mp3': '<i class="fa fa-file-audio-o text-warning"></i>',
					},
					previewFileExtSettings: { // configure the logic for determining icon file extensions
						'doc': function(ext) {return ext.match(/(doc|docx)$/i);},
						'xls': function(ext) {return ext.match(/(xls|xlsx)$/i);},
						'ppt': function(ext) {return ext.match(/(ppt|pptx)$/i);},
						'zip': function(ext) {return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);},
						'htm': function(ext) {return ext.match(/(htm|html)$/i);},
						'txt': function(ext) {return ext.match(/(txt|ini|csv|java|php|js|css)$/i);},
						'mov': function(ext) {return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);},
						'mp3': function(ext) {return ext.match(/(mp3|wav)$/i);}
					}		
				});	
					
			<?php echo '</script'; ?>
>		
				
		<?php }?>
		
	</form> 	

	

</body>
</html><?php }
}
