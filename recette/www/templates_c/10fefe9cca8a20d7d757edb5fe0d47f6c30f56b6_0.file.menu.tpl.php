<?php
/* Smarty version 3.1.32, created on 2018-08-28 13:43:27
  from 'C:\wamp64\www\bootstrap\administrateur\templates\menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5b85517fd9d040_05037844',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '10fefe9cca8a20d7d757edb5fe0d47f6c30f56b6' => 
    array (
      0 => 'C:\\wamp64\\www\\bootstrap\\administrateur\\templates\\menu.tpl',
      1 => 1535463807,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b85517fd9d040_05037844 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="fr">
<head>
	<title>CMS Bootstrap</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<link rel="stylesheet" href="css/styles.css">

	<link href="../cgi-bin/kartik/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>	
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css"/>
	<link href="../cgi-bin/kartik/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>

	
	
    <?php echo '<script'; ?>
 src="../cgi-bin/kartik/js/plugins/sortable.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="../cgi-bin/kartik/js/fileinput.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="../cgi-bin/kartik/js/locales/fr.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="../cgi-bin/kartik/themes/explorer-fa/theme.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="../cgi-bin/kartik/themes/fa/theme.js" type="text/javascript"><?php echo '</script'; ?>
>
	
	
	
	<?php echo '<script'; ?>
 src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"><?php echo '</script'; ?>
>
	
	<?php echo '<script'; ?>
 src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=esvm5qeob0yllpz8a3gkbqsasy7y03dyo0s9z4lnil7k572b"><?php echo '</script'; ?>
> 
	<?php echo '<script'; ?>
>
		// Evite les problèmes de focusIn entre Bootstrap et TinyMCE
		$(document).on('focusin', function(e) {
		  if ($(e.target).closest(".mce-window").length) {
			e.stopImmediatePropagation();
		  }
		});
	
		// Paramétrage TINYMCE pour les champs de type textarea
		tinymce.init({
			selector: 'textarea',
			language_url : '../cgi-bin/tinymce/langues/fr_FR.js',
			plugins: "code textcolor colorpicker hr image media link",
			toolbar: "code | fullpage |undo redo | bold italic | alignleft aligncenter alignright alignjustify | forecolor backcolor | bullist numlist outdent indent | hr | image link",
			media_live_embeds: true, 
			image_advtab: true,
			image_prepend_url: "fichiers/images/",
			//image_list: "/mylist.php",
			image_list: [
				{title: 'Mon image 1', value: 'image-1.jpg'},
				{title: 'Mon image 2', value: 'image-2.jpg'}
			],
			// Rentrer ici les classes proposées pour les liens
			link_class_list: [
				{title: 'None', value: ''},
				{title: 'Dog', value: 'dog'},
				{title: 'Cat', value: 'cat'}
			]
		});
	
		// Javascript gérant la validité des champs du formulaire
		(function() {
		  'use strict';
		  window.addEventListener('load', function() {
			// Fetch all the forms we want to apply custom Bootstrap validation styles to
			var forms = document.getElementsByClassName('needs-validation');
			// Loop over them and prevent submission
			var validation = Array.prototype.filter.call(forms, function(form) {
			  form.addEventListener('submit', function(event) {
				if (form.checkValidity() === false) {
				  event.preventDefault();
				  event.stopPropagation();
				}
				form.classList.add('was-validated');
			  }, false);
			});
		  }, false);
		})();
	<?php echo '</script'; ?>
>  
  
</head>
<body>


	<div class=""> CECI EST UN TEST
		<?php
$__section_liste_items_menu_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_items_menu']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_items_menu_0_total = $__section_liste_items_menu_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu'] = new Smarty_Variable(array());
if ($__section_liste_items_menu_0_total !== 0) {
for ($__section_liste_items_menu_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] = 0; $__section_liste_items_menu_0_iteration <= $__section_liste_items_menu_0_total; $__section_liste_items_menu_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']++){
?> 
		<a href="<?php echo $_smarty_tpl->tpl_vars['liste_items_menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] : null)]['url'];?>
.php"><?php echo $_smarty_tpl->tpl_vars['liste_items_menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] : null)]['titre_fr'];?>
</a><br />
		<?php
}
}
?>
	</div>




	<nav class="navbar navbar-expand-md navbar-dark bg-vert">
		<a href="/" class="navbar-brand">Logo</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar7">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="navbar-collapse collapse justify-content-stretch" id="navbar7">
			<ul class="navbar-nav ml-auto ">
			
			
			
			
				<li class="nav-item mr-3">
					<a class="<?php if ($_smarty_tpl->tpl_vars['menu']->value == 'programmes') {?>nav-link font-weight-bold active<?php } else { ?>nav-link text-dark font-weight-bold<?php }?>" href="#">Programmes</a>
				</li>
				<li class="nav-item mr-3">
					<a class="<?php if ($_smarty_tpl->tpl_vars['menu']->value == 'publications') {?>nav-link font-weight-bold active<?php } else { ?>nav-link text-dark font-weight-bold<?php }?>" href="#">Publications</a>
				</li>    
				<li class="nav-item mr-3">
					<a class="<?php if ($_smarty_tpl->tpl_vars['menu']->value == 'evenements') {?>nav-link font-weight-bold active<?php } else { ?>nav-link text-dark font-weight-bold<?php }?>" href="#">Evénements</a>
				</li>    
				<li class="nav-item mr-3">
					<a class="<?php if ($_smarty_tpl->tpl_vars['menu']->value == 'faq') {?>nav-link font-weight-bold active<?php } else { ?>nav-link text-dark font-weight-bold<?php }?>" href="#">FAQ</a>
				</li>    
				<li class="nav-item mr-3">
					<a class="<?php if ($_smarty_tpl->tpl_vars['menu']->value == 'textes') {?>nav-link font-weight-bold active<?php } else { ?>nav-link text-dark font-weight-bold<?php }?>" href="#">Textes</a>
				</li>    
				<li class="nav-item mr-3">
					<a class="<?php if ($_smarty_tpl->tpl_vars['menu']->value == 'temoignages') {?>nav-link font-weight-bold active<?php } else { ?>nav-link text-dark font-weight-bold<?php }?>" href="#">Témoignages</a>
				</li>    				
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link text-dark font-weight-bold" href="#"><i class="fas fa-power-off"></i> Déconnexion</a>
				</li> 
			</ul>
		</div>
	</nav>
</body>
</html>	
	<?php }
}
