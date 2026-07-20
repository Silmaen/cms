<?php
/* Smarty version 3.1.32, created on 2018-09-27 09:10:19
  from 'C:\wamp64\www\bootstrap\administrateur\templates\header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5bac9e7b50f750_88534273',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3e634760fa389252c1f679ed4f31c1224fa6680d' => 
    array (
      0 => 'C:\\wamp64\\www\\bootstrap\\administrateur\\templates\\header.tpl',
      1 => 1538039409,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5bac9e7b50f750_88534273 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<meta charset="utf-8" />
<html lang="fr">
<head>
	<title>CMS Bootstrap</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous" />
	<link rel="stylesheet" href="css/styles.css" />

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />	
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css" />
	
	<?php echo '<script'; ?>
 src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"  type="text/javascript"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" type="text/javascript"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=esvm5qeob0yllpz8a3gkbqsasy7y03dyo0s9z4lnil7k572b" type="text/javascript"><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/js/plugins/sortable.min.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/js/plugins/purify.min.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/themes/explorer-fa/theme.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/js/plugins/piexif.min.js" type="text/javascript"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/js/fileinput.min.js" type="text/javascript"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/js/locales/fr.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/themes/fas/theme.js" type="text/javascript"><?php echo '</script'; ?>
>
	
	<?php echo '<script'; ?>
 src="js/scripts.js" type="text/javascript"><?php echo '</script'; ?>
>

	<link href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet" type="text/css" />
	<?php echo '<script'; ?>
 src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"><?php echo '</script'; ?>
>
	
	<?php echo '<script'; ?>
 type="text/javascript" src="https://www.gstatic.com/charts/loader.js"><?php echo '</script'; ?>
>
   
  
</head>
<body>

	<?php if (isset($_SESSION['id_admin_menu_selectionne'])) {?>

	<nav class="navbar navbar-expand-md navbar-dark bg-vert">
		
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar7">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="navbar-collapse collapse " id="navbar7">
			<div class="col-1">
				<a href="accueil.php" class="navbar-brand">Logo</a>
			</div>
			<div class="col-9">
				<ul class="navbar-nav ml-auto ">
				<?php
$__section_liste_items_menu_3_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_items_menu']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_items_menu_3_total = $__section_liste_items_menu_3_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu'] = new Smarty_Variable(array());
if ($__section_liste_items_menu_3_total !== 0) {
for ($__section_liste_items_menu_3_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] = 0; $__section_liste_items_menu_3_iteration <= $__section_liste_items_menu_3_total; $__section_liste_items_menu_3_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']++){
?> 
					<li class="nav-item mr-3">
						<a class="<?php ob_start();
echo $_smarty_tpl->tpl_vars['liste_items_menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] : null)]['id_admin_menu'];
$_prefixVariable1 = ob_get_clean();
if ($_SESSION['id_admin_menu_selectionne'] == $_prefixVariable1) {?>nav-link font-weight-bold active<?php } else { ?>nav-link text-dark font-weight-bold<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['liste_items_menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] : null)]['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['liste_items_menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] : null)]['titre_fr'];?>
</a>
					</li>
					<?php
}
}
?>
				</ul>		
			</div>
			<div class="col-2">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link text-dark font-weight-bold" href="admin_preferences.php"><i class="fas fa-sliders-h"></i> Préférences</a>
					</li>
					<li class="nav-item">
						<a class="nav-link text-dark font-weight-bold" href="index.php?action=deconnecter"><i class="fas fa-power-off"></i> Déconnexion</a>
					</li> 
				</ul>
			</div>
		</div>
	</nav>



	<nav>	
		<div class="row ml-1 mr-2 mt-2">
			<div class="col-4">
				<a class="breadcrumb-item text-dark" href="<?php echo $_smarty_tpl->tpl_vars['nom_table']->value;?>
_liste.php">//&nbsp; <?php echo $_smarty_tpl->tpl_vars['breadcrumb']->value;?>
</a>
			</div>
			
			<div class="col-4 text-center">
				<h1 class="d-inline text-gris  align-top"><?php echo $_smarty_tpl->tpl_vars['titre_menu']->value;?>
</h1>
			</div>	
			
			
			<div class="col-4 text-right">
				<h6 class="d-inline ml-3 font-weight-bold text-left text-danger"><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</h6>
			</div>	
		</div>
		<div class="container-fluid">		
			<hr class="style-5 bg-vert">
		</div>
	</nav>
	
	<?php }
}
}
