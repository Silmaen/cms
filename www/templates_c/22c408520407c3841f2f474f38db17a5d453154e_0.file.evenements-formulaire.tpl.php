<?php
/* Smarty version 3.1.32, created on 2018-08-29 06:54:52
  from 'C:\wamp64\www\bootstrap\administrateur\templates\evenements-formulaire.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5b86433c9705c6_36178387',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '22c408520407c3841f2f474f38db17a5d453154e' => 
    array (
      0 => 'C:\\wamp64\\www\\bootstrap\\administrateur\\templates\\evenements-formulaire.tpl',
      1 => 1535525649,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_5b86433c9705c6_36178387 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>



	
	

	
	<div class="container-fluid">
		<h1 class="text-center text-gris mt-3 mb-5">Edition d'un événement</h1>
	</div>
	
	
	<form class="col-12 needs-validation" novalidate action="/action_page.php">
	
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="titre_fr">Titre:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="titre_fr" value="<?php echo $_smarty_tpl->tpl_vars['titre_fr']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir un titre valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="descriptif_fr">Contenu:</label>
			<div class="col-sm-10">
				<textarea class="form-control form-control-danger" id="descriptif_fr" required><?php echo $_smarty_tpl->tpl_vars['contenu_fr']->value;?>
</textarea>
				<div class="invalid-feedback">Veuillez saisir un contenu valide</div>
			</div>
		</div>
		
	

	
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="titre">Date de l'événement:</label>
			<div class="col-sm-10">
				<input type="date" class="form-control form-control-danger" id="date" value="<?php echo $_smarty_tpl->tpl_vars['date_evenement']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="date_debut">Affiché du:</label>
			<div class="col-sm-4">
				<input type="date" class="form-control form-control-danger" id="date_debut" value="<?php echo $_smarty_tpl->tpl_vars['date_debut']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_fin">Jusqu'au:</label>
			<div class="col-sm-4">
				<input type="date" class="form-control form-control-danger" id="date_fin" value="<?php echo $_smarty_tpl->tpl_vars['date_fin']->value;?>
" required>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
		</div>

		<div class="form-group row">
			
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="ordre">Ordre:</label>
			<div class="col-sm-10">
				<input type="number" class="form-control form-control-danger" id="ordre" value="<?php echo $_smarty_tpl->tpl_vars['ordre']->value;?>
" required>
				<small id="aide_ordre" class="form-text text-muted">
					Ordre d'affichage dans la liste des événéments. Si vous souhaitez intercaler votre événement à la 3è place, saisissez 3. Les événements suivants seront automatiquement décalés.
				</small>		
				<div class="invalid-feedback">Veuillez saisir un nombre valide</div>
			</div>
		</div>

		<div class="container-fluid">		
			<hr class="style-5 mt-5 bg-vert">
			<h2 class="text-left text-gris col 4 mb-5">Images et fichiers joints</h2>
		</div>
		
		
		
		
		
		
		
<!-- PREVIEW DATA -->
<!-- load the JS files in the right order -->
<!-- sortable plugin for sorting/rearranging initial preview -->
<?php echo '<script'; ?>
 src="../cgi-bin/kartik/js/plugins/sortable.min.js"><?php echo '</script'; ?>
>
<!-- purify plugin for safe rendering HTML content in preview -->
<?php echo '<script'; ?>
 src="../cgi-bin/kartik/js/plugins/purify.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="../cgi-bin/kartik/js/fileinput.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="../cgi-bin/kartik/js/locales/fr.js"><?php echo '</script'; ?>
>

<div class="file-loading">
    <input id="input-pd" name="input-pd[]" type="file" multiple>
</div>
<?php echo '<script'; ?>
>
	/* Initialisation pour le téléchargement des fichiers */
	$(document).on('ready', function () {
		$("#file-0b").fileinput();
	});
<?php echo '</script'; ?>
>




<?php echo '<script'; ?>
>
$("#input-pd").fileinput({
	language : "fr",
    uploadUrl: "/fichiers",
    uploadAsync: false,
    minFileCount: 2,
    maxFileCount: 5,
    overwriteInitial: false,
    initialPreview: [
        // IMAGE DATA
        "fichiers/images/image-1.jpg",
        "fichiers/images/image-2.jpg",
		"fichiers/images/image-3.jpg",
		"fichiers/images/image-4.jpg",
		"fichiers/images/image-5.jpg",
		"fichiers/pj/pdf-1.pdf",
		
        // TEXT DATA
        /*"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut mauris ut libero fermentum feugiat eu et dui. Mauris condimentum rhoncus enim, sed semper neque vestibulum id. Nulla semper, turpis ut consequat imperdiet, enim turpis aliquet orci, eget venenatis elit sapien non ante. Aliquam neque ipsum, rhoncus id ipsum et, volutpat tincidunt augue. Maecenas dolor libero, gravida nec est at, commodo tempor massa. Sed id feugiat massa. Pellentesque at est eu ante aliquam viverra ac sed est.",
        // HTML DATA
        '<div class="text-center">' + 
        '<h3>Lorem Ipsum</h3>' + 
        '<p><em>"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..."</em></p>' + 
        '<h5><small>"There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain..."</small></h5>' + 
        '<hr>' + 
        '</div>'*/
    ],
    initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
    initialPreviewFileType: 'image', // image is the default and can be overridden in config below
    initialPreviewDownloadUrl: 'fichiers/images/{filename}', // includes the dynamic `filename` tag to be replaced for each config
    initialPreviewConfig: [
        {caption: "image-1.jpg", size: 827000, width: "120px", url: "fichiers/images", key: 1},
        {caption: "image-2.jpg", size: 549000, width: "120px", url: "fichiers/images", key: 2}, 
        {caption: "image-3.jpg", size: 549000, width: "120px", url: "fichiers/images", key: 3}, 
		{caption: "image-4.jpg", size: 549000, width: "120px", url: "fichiers/images", key: 4}, 
		{caption: "image-5.jpg", size: 549000, width: "120px", url: "fichiers/images", key: 5}, 
		{type: "pdf", size: 8000, caption: "pdf1.pdf", url: "fichiers/pj", key: 6, downloadUrl: false},
		/*{   
            type: "video", 
            size: 375000,
            filetype: "video/mp4",
            caption: "KrajeeSample.mp4", 
            url: "/file-upload-batch/2",
            key: 3,
            downloadUrl: 'http://kartik-v.github.io/bootstrap-fileinput-samples/samples/small.mp4', // override url
            filename: 'KrajeeSample.mp4' // override download filename
        }, 
        {type: "office", size: 102400, caption: "doc1.docx", url: "fichiers/", key: 4}, 
        {type: "office", size: 45056, caption: "xls1.xlsx", url: "/fichiers", key: 5}, 
        {type: "office", size: 512000, caption: "ppt1.ppt", url: "/fichiers", key: 6}, 
        {type: "gdocs", size: 811008, caption: "tif1.tif", url: "/fichiers", key: 7}, 
        {type: "gdocs", size: 375808, caption: "ai1.ai", url: "/fichiers/2", key: 8}, 
        {type: "gdocs", size: 40960, caption: "eps1.eps", url: "/fichiers", key: 9}, 
        {type: "pdf", size: 8000, caption: "pdf1.pdf", url: "/fichiers", key: 10, downloadUrl: false}, // disable download
        {type: "text", size: 1430, caption: "txt1.txt", url: "/fichiers", key: 11, downloadUrl: false},  // disable download
        {type: "html", size: 3550, caption: "html1.html", url: "/fichiers", key: 12, downloadUrl: false}  // disable download*/
    ],
    purifyHtml: true, // this by default purifies HTML data for preview
    uploadExtraData: {
        img_key: "1000",
        img_keywords: "happy, places"
    }
}).on('filesorted', function(e, params) {
    console.log('File sorted params', params);
}).on('fileuploaded', function(e, params) {
    console.log('File uploaded params', params);
});
<?php echo '</script'; ?>
>		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		<div class="container-fluid">		
			<hr class="style-5 mt-5 bg-vert">
			<h2 class="text-left text-gris col 4 mb-5">Données pour le référencement</h2>
		</div>
	
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="meta_title_fr">URL:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="meta_url_fr" value="<?php echo $_smarty_tpl->tpl_vars['meta_url_fr']->value;?>
" required>
				<small id="aide_meta_url_fr" class="form-text text-muted">
					L'url est l'adresse UNIQUE de la page écrite en minuscule. Attention, n'utilisez ni accents ni caracteres spéciaux. Vous devriez retrouver vos mots clés principaux dans l'url.
				</small>				
				<div class="invalid-feedback">Veuillez saisir une url valide</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="meta_title_fr">Meta Title:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="meta_title_fr" value="<?php echo $_smarty_tpl->tpl_vars['meta_title_fr']->value;?>
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
				<input type="text" class="form-control form-control-danger" id="meta_keywords_fr" value="<?php echo $_smarty_tpl->tpl_vars['meta_keywords_fr']->value;?>
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
				<input type="text" class="form-control form-control-danger" id="meta_description_fr" value="<?php echo $_smarty_tpl->tpl_vars['meta_description_fr']->value;?>
" required>
				<small id="aide_meta_description_fr" class="form-text text-muted">
					Une Description optimisée comporte environ 150 caractères 
				</small>				
				<div class="invalid-feedback">Veuillez saisir une description valide</div>
			</div>
		</div>
		
		<div class="form-group row mr-1 float-right">
			<button type="submit" class="btn btn-vert">Valider</button>
		</div>		

	</form> 	
	
	

</body>
</html><?php }
}
