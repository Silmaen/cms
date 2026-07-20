<!--{include file="header.tpl"}-->



<!--{if $smarty.session.id_utilisateur_groupe<=1}-->
	<!--{$readonly = ""}-->
<!--{else}-->
	<!--{$readonly = "readonly"}-->
<!--{/if}-->


	<form name="formulaire-inventaires" id="formulaire-inventaires" class="col-12 needs-validation mb-5" action="inventaires_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<!--{$action}-->">
		<input type="hidden" id="id_inventaire" name="id_inventaire" value="<!--{$id_inventaire}-->">
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="date_creation">Date de création:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_creation" name="date_creation" value="<!--{$date_creation}-->" readonly>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_modification">Date de modification:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_modification" name="date_modification" value="<!--{$date_modification}-->" readonly>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="modifie_par">Par:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="modifie_par" name="modifie_par" value="<!--{$modifie_par}-->" readonly>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label text-left" for="libelle_etat">Etat:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="libelle_etat" name="libelle_etat" value="<!--{$libelle_etat}-->" readonly>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_inventaire">Date d'inventaire:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_inventaire" name="date_inventaire" value="<!--{$date_inventaire}-->" onkeydown="return false" <!--{$readonly}-->>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="id_statut_inventaire">Statut:</label>
			<div class="col-sm-2">
				<select class="form-control form-control-danger" id="id_statut_inventaire" name="id_statut_inventaire" <!--{$readonly}-->>
					
					<!--{if $id_statut_inventaire!=''}-->
						<option value="<!--{$id_statut_inventaire}-->" selected><!--{$libelle_statut}--></option>
					<!--{/if}-->
					<!--{section name=liste_statuts loop=$liste_statuts}-->
						<option value="<!--{$liste_statuts[liste_statuts].id_statut_inventaire}-->"><!--{$liste_statuts[liste_statuts].libelle_statut}--></option>
					<!--{/section}-->
				</select>
			</div>

		</div>
	
		<div class="form-group row">
			<label class="col-sm-2 col-form-label text-left" for="id_type_inventaire">Type:</label>
			<div class="col-sm-2">
				<select class="form-control form-control-danger" id="id_type_inventaire" name="id_type_inventaire" <!--{$readonly}-->>
					
					<!--{if $id_type_inventaire!=''}-->
						<option value="<!--{$id_type_inventaire}-->" selected><!--{$libelle_type}--></option>
					<!--{/if}-->
					<!--{section name=liste_types loop=$liste_types}-->
						<option value="<!--{$liste_types[liste_types].id_type_inventaire}-->"><!--{$liste_types[liste_types].libelle_type}--></option>
					<!--{/section}-->
				</select>
			</div>
		
			<label class="col-sm-2 col-form-label text-right" for="commentaire">Commentaire:</label>
			<div class="col-sm-6">
				<textarea class="form-control form-control-danger" id="commentaire" name="commentaire" rows="2" <!--{$readonly}-->><!--{$commentaire}--></textarea>
				<div class="invalid-feedback">Veuillez saisir un commentaire valide</div>
			</div>
		</div>
	
		<div class="form-group row">		
			<hr class="style-5 mt-5 bg-vert">
			<div class="col-9">
				<h2 class="text-left text-gris mb-3">Liste des articles</h2>
			</div>		
			<div class="col-3 text-right">					
				<a href="<!--{$smarty.session.nom_table}-->_liste.php?id_admin_menu=<!--{$smarty.session.id_admin_menu_selectionne}-->" class="btn btn-lg btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Annuler" aria-pressed="true"><i class="fas fa-times-circle fa-lg"></i></a>
				<!--{if $smarty.session.droit==1}-->
					&nbsp;&nbsp;&nbsp;
					<button id="btnSubmit" type="submit" class="btn btn-lg btn-vert"><i class="fas fa-check-circle fa-lg"></i></button>
				<!--{/if}-->
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
				
				<!--{$compteur=0}-->
				<!--{$i=0}-->
				<!--{section name=liste_articles loop=$liste_articles}-->		

				<!--{if $i==0}-->
					<!--{$couleur_fond='bg-light'}-->
					<!--{$i=1}-->
				<!--{else}-->
					<!--{$couleur_fond='bg-grey'}-->
					<!--{$i=0}-->
				<!--{/if}-->
				
				
				
					<div id="ligne_<!--{$compteur}-->" name="ligne_<!--{$compteur}-->" class="row <!--{$couleur_fond}-->">
						<div class="col-3 text-left">
							<label class="col-form-label" for="qtetotale_<!--{$compteur}-->"><!--{$liste_articles[liste_articles].designation}--></label>
						</div>
						<div class="col-2 text-center">
							<input type="hidden" id="id_article_<!--{$compteur}-->" name="id_article_<!--{$compteur}-->" value="<!--{$liste_articles[liste_articles].id_article}-->" <!--{$readonly}-->>

							<input type="number" class="form-control text-center" id="qteprecedent_<!--{$compteur}-->" name="qteprecedent_<!--{$compteur}-->" value="<!--{$liste_articles[liste_articles].quantite_precedent}-->" readonly>
						</div>
						<div class="col-2 text-center">
							<input type="number" class="form-control text-center" id="qtetotale_<!--{$compteur}-->" name="qtetotale_<!--{$compteur}-->" value="<!--{$liste_articles[liste_articles].quantite_totale}-->" onChange="Ecart(<!--{$compteur}-->);" <!--{$readonly}-->>
						</div>
						<div class="col-2 text-center">
							<input type="number" class="form-control text-center" id="resultat_<!--{$compteur}-->" name="resultat_<!--{$compteur}-->" value="<!--{$liste_articles[liste_articles].ecart}-->" readonly>
						</div>
						<div class="col-3 text-left">
							<input type="text" class="form-control text-center" id="commentaire_<!--{$compteur}-->" name="commentaire_<!--{$compteur}-->" value="<!--{$liste_articles[liste_articles].commentaire}-->" <!--{$readonly}-->>
						</div>
						
						
					</div>
					<!--{$compteur = ($compteur+1)}-->
				<!--{/section}-->
				

				<script>
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
							minDate: '<!--{$date_dernier_inventaire}-->',
							keyboardNavigation: true
					});									
										
				</script>
			</div>
			<div class="col-1"></div>
		</div>
		
		
		
		
		<div class="form-group row mr-1 float-right">
			<a href="<!--{$smarty.session.nom_table}-->_liste.php?id_admin_menu=<!--{$smarty.session.id_admin_menu_selectionne}-->" class="btn btn-lg btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Annuler" aria-pressed="true"><i class="fas fa-times-circle fa-lg"></i></a>
			<!--{if $smarty.session.droit==1}-->
				&nbsp;&nbsp;&nbsp;
				<button id="btnSubmit" type="submit" class="btn btn-lg btn-vert"><i class="fas fa-check-circle fa-lg"></i></button>
			<!--{/if}-->
		</div>		


	</form> 	

		<script>
		$('#formulaire-inventaires').keypress(function(e){
			if( e.which == 13 ){e.preventDefault();}
		});
	</script>


</body>
</html>