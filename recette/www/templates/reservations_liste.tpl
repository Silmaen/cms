<!--{include file="header.tpl"}-->
<!--{$sens='down'}-->
	<div class="container-fluid">

		<nav aria-label="Pagination">
			
		
			<div class="row">
				<div class="col-7">
				
					<form class="form-inline justify-content-left">
						<div class="form-group col-4">
							<input class="form-control col-12" id="recherche" type="text" placeholder="Recherche ...">
						</div>

						<!--{if $smarty.session.id_utilisateur_groupe<=2}-->
						
							<div class="col-2 text-left">
								<input class="form-control form-check-input" type="radio" value="1" id="filtre_reservations_1" name="filtre_reservations" <!--{if $smarty.session.filtre_statut==1}--> checked <!--{/if}-->onchange='this.form.submit();'>
								<label class="form-control form-check-label text-secondary text-left" for="filtre_reservations">Actives</label>
							</div>
							<div class="col-2 text-left">
								<input class="form-control form-check-input" type="radio" value="3" id="filtre_reservations_3" name="filtre_reservations" <!--{$smarty.session.filtre_statut}--> <!--{if $smarty.session.filtre_statut==3}--> checked <!--{/if}--> onchange='this.form.submit();'>
								<label class="form-control form-check-label text-secondary text-left" for="filtre_reservations">Toutes</label>
							</div>
							<div class="col-2 text-left">
								<input class="form-control form-check-input" type="radio" value="4" id="filtre_reservations_4" name="filtre_reservations" <!--{$smarty.session.filtre_statut}--> <!--{if $smarty.session.filtre_statut==4}--> checked <!--{/if}--> onchange='this.form.submit();'>
								<label class="form-control form-check-label text-secondary text-left text" for="filtre_reservations">A valider</label>
							</div>
							<div class="col-2 text-left">
								<input class="form-control form-check-input" type="radio" value="5" id="filtre_reservations_5" name="filtre_reservations" <!--{$smarty.session.filtre_statut}--> <!--{if $smarty.session.filtre_statut==5}--> checked <!--{/if}--> onchange='this.form.submit();'>
								<label class="form-control form-check-label text-secondary text-left text" for="filtre_reservations">6 mois</label>
							</div>							
						<!--{/if}-->
						
					</form>
				</div>

			

				<div class="col-5">
					<ul class="pagination pull-right">
					
						<!--{if $page_actuelle==1}-->
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<!--{else}-->
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<!--{/if}-->
						
						<!--{for $foo=1 to $smarty.session.nombre_de_pages}-->
							<!--{if $foo==$page_actuelle}-->
								<li class="page-item disabled">
									<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<!--{$foo}-->"><!--{$foo}--></a>
								</li>
							<!--{else}-->
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<!--{$foo}-->"><!--{$foo}--></a>
								</li>
							<!--{/if}-->
						<!--{/for}-->
					
						<!--{if $page_actuelle==$smarty.session.nombre_de_pages}-->
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<!--{$nombre_de_pages}-->" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<!--{else}-->
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<!--{$smarty.session.nombre_de_pages}-->" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<!--{/if}-->
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="reservations_liste.php" method="post">
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
								 <option value='10000'>Tous</option>
							</select>
						</form>
					</div>
					
					
					<div class="pull-right mb-3">
						<!--{if $smarty.session.droit==1}-->
							<a href="reservations_formulaire.php?action=ajouter" class="btn btn-success mr-3" role="button" aria-pressed="true"><i class="fas fa-plus-circle"></i></a>
						<!--{/if}-->		
					</div>
				</div>				
			</div>			
		</nav>
	<!--{if $designation_article_selectionne!=''}-->
		<div class="row bg-light">
			<div class="col-12">
				Liste des réservations pour l'article : <b><!--{$designation_article_selectionne}--></b>
			</div>
		</div>
	<!--{/if}-->
		
	</div>


	
	<div>
		<table class="table table-hover table-striped " id="tableau">
			<thead class="thead-gris text-white">
				<tr class="row">
				
				
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
							<a href="reservations_liste.php?colonne=<!--{$liste_page[liste_page].colonne}-->&sens_tri=<!--{$sens_tri}-->" class="btn btn-<!--{$bouton}--> text-white" role="button" data-toggle="tooltip" data-placement="bottom" title="Trier" aria-pressed="true"><i class="fas fa-sort-alpha-<!--{$sens}-->"></i></a>							
						<!--{else}-->
							<!--{$liste_page[liste_page].colonne_titre_fr}-->
							&nbsp;
							<a href="reservations_liste.php?colonne=<!--{$liste_page[liste_page].colonne}-->&sens_tri=ASC" class="btn btn-link text-white" role="button" data-toggle="tooltip" data-placement="bottom" title="Trier" aria-pressed="true"><i class="fas fa-sort-alpha-down"></i></a>
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
					<td class="col-3 text-left">
						&nbsp;<!--{$picto_etat}--> 
						&nbsp; <!--{$liste_items[liste_items].quantite_reservee}--> 
						&nbsp; <!--{$liste_items[liste_items].association}-->
					</td>
					<td class="col-2 text-left"><!--{$liste_items[liste_items].nom}--> <!--{$liste_items[liste_items].prenom}--></td>
					<td class="col-2 text-left"><!--{$liste_items[liste_items].date_depart|date_format:"%d-%m-%Y"}--></td>
					<td class="col-2 text-left"><!--{$liste_items[liste_items].date_retour|date_format:"%d-%m-%Y"}--></td>
					<td class="col-1 text-left"><!--{$liste_items[liste_items].date_modification|date_format:"%d-%m-%Y"}--> - <!--{$liste_items[liste_items].heure_modification|date_format:"%H:%M"}--></td>
					<td class="col-2 text-center">
							<a href="reservations_formulaire.php?action=modifier&id_reservation=<!--{$liste_items[liste_items].id_reservation}-->" class="btn btn-primary btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Modifier" aria-pressed="true"><i class="fas fa-edit"></i></a>
						
						<!--{if $liste_items[liste_items].email!=''}-->
							<a href="reservation_confirmation.php?action=envoyer&id_reservation=<!--{$liste_items[liste_items].id_reservation}-->" class="btn btn-info btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Envoyer la confirmation de la réservation" aria-pressed="true"><i class="fas fa-envelope"></i></a>
						<!--{/if}-->
							<a href="fiche_reservation.php?action=imprimer&id_reservation=<!--{$liste_items[liste_items].id_reservation}-->" class="btn btn-orange btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Imprimer une réservation" aria-pressed="true"><i class="fas fa-print"></i></a>

						<!--{if $smarty.session.id_utilisateur_groupe<=2}-->
							<a href="reservations_formulaire.php?action=copier&id_reservation=<!--{$liste_items[liste_items].id_reservation}-->" class="btn btn-secondary btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Copier" aria-pressed="true"><i class="fas fa-copy"></i></a>
							
							&nbsp;
							
							<a href="reservations_liste.php?action=activer&id_reservation=<!--{$liste_items[liste_items].id_reservation}-->" class="btn btn-success btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Activer" aria-pressed="true"><i class="fa fa-play" aria-hidden="true"></i></a>
							
							<a href="reservations_liste.php?action=archiver&id_reservation=<!--{$liste_items[liste_items].id_reservation}-->" class="btn btn-warning btn-sm" role="button" data-toggle="tooltip" data-placement="bottom" title="Archiver" aria-pressed="true"><i class="fa fa-archive" aria-hidden="true"></i></a>
							
							<!--{if $smarty.session.id_utilisateur_groupe<=2}-->
							
							<button class="confirmer<!--{$liste_items[liste_items].id_reservation}--> btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Supprimer" aria-pressed="true"><i class="fas fa-trash-alt"></i></button>
							
							<script>								$('.confirmer<!--{$liste_items[liste_items].id_reservation}-->').on('click', function(){
									$.confirm({
										boxWidth: '30%',
										useBootstrap: false,
										title: 'Supprimer cette réservation ?',
										content: 'Choisissez une option',
										buttons: {
											supprimer: function(){
												window.location.href = 'reservations_liste.php?action=supprimer&id_reservation=<!--{$liste_items[liste_items].id_reservation}-->';
											},
											annuler: function()
											{},
											somethingElse: {
												text: 'Suppr. & Email',
												action: function(){
													this.$content // reference to the content
													window.location.href = 'reservations_liste.php?action=supprimer-envoyer&id_reservation=<!--{$liste_items[liste_items].id_reservation}-->';
												}
											}
										}
									});
								});
							</script>
							
						
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
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<!--{else}-->
							<li class="page-item">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only ">Previous</span>
								</a>
							</li>
						<!--{/if}-->
						
						<!--{for $foo=1 to $smarty.session.nombre_de_pages}-->
							<!--{if $foo==$page_actuelle}-->
								<li class="page-item disabled">
									<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<!--{$foo}-->"><!--{$foo}--></a>
								</li>
							<!--{else}-->
								<li class="page-item">
									<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<!--{$foo}-->"><!--{$foo}--></a>
								</li>
							<!--{/if}-->
						<!--{/for}-->
					
						<!--{if $page_actuelle==$smarty.session.nombre_de_pages}-->
							<li class="page-item disabled">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<!--{$smarty.session.nombre_de_pages}-->" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
						<!--{else}-->
							<li class="page-item ">
								<a class="page-link text-vert font-weight-bold bg-gris" href="reservations_liste.php?page=<!--{$smarty.session.nombre_de_pages}-->" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>

						<!--{/if}-->
					</ul>
					
					
					
					<div class="pull-right mb-3">
						<form class="mr-3" id="formulairePagination" action="reservations_liste.php" method="post">
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