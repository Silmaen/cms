<!--{include file="header.tpl"}-->
<!--{$sens='down'}-->

	<div class="container-fluid">

		<nav aria-label="Pagination">
			<div class="row">

				<div class="col-4">
					<form class="form-inline justify-content-left">
						<input class="form-control col-10 mr-2" id="recherche" type="text" placeholder="Recherche ...">
					</form>
				</div>

				<div class="col-8">
					<ul class="pagination pull-right">
					
						<!--{if $page_actuelle==1}-->
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<!--{else}-->
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<!--{/if}-->
						
						<!--{for $foo=1 to $smarty.session.nombre_de_pages}-->
							<!--{if $foo==$page_actuelle}-->
								<li class="page-item disabled">
									<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=<!--{$foo}-->"><!--{$foo}--></a>
								</li>
							<!--{else}-->
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=<!--{$foo}-->"><!--{$foo}--></a>
								</li>
							<!--{/if}-->
						<!--{/for}-->
					
						<!--{if $page_actuelle==$smarty.session.nombre_de_pages}-->
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=<!--{$smarty.session.nombre_de_pages}-->" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<!--{else}-->
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=<!--{$smarty.session.nombre_de_pages}-->" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<!--{/if}-->
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="admin_utilisateurs_liste.php" method="post">
							<select class="form-control" id='items_par_page' name='items_par_page' onchange='if(this.value != 0) { this.form.submit(); }'>
								 <option value='<!--{$smarty.session.items_par_page}-->' selected>
									<!--{if $smarty.session.items_par_page==10000}-->
										Tous les items
									<!--{else}-->
										<!--{$smarty.session.items_par_page}--> par page
									<!--{/if}-->
								 </option>
								 <option value='100'>100 par page</option>
								 <option value='200'>200 par page</option>
								 <option value='300'>300 par page</option>
								 <option value='10000'>Tous les items</option>
							</select>
						</form>
					</div>
					
					
					<div class="pull-right mb-3">
						<!--{if $smarty.session.droit==1}-->
							<a href="<!--{$smarty.session.nom_table}-->_formulaire.php?action=ajouter" class="btn btn-success mr-3" role="button" aria-pressed="true"><i class="fas fa-plus-circle"></i></a>	
						<!--{/if}-->	
					</div>
					
				</div>
			</div>
			
		</nav>
		
	</div>

	
	
	
	
	<div>
	  
		<table class="table table-hover table-striped" id="tableau">
			<thead class="thead-gris text-white">
				<tr  class="row">
					
					<!--{section name=liste_page loop=$liste_page}-->		
					
					<th class="col-<!--{$liste_page[liste_page].largeur}--> text-left align-text-bottom">
						<!--{if $liste_page[liste_page].ordre==1}-->
							&nbsp;
						<!--{/if}-->

						<!--{if $smarty.session.colonne==$liste_page[liste_page].colonne}-->
							<!--{$bouton='info'}-->
							<!--{if $smarty.session.sens_tri=='DESC'}-->
								<!--{$sens='up'}-->
								<!--{$sens_tri='ASC'}-->
							<!--{else}-->
								<!--{$sens='down'}-->
								<!--{$sens_tri='DESC'}-->
							<!--{/if}-->
							
							<!--{$liste_page[liste_page].colonne_titre_fr}-->
							&nbsp;
							<a href="admin_utilisateurs_liste.php?colonne=<!--{$liste_page[liste_page].colonne}-->&sens_tri=<!--{$sens_tri}-->" class="btn btn-<!--{$bouton}--> text-white" role="button" data-toggle="tooltip" data-placement="bottom" title="Trier" aria-pressed="true"><i class="fas fa-sort-alpha-<!--{$sens}-->"></i></a>							
						<!--{else}-->
							<!--{$liste_page[liste_page].colonne_titre_fr}-->
							&nbsp;
							<a href="admin_utilisateurs_liste.php?colonne=<!--{$liste_page[liste_page].colonne}-->&sens_tri=ASC" class="btn btn-link text-white" role="button" data-toggle="tooltip" data-placement="bottom" title="Trier" aria-pressed="true"><i class="fas fa-sort-alpha-down"></i></a>
						<!--{/if}-->											
						
					</th>
										
					<!--{/section}-->
					
					
					
					
					<th class="col-2 text-center align-text-bottom">Actions</th>
				</tr>
			</thead>
			
		
			
			
			
			<tbody id="TableListe">
				
				<!--{section name=liste_items loop=$liste_items}-->
				<!--{if $liste_items[liste_items].id_etat=='1'}-->
					<!--{$picto_etat='<i class="fa fa-circle fa-xs text-success" aria-hidden="true"></i>'}-->
				<!--{elseif $liste_items[liste_items].id_etat=='2'}-->	
					<!--{$picto_etat='<i class="fa fa-circle fa-xs text-warning" aria-hidden="true"></i>'}-->
				<!--{else}-->
					<!--{$picto_etat='<i class="fa fa-circle fa-xs text-danger" aria-hidden="true"></i>'}-->
				<!--{/if}-->

				<tr class="row">
					<td class="col-6 text-left">&nbsp;<!--{$picto_etat}--> &nbsp; <!--{$liste_items[liste_items].nom_utilisateur}--> <!--{$liste_items[liste_items].prenom_utilisateur}--></td>
					<td class="col-4 text-left"><!--{$liste_items[liste_items].libelle_utilisateur_groupe}--></td>
					<td class="col-2 text-center">
						<a href="admin_utilisateurs_formulaire.php?action=modifier&id_utilisateur=<!--{$liste_items[liste_items].id_utilisateur}-->" class="btn btn-primary btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Modifier" aria-pressed="true"><i class="fas fa-edit"></i></a>
	
						<a href="mdp_perdu.php?email=<!--{$liste_items[liste_items].email_utilisateur}-->" class="btn btn-info btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Envoyer" aria-pressed="true"><i class="fas fa-envelope"></i></a>
						
						<!--{if $smarty.session.id_utilisateur_groupe<=2}-->
						
							<a href="admin_utilisateurs_formulaire.php?action=copier&id_utilisateur=<!--{$liste_items[liste_items].id_utilisateur}-->" class="btn btn-secondary btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Copier" aria-pressed="true"><i class="fas fa-copy"></i></a>
							
								&nbsp;					
							
							<a href="admin_utilisateurs_liste.php?action=activer&id_utilisateur=<!--{$liste_items[liste_items].id_utilisateur}-->" class="btn btn-success btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Activer" aria-pressed="true"><i class="fa fa-play" aria-hidden="true"></i></a>
								
							<a href="admin_utilisateurs_liste.php?action=archiver&id_utilisateur=<!--{$liste_items[liste_items].id_utilisateur}-->" class="btn btn-warning btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Archiver" aria-pressed="true"><i class="fa fa-archive" aria-hidden="true"></i></a>

							<!--{if $smarty.session.id_utilisateur_groupe<=1}-->
							
								<a href="admin_utilisateurs_liste.php?action=supprimer&id_utilisateur=<!--{$liste_items[liste_items].id_utilisateur}-->" class="btn btn-danger btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Supprimer" aria-pressed="true" onclick="return confirm('Confirmez-vous la suppression?');"><i class="fas fa-trash-alt"></i></a>
							

							<!--{/if}-->
							
						<!--{/if}-->
					</td>
				</tr>
				<!--{/section}-->
			</tbody>
		</table>
	</div>

		<nav aria-label="Pagination">
			<div class="row">	
				
				<div class="col-12">
					<ul class="pagination pull-right">
					
						<!--{if $page_actuelle==1}-->
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<!--{else}-->
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<!--{/if}-->
						
						<!--{for $foo=1 to $smarty.session.nombre_de_pages}-->
							<!--{if $foo==$page_actuelle}-->
								<li class="page-item disabled">
									<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=<!--{$foo}-->"><!--{$foo}--></a>
								</li>
							<!--{else}-->
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=<!--{$foo}-->"><!--{$foo}--></a>
								</li>
							<!--{/if}-->
						<!--{/for}-->
					
						<!--{if $page_actuelle==$smarty.session.nombre_de_pages}-->
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=<!--{$smarty.session.nombre_de_pages}-->" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<!--{else}-->
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="admin_utilisateurs_liste.php?page=<!--{$smarty.session.nombre_de_pages}-->" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<!--{/if}-->
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="admin_utilisateurs_liste.php" method="post">
							<select class="form-control" name='items_par_page' onchange='if(this.value != 0) { this.form.submit(); }'>
								 <option value='<!--{$smarty.session.items_par_page}-->' selected>
									<!--{if $smarty.session.items_par_page==10000}-->
										Tous les items
									<!--{else}-->
										<!--{$smarty.session.items_par_page}--> par page
									<!--{/if}-->
								 </option>
								 <option value='100'>100 par page</option>
								 <option value='200'>200 par page</option>
								 <option value='300'>300 par page</option>
								 <option value='10000'>Tous les items</option>
							</select>
						</form>
					</div>
					

				</div>
			</div>
			
		</nav>
		
	

	

</body>
</html>