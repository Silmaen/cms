<!DOCTYPE html>
<meta charset="utf-8" />
<html lang="fr">
<head>
	<title>CMS du Comité des Fêtes de Genay</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous" />
	<link rel="stylesheet" href="assets/css/styles.css" />

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css" />

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"  type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=y1xljp9y4lyecedn253xdtv1h72oti7up38ehuqrrtcn9jyu" type="text/javascript"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/js/plugins/sortable.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/js/plugins/purify.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/themes/explorer-fa/theme.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/js/plugins/piexif.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/js/fileinput.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/js/locales/fr.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.5.0/themes/fas/theme.js" type="text/javascript"></script>

	<script src="assets/js/scripts.js" type="text/javascript"></script>

	<link href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet" type="text/css" />
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>



    <script src="assets/vendor/gijgo/js/gijgo.min.js" type="text/javascript"></script>
    <link href="assets/vendor/gijgo/css/gijgo-clc.css" rel="stylesheet" type="text/css" />

    <script src="assets/vendor/gijgo/js/messages/messages.fr-fr.js" type="text/javascript"></script>



</head>
<body data-env="<!--{$cdf_env}-->">

	<!--{if $cdf_env_badge}--><div class="cdf-env-badge"><!--{$cdf_env_libelle}--></div><!--{/if}-->

	<!--{if isset($smarty.session.id_admin_menu_selectionne)}-->

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
				<!--{section name=liste_items_menu loop=$liste_items_menu}-->

					<!--{if $liste_items_menu[liste_items_menu].id_admin_menu<90}-->
						<li class="nav-item mr-3">
							<a class="<!--{if $smarty.session.id_admin_menu_selectionne==<!--{$liste_items_menu[liste_items_menu].id_admin_menu}-->}-->nav-link font-weight-bold active<!--{else}-->nav-link text-dark font-weight-bold<!--{/if}-->" href="<!--{$liste_items_menu[liste_items_menu].url}-->?id_admin_menu=<!--{$liste_items_menu[liste_items_menu].id_admin_menu}-->"><!--{$liste_items_menu[liste_items_menu].titre_fr}--></a>
						</li>
					<!--{/if}-->
				<!--{/section}-->
				</ul>
			</div>
			<div class="col-2">
				<ul class="navbar-nav justify-content-end">
					<!--{if $smarty.session.id_utilisateur_groupe==1}-->
					<li class="nav-item">
						<a class="nav-link text-dark font-weight-bold" href="admin_utilisateurs_liste.php?id_admin_menu=90" title="Utilisateurs" alt="Utilisateurs"><i class="fas fa-user fa-lg"></i></a>
					</li>
					<li class="nav-item">
						<a class="nav-link text-dark font-weight-bold" href="admin_utilisateurs_groupes_liste.php?id_admin_menu=91" title="Groupes d'utilisateurs" alt="Groupes d'utilisateurs"><i class="fas fa-users fa-lg"></i></a>
					</li>
					<!--{/if}-->
					<li class="nav-item">
						<a class="nav-link text-dark font-weight-bold" href="index.php?action=deconnecter" title="Déconnexion" alt="Déconnexion"><i class="fas fa-power-off fa-lg"></i></a>
					</li>
				</ul>
			</div>
		</div>
	</nav>



	<nav>
		<div class="row ml-1 mr-2 mt-2">
			<div class="col-4">
				<!--{if $smarty.session.breadcrumb=="Accueil"}-->
					<a class="breadcrumb-item text-dark" href="accueil.php">//&nbsp; Accueil</a>
				<!--{else}-->
					<a class="breadcrumb-item text-dark" href="<!--{$smarty.session.nom_table}-->_liste.php?id_admin_menu=<!--{$smarty.session.id_admin_menu_selectionne}-->">//&nbsp; <!--{$smarty.session.breadcrumb}--></a>
				<!--{/if}-->
			</div>

			<div class="col-4 text-center">
				<h1 class="d-inline text-gris  align-top"><!--{$smarty.session.titre_page_fr}--></h1>
			</div>


			<div class="col-4 text-right">
				<h6 class="d-inline ml-3 font-weight-bold text-left text-danger"><!--{$message_formulaire}--></h6>
			</div>
		</div>
		<div class="container-fluid">
			<hr class="style-5 bg-vert">
		</div>
	</nav>

	<!--{/if}-->
