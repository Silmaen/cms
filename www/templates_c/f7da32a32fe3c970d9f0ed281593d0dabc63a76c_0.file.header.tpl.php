<?php
/* Smarty version 3.1.32, created on 2026-07-25 14:33:52
  from '/var/www/html/www/templates/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_6a64c95060e9d1_66608987',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f7da32a32fe3c970d9f0ed281593d0dabc63a76c' => 
    array (
      0 => '/var/www/html/www/templates/header.tpl',
      1 => 1784983155,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a64c95060e9d1_66608987 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<meta charset="utf-8" />
<html lang="fr">
<head>
	<title>Comité des Fêtes de Genay</title>
	<link rel="icon" type="image/svg+xml" href="images/logo_cms.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="fontawesome/css/all520.css" />
	<link rel="stylesheet" href="css/styles.css" />

	<link href="fontawesome/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css" />
	<link href="kartik/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css" />
	<link href="kartik/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />	
	<link href="kartik/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css" />
	
	<?php echo '<script'; ?>
 src="js/jquery.min.js"  type="text/javascript"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="js/popper.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="bootstrap/js/bootstrap.min.js" type="text/javascript"><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
 src="kartik/js/plugins/sortable.min.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="kartik/js/plugins/purify.min.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="kartik/js/plugins/piexif.min.js" type="text/javascript"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="kartik/js/fileinput.min.js" type="text/javascript"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="kartik/js/locales/fr.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="kartik/themes/explorer-fa/theme.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="kartik/themes/fas/theme.js" type="text/javascript"><?php echo '</script'; ?>
>
	
	<?php echo '<script'; ?>
 src="js/scripts.js" type="text/javascript"><?php echo '</script'; ?>
>

	<link href="js/jqueryconfirm/css/jquery-confirm.css" rel="stylesheet" type="text/css" />
	<?php echo '<script'; ?>
 src="js/jqueryconfirm/js/jquery-confirm.js"><?php echo '</script'; ?>
>
	
    <?php echo '<script'; ?>
 src="gijgo/js/gijgo.min.js" type="text/javascript"><?php echo '</script'; ?>
>
    <link href="gijgo/css/gijgo-clc.css" rel="stylesheet" type="text/css" />
 
    <?php echo '<script'; ?>
 src="gijgo/js/messages/messages.fr-fr.js" type="text/javascript"><?php echo '</script'; ?>
>
 
</head>
<body data-env="<?php echo $_smarty_tpl->tpl_vars['cdf_env']->value;?>
">

	<?php if ($_smarty_tpl->tpl_vars['cdf_env_badge']->value) {?><div class="cdf-env-badge"><?php echo $_smarty_tpl->tpl_vars['cdf_env_libelle']->value;?>
</div><?php }?>

	<?php if (isset($_SESSION['id_admin_menu_selectionne'])) {?>

	<nav class="navbar navbar-expand-md navbar-dark bg-vert">
		
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar7">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="navbar-collapse collapse " id="navbar7">
			<div class="col-1">
				<a href="accueil.php" class="navbar-brand">				
					<svg width="100%" height="100%" version="1.1" viewBox="0 0 30.333 7.8233">
						<g transform="translate(-14.147 -21.504)">
							<g fill="#343a40" stroke-width=".26458" aria-label="cms">
								<path d="m17.978 29.221q-1.778 0-2.8575-1.2171-0.97367-1.1112-0.97367-2.667 0-1.5346 0.93133-2.5612 1.0583-1.1642 2.8998-1.1642h4.2862v1.3229h-4.2862q-1.143 0-1.8521 0.75142-0.62442 0.66675-0.66675 1.651-0.03175 1.0372 0.59267 1.7568 0.6985 0.80433 1.9262 0.80433h4.2862v1.3229z" />
								<path d="m28.266 28.819-2.5506-4.9107-1.1007 5.334h-1.4922l1.6933-6.985q0.16933-0.6985 0.79375-0.75142 0.45508-0.04233 0.85725 0.75142l2.5506 4.9742 2.54-4.9848q0.40217-0.78317 0.85725-0.74083 0.62442 0.05292 0.79375 0.74083l1.6933 6.9956h-1.4922l-1.1007-5.334-2.5506 4.9107q-0.26458 0.508-0.71967 0.508-0.508 0-0.77258-0.508z" />
								<path d="m41.876 29.221h-5.7997v-1.3229h5.7997q0.65617 0 1.0478-0.32808 0.35983-0.30692 0.35983-0.73025 0-0.42333-0.34925-0.70908-0.39158-0.3175-1.0583-0.3175h-3.5983q-1.1218 0-1.778-0.65617-0.59267-0.59267-0.59267-1.4499 0-0.84667 0.59267-1.4393 0.65617-0.65617 1.778-0.65617h5.7468v1.3229h-5.7468q-0.42333 0-0.66675 0.23283-0.24342 0.23283-0.24342 0.55033t0.22225 0.52917q0.254 0.24342 0.68792 0.24342h3.5983q1.2171 0 1.9473 0.74083 0.65617 0.67733 0.65617 1.6404t-0.64558 1.6192q-0.71967 0.73025-1.9579 0.73025z" />
							</g>
						</g>
					</svg>					
				</a>
			</div>
			<div class="col-9">
				<ul class="navbar-nav ml-auto ">
				<?php
$__section_liste_items_menu_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['liste_items_menu']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_liste_items_menu_0_total = $__section_liste_items_menu_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu'] = new Smarty_Variable(array());
if ($__section_liste_items_menu_0_total !== 0) {
for ($__section_liste_items_menu_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] = 0; $__section_liste_items_menu_0_iteration <= $__section_liste_items_menu_0_total; $__section_liste_items_menu_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']++){
?> 
				
					<?php if ($_smarty_tpl->tpl_vars['liste_items_menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] : null)]['id_admin_menu'] < 90) {?>
						<li class="nav-item mr-3">
							<a class="<?php ob_start();
echo $_smarty_tpl->tpl_vars['liste_items_menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] : null)]['id_admin_menu'];
$_prefixVariable1 = ob_get_clean();
if ($_SESSION['id_admin_menu_selectionne'] == $_prefixVariable1) {?>nav-link font-weight-bold active<?php } else { ?>nav-link text-dark font-weight-bold<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['liste_items_menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] : null)]['url'];?>
?id_admin_menu=<?php echo $_smarty_tpl->tpl_vars['liste_items_menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] : null)]['id_admin_menu'];?>
"><?php echo $_smarty_tpl->tpl_vars['liste_items_menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_liste_items_menu']->value['index'] : null)]['titre_fr'];?>
</a>
						</li>
					<?php }?>	
				<?php
}
}
?>
				</ul>		
			</div>
			<div class="col-2">
				<ul class="navbar-nav justify-content-end">
					<li class="nav-item">
						<a class="nav-link text-dark font-weight-bold" href="aide/aide.pdf" target="_blank"title="Aide" alt="Aide"><i class="fas fa-question fa-lg"></i></a>
					</li> 
					<?php if ($_SESSION['id_utilisateur_groupe'] == 1) {?>
					<li class="nav-item">
						<a class="nav-link text-dark font-weight-bold" href="admin_utilisateurs_liste.php?id_admin_menu=90" title="Utilisateurs" alt="Utilisateurs"><i class="fas fa-user fa-lg"></i></a>
					</li> 
					<!--
					
					<li class="nav-item">
						<a class="nav-link text-dark font-weight-bold" href="admin_utilisateurs_groupes_liste.php?id_admin_menu=91" title="Groupes d'utilisateurs" alt="Groupes d'utilisateurs"><i class="fas fa-users fa-lg"></i></a>
					</li> 
					
					-->
					<?php }?>
					<li class="nav-item">
						<a class="nav-link text-dark font-weight-bold" href="index.php?action=deconnecter" title="Déconnecter <?php echo $_SESSION['prenom_utilisateur'];?>
 <?php echo $_SESSION['nom_utilisateur'];?>
" alt="Déconnecter <?php echo $_SESSION['prenom_utilisateur'];?>
 <?php echo $_SESSION['nom_utilisateur'];?>
"><i class="fas fa-power-off fa-lg"></i></a>
					</li> 
				</ul>
			</div>
		</div>
	</nav>



	<nav>	
		<div class="row ml-1 mr-2 mt-2">
			<div class="col-4">
				<?php if ($_SESSION['breadcrumb'] == "Accueil") {?>
					<a class="breadcrumb-item text-dark" href="accueil.php">//&nbsp; Accueil</a>
				<?php } else { ?>
					<a class="breadcrumb-item text-dark" href="<?php echo $_SESSION['nom_table'];?>
_liste.php?id_admin_menu=<?php echo $_SESSION['id_admin_menu_selectionne'];?>
">//&nbsp; <?php echo $_SESSION['breadcrumb'];?>
</a>
				<?php }?>
			</div>
			
			<div class="col-4 text-center">
				<h1 class="d-inline text-gris  align-top"><?php echo $_SESSION['titre_page_fr'];?>
</h1>
			</div>	
			
			
			<div class="col-4 text-right">
				<h6 class="d-inline ml-3 font-weight-bold text-left text-danger"><?php echo $_smarty_tpl->tpl_vars['message_formulaire']->value;?>
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
