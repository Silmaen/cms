<!--{include file="header.tpl"}-->

<!--{if $smarty.session.id_utilisateur_groupe<=2}-->
	<!--{$readonly = ""}-->
	<!--{$readonly_adhesion = ''}-->
<!--{else}-->
	<!--{$readonly = "readonly"}-->
	<!--{$readonly_adhesion ='readonly'}-->
<!--{/if}-->


<!--{if $action=='modifier-valider'}-->
	<!--{$readonly_date_depart = "readonly"}-->
	<!--{$readonly_date_retour = "readonly"}-->
<!--{elseif $action=='copier-valider'}-->
	<!--{$readonly_date_depart = ""}-->
	<!--{$readonly_date_retour = "readonly"}-->
<!--{elseif $action=='ajouter-valider'}-->
	<!--{$readonly_date_depart = ""}-->
	<!--{$readonly_date_retour = "readonly"}-->
<!--{else}-->
	<!--{$readonly_date_depart = ""}-->
	<!--{$readonly_date_retour ="readonly"}-->
<!--{/if}-->



	<form name="formulaire-reservations" id="formulaire-reservations" class="col-12 needs-validation mb-5" action="reservations_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<!--{$action}-->">
		<input type="hidden" id="id_reservation" name="id_reservation" value="<!--{$id_reservation}-->">
		<input type="hidden" id="envoyer" name="envoyer" value="non">
		
		<!--{if $action=='copier-valider'}-->
			<div class="form-group row ">
				<h4 class="font-weight-bold text-center text-danger">Réservation créée. Date de départ à modifier. Valider et rouvrir cette réservation pour avoir la disponibilité du matériel à la date précisée.</h4>
			</div>
		<!--{/if}-->
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="date_creation">Date de création:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_creation" name="date_creation" value="<!--{$date_creation}-->" readonly>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_modification">Date de modification:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_modification" name="date_modification" value="<!--{$date_modification}-->" readonly>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="modifie_par">Par:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="modifie_par" name="modifie_par" value="<!--{$modifie_par}-->" readonly>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="id_client_association">Association:</label>
			<div class="col-sm-2">
				<!--{if $date_retour_temp>=$aujourdhui_temp}-->	
					<select class="form-control form-control-danger" id="id_client_association" name="id_client_association" <!--{$readonly}--> required>
						<!--{if $id_client!='0'}-->
							<option value="<!--{$id_client}-->" selected><!--{$libelle_association}--></option>
						<!--{else}-->
							<option value="" selected></option>
						<!--{/if}-->
						<!--{section name=liste_associations loop=$liste_associations}-->
							<option value="<!--{$liste_associations[liste_associations].id_client}-->"><!--{$liste_associations[liste_associations].libelle}--></option>
						<!--{/section}-->
					</select>
					
				<!--{else}-->
					<select class="form-control form-control-danger" id="id_client" name="id_client" readonly required>
						<option value="<!--{$id_client}-->" selected><!--{$libelle}--></option>
					</select>
				<!--{/if}-->				
			</div>			

			<label class="col-sm-2 col-form-label text-right" for="id_client">Adhérent:</label>
			<div class="col-sm-2">
				<!--{if $date_retour_temp>=$aujourdhui_temp}-->	
					<select class="form-control form-control-danger" id="id_client" name="id_client" <!--{$readonly}--> required>
						<!--{if $id_client!='0'}-->
							<option value="<!--{$id_client}-->" selected><!--{$libelle}--></option>
						<!--{else}-->
							<option value="" selected></option>
						<!--{/if}-->
						<!--{section name=liste_clients loop=$liste_clients}-->
							<option value="<!--{$liste_clients[liste_clients].id_client}-->"><!--{$liste_clients[liste_clients].libelle}--></option>
						<!--{/section}-->
					</select>
					
				<!--{else}-->
					<select class="form-control form-control-danger" id="id_client" name="id_client" readonly required>
						<option value="<!--{$id_client}-->" selected><!--{$libelle}--></option>
					</select>
				<!--{/if}-->				
			</div>			
			
			<label class="col-sm-2 col-form-label text-right" for="libelle_etat">Etat:</label>
			<div class="col-sm-2">
				<input type="hidden" id="id_etat" name="id_etat" value="<!--{$id_etat}-->">
				<input type="text" class="form-control form-control-danger" id="libelle_etat" name="libelle_etat" value="<!--{$libelle_etat}-->" readonly>
			</div>
			
		</div>



		<div class="form-group row">
			<label class="col-sm-2 col-form-label text-right" for=""></label>
			<div class="col-sm-2">
				&nbsp;
			</div>
			
			<label class="col-sm-2 col-form-label text-right" for=""></label>
			<div class="col-sm-2">
				&nbsp;
			</div>
			<!--{if $smarty.session.id_utilisateur_groupe==1}-->
				<label class="col-sm-2 col-form-label text-right for="don">Don:</label>
				<div class="col-sm-2">
					<input type="number" class="form-control form-control-danger" id="don" name="don" value="<!--{$don}-->" <!--{$readonly}-->>
					<div class="invalid-feedback">Veuillez saisir un don valide</div>
				</div>
					
			<!--{else}-->
					
				<label class="col-sm-2 col-form-label text-right" for="don">&nbsp;</label>
				<div class="col-sm-2">
					<input type="hidden" class="form-control form-control-danger" id="don" name="don" value="<!--{$don}-->" <!--{$readonly}-->>
					<div class="invalid-feedback">Veuillez saisir un don valide</div>
				</div>
				
			<!--{/if}-->

		</div>









		<div class="form-group row">
			<label class="col-sm-2 col-form-label text-left" for="date_depart">Date de départ:</label>
		<!--{if $action!='ajouter-valider'}-->
			<div class="col-sm-2">
				<!--{if $date_retour_temp>=$aujourdhui_temp}-->	
					<input type="text" class="form-control form-control-danger" id="date_depart" name="date_depart" value="<!--{$date_depart}-->" <!--{$readonly_date_depart}--> required>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			<!--{else}-->
				<input type="text" class="form-control form-control-danger" id="date_depart" name="date_depart" value="<!--{$date_depart}-->" <!--{$readonly_date_depart}--> required>
			<!--{/if}-->
			
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_retour">Date de retour:</label>
			<div class="col-sm-2">
				<!--{if $date_retour_temp>=$aujourdhui_temp}-->	
					<input type="text" class="form-control form-control-danger" id="date_retour" name="date_retour" value="<!--{$date_retour}-->" <!--{$readonly_date_retour}--> required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<!--{else}-->
					<input type="text" class="form-control form-control-danger" id="date_retour" name="date_retour" value="<!--{$date_retour}-->" <!--{$readonly_date_retour}--> required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<!--{/if}-->
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_don">Date du don:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_don" name="date_don" value="<!--{$date_don}-->" required>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>

		<!--{else}-->
			<div class="col-sm-2">
				<!--{if $date_retour_temp>=$aujourdhui_temp}-->	
					<input type="text" class="form-control form-control-danger" id="date_depart" name="date_depart" value="<!--{$date_depart}-->" <!--{$readonly}--> required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<!--{else}-->
					<input type="text" class="form-control form-control-danger" id="date_depart" name="date_depart" value="<!--{$date_depart}-->" readonly required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<!--{/if}-->
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_retour">Date de retour:</label>
			<div class="col-sm-2">
				<!--{if $date_retour_temp>=$aujourdhui_temp}-->	
					<input type="text" class="form-control form-control-danger" id="date_retour" name="date_retour" value="<!--{$date_retour}-->" <!--{$readonly_date_retour}--> required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<!--{else}-->
					<input type="text" class="form-control form-control-danger" id="date_retour" name="date_retour" value="<!--{$date_retour}-->" <!--{$readonly_date_retour}--> required>
					<div class="invalid-feedback">Veuillez saisir une date valide</div>
				<!--{/if}-->
			</div>
			<label class="col-sm-2 col-form-label text-right" for="date_don">Date du don:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="date_don" name="date_don" value="<!--{$date_don}-->" required>
				<div class="invalid-feedback">Veuillez saisir une date valide</div>
			</div>

		<!--{/if}-->
		</div>





















		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="commentaire">Commentaire:</label>
			<div class="col-sm-6">
				<textarea class="form-control form-control-danger" id="commentaire" name="commentaire" rows="1" maxlength="280" <!--{$readonly}--> placeholder="280 caractères max." ><!--{$commentaire}--></textarea>
				<div class="invalid-feedback">Veuillez saisir un commentaire valide</div>
			</div>
			
			<label class="col-sm-2 col-form-label text-right" for="id_statut_reservation">Statut:</label>
			<div class="col-sm-2">
				<!--{if $date_retour_temp>=$aujourdhui_temp}-->	
					<select class="form-control form-control-danger" id="id_statut_reservation" name="id_statut_reservation" <!--{$readonly}-->>
					<!--{if $id_statut_reservation!=''}-->
						<option value="<!--{$id_statut_reservation}-->" selected><!--{$libelle_statut}--></option>
					<!--{/if}-->
					<!--{section name=liste_statuts loop=$liste_statuts}-->
						<option value="<!--{$liste_statuts[liste_statuts].id_statut_reservation}-->"><!--{$liste_statuts[liste_statuts].libelle_statut}--></option>
					<!--{/section}-->
					</select>

				<!--{else}-->	
					<select class="form-control form-control-danger" id="id_statut_reservation" name="id_statut_reservation" readonly>
						<option value="<!--{$id_statut_reservation}-->" selected><!--{$libelle_statut}--></option>
					</select>
				<!--{/if}-->				
			</div>
			
			
		</div>
	






	<!--{if $action!='ajouter-valider'}-->
		

		<div class="container-fluid">		
			<hr class="style-5 bg-vert">
			<h2 class="text-left text-gris mb-3">Informations Adhérent</h2>
		</div>
	

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="association_client">Association:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="association_client" name="association_client" value="<!--{$association_client}-->" readonly>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="nom_client">Nom:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="nom_client" name="nom_client" value="<!--{$nom_client}-->" readonly>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="prenom_client">Prénom:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="prenom_client" name="prenom_client" value="<!--{$prenom_client}-->" readonly>
			</div>		
		</div>


		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="adresse1_client">Adresse:</label>
			<div class="col-sm-6">
				<input type="text" class="form-control form-control-danger" id="adresse1_client" name="adresse1_client" value="<!--{$adresse1_client}-->" readonly >
			</div>
		
			<label class="col-sm-2 col-form-label text-right" for="cp_client">CP:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="cp_client" name="cp_client" value="<!--{$cp_client}-->" readonly>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="adresse2_client">&nbsp;</label>
			<div class="col-sm-6">
				<input type="text" class="form-control form-control-danger" id="adresse2_client" name="adresse2_client" value="<!--{$adresse2_client}-->" readonly >		
			</div>	
			<label class="col-sm-2 col-form-label text-right" for="ville_client">Ville:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="ville_client" name="ville_client" value="<!--{$ville_client}-->" readonly>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="adresse3_client">&nbsp;</label>
			<div class="col-sm-6">
				<input type="text" class="form-control form-control-danger" id="adresse3_client" name="adresse3_client" value="<!--{$adresse3_client}-->" readonly >
			</div>	
			<label class="col-sm-2 col-form-label text-right" for="adhesion_client">Adhésion:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="adhesion_client" name="adhesion_client" value="<!--{$adhesion_client}-->" readonly>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="telephone_client">Téléphone:</label>
			<div class="col-sm-2">
				<input type="tel" class="form-control form-control-danger" id="telephone_client" name="telephone_client" value="<!--{$telephone_client}-->" readonly >
			</div>
			<label class="col-sm-2 col-form-label text-right" for="email_client">Email:</label>
			<div class="col-sm-2">
				<input type="email" class="form-control form-control-danger" id="email_client" name="email_client" value="<!--{$email_client}-->" readonly>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="libelle_statut_client">Statut adhérent:</label>
			<div class="col-sm-2">
				<input type="email" class="form-control form-control-danger" id="libelle_statut_client" name="libelle_statut_client" value="<!--{$libelle_statut_client}-->" readonly>
			</div>		
		</div>





		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="commentaire_client">Commentaire:</label>
			<div class="col-sm-10">
				<textarea class="form-control form-control-danger" id="commentaire_client" name="commentaire_client" rows="1" maxlength="280" placeholder="280 caractères max." readonly><!--{$commentaire_client}--></textarea>
				<div class="invalid-feedback">Veuillez saisir un commentaire valide</div>
			</div>
		</div>


		<hr class="style-5 bg-vert">

	
		<div class="form-group row">		
			<div class="col-9">
				<h2 class="text-left text-gris mb-3">Liste des articles</h2>
			</div>		
			<div class="col-3 text-right">
			
				<!--{if $action=='modifier-valider-rc'}-->
					<a href="reservations_formulaire.php?action=modifier&id_reservation=<!--{$id_reservation}-->" class="btn btn-lg btn-secondary" role="button" data-toggle="tooltip" data-placement="bottom" title="Passer en réservation normale" aria-pressed="true"><b>RC</b></a>
				<!--{else}-->			
					<a href="reservations_formulaire.php?action=modifier-rc&id_reservation=<!--{$id_reservation}-->" class="btn btn-lg btn-vert" role="button" data-toggle="tooltip" data-placement="bottom" title="Activer la Réservation Classique" aria-pressed="true"><b>RC</b></a>
				<!--{/if}-->
			
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="<!--{$smarty.session.nom_table}-->_liste.php?id_admin_menu=<!--{$smarty.session.id_admin_menu_selectionne}-->" class="btn btn-lg btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Annuler" aria-pressed="true"><i class="fas fa-times-circle fa-lg"></i></a>
					<!--{if $smarty.session.droit==1}-->
						&nbsp;&nbsp;&nbsp;
						<button id="btnSubmit" type="submit" class="btn btn-lg btn-vert"><i class="fas fa-check-circle fa-lg"></i></button>
						
						<!--{if $email_client!=''}-->
							&nbsp;&nbsp;&nbsp;
							<a href="" class="btn btn-info btn-lg" role="button" data-toggle="tooltip" data-placement="bottom" title="11 Valider et Envoyer la confirmation de la réservation" aria-pressed="true" onclick="Envoyer();"><i class="fas fa-envelope"></i></a>

						<!--{/if}-->
						
						
						
					<!--{/if}-->
					

			</div>

			
		</div>
	
		<div class="form-group row">

			<div class="col-12" id="tableau">
				<div class="row bg-gris text-white">
					<h5 class="col-4 text-left">&nbsp;Article</h5>
					<h5 class="col-2 text-center">Qté Inventaire</h5>
					<h5 class="col-2 text-center">Qté Dispo Avant</h5>
					<h5 class="col-2 text-center">Qté Réservée</h5>
					<h5 class="col-2 text-center">Qté Dispo Après</h5>
				</div>
				
				<!--{$compteur=0}-->
				<!--{$i=0}-->
				<!--{section name=liste_articles loop=$liste_articles}-->
				
					<!--{if $liste_articles[liste_articles].premier_article==1}-->
						<hr class="style-5 bg-vert">

					<!--{/if}-->		
			
			
			
				<!--{if $liste_articles[liste_articles].quantite_reservee>0}-->
					<!--{if $liste_articles[liste_articles].quantite_reservee>$liste_articles[liste_articles].quantite_actuelle}-->	
						<!--{$couleur_fond='bg-danger'}-->
					<!--{else}-->
						<!--{$couleur_fond='bg-vert'}-->
					<!--{/if}-->
					
				<!--{else if $i==0}-->
					<!--{$couleur_fond='bg-light'}-->
					<!--{$i=1}-->
				<!--{else}-->
					<!--{$couleur_fond='bg-grey'}-->
					<!--{$i=0}-->
				<!--{/if}-->
				
				<!--{if $liste_articles[liste_articles].id_etat_article=='1'}-->
					<!--{$picto_etat='<i class="fa fa-circle fa-xs text-success" aria-hidden="true"></i>'}-->
				<!--{elseif $liste_articles[liste_articles].id_etat_article=='2'}-->	
					<!--{$picto_etat='<i class="fa fa-circle fa-xs text-warning" aria-hidden="true"></i>'}-->
				<!--{else}-->
					<!--{$picto_etat='<i class="fa fa-circle fa-xs text-danger" aria-hidden="true"></i>'}-->
				<!--{/if}-->

				
	
				

			
			
							
				
					<div id="ligne_<!--{$compteur}-->" name="ligne_<!--{$compteur}-->" class="row <!--{$couleur_fond}-->">
						<div class="col-4 text-left">
							<label class="col-form-label" for="qtetotale_<!--{$compteur}-->">&nbsp;<!--{$picto_etat}--> <!--{$liste_articles[liste_articles].designation}--></label>
							
							<!--{if $liste_articles[liste_articles].nom_fichier!=''}-->	
								<a href="fichiers/<!--{$liste_articles[liste_articles].nom_fichier}-->.<!--{$liste_articles[liste_articles].extension}-->" target="_blank" class="btn text-dark mr-3" role="button" aria-hidden="true"><i class="fa fa-file-image-o" ></i></a>
							<!--{/if}-->
							
						</div>
						<div class="col-2 text-center">
							<input type="hidden" id="id_article_<!--{$compteur}-->" name="id_article_<!--{$compteur}-->" value="<!--{$liste_articles[liste_articles].id_article}-->" >

						<!--{if $date_retour_temp>=$aujourdhui_temp}-->	
							<input type="number" class="form-control text-center" id="qtetotale_<!--{$compteur}-->" name="qtetotale_<!--{$compteur}-->" value="<!--{$liste_articles[liste_articles].quantite_totale}-->" readonly>
						<!--{else}-->
							<input type="number" class="form-control text-center" id="qtetotale_<!--{$compteur}-->" name="qtetotale_<!--{$compteur}-->" value="" readonly>
						<!--{/if}-->

						
						</div>
						<div class="col-2 text-center">
						<!--{if $date_retour_temp>=$aujourdhui_temp}-->	
							<input type="number" class="form-control text-center" id="qtedispoavant_<!--{$compteur}-->" name="qtedispoavant_<!--{$compteur}-->" value="<!--{$liste_articles[liste_articles].quantite_actuelle}-->" readonly>
						<!--{else}-->
							<input type="number" class="form-control text-center" id="qtedispoavant_<!--{$compteur}-->" name="qtedispoavant_<!--{$compteur}-->" value="" readonly>
						<!--{/if}-->

						</div>
						<div class="col-2 text-center">
						<!--{if $date_retour_temp>=$aujourdhui_temp}-->	
							<input type="number" class="form-control text-center" id="qtereservee_<!--{$compteur}-->" name="qtereservee_<!--{$compteur}-->" value="<!--{$liste_articles[liste_articles].quantite_reservee}-->" onChange="Soustraction(<!--{$compteur}-->);" <!--{$readonly}-->>
						<!--{else}-->
							<input type="number" class="form-control text-center" id="qtereservee_<!--{$compteur}-->" name="qtereservee_<!--{$compteur}-->" value="<!--{$liste_articles[liste_articles].quantite_reservee}-->" onChange="Soustraction(<!--{$compteur}-->);" readonly>
						<!--{/if}-->
						</div>
						<div class="col-2 text-center">
						<!--{if $date_retour_temp>=$aujourdhui_temp}-->	
							<input type="number" class="form-control text-center" id="resultat_<!--{$compteur}-->" name="resultat_<!--{$compteur}-->" value="<!--{$liste_articles[liste_articles].quantite_apres}-->" readonly>
						<!--{else}-->
							<input type="number" class="form-control text-center" id="resultat_<!--{$compteur}-->" name="resultat_<!--{$compteur}-->" value="" readonly>
						<!--{/if}-->
						</div>
					</div>
					
					
					
					<script>
					
						$("#ligne_"+<!--{$compteur}-->).hover(function() {	

							if($(this).hasClass('<!--{$couleur_fond}-->'))
							{
								$(this).removeClass('<!--{$couleur_fond}--> text-dark');
								$(this).addClass('bg-secondary text-white');
							}
							else{
								$(this).removeClass('bg-secondary text-white');
								$(this).addClass('<!--{$couleur_fond}--> text-dark');	
							}
						});
					</script>	
					
					<!--{$compteur = ($compteur+1)}-->
				<!--{/section}-->
				

		<!--{/if}-->
				<script>
				

					

					function Envoyer()
					{
						$('#envoyer').val('oui');	
							
						$('#formulaire-reservations').submit().delay(3000);

						alert('Envoie un email.');
						//$("#formulaire-reservations").submit();						
					}

					
				
				
				
					function Soustraction(numero) 
					{ 
						var dispoavant = parseInt(document.getElementById("qtedispoavant_"+numero).value); 
						var reserve = parseInt(document.getElementById("qtereservee_"+numero).value); 
					
						if(dispoavant == reserve)
						{
							document.getElementById("resultat_"+numero).value = '0';
						}
						else if(reserve<0)
						{
							alert("Vous ne pouvez réserver une valeur < 0");
							document.getElementById("qtereservee_"+numero).value = 0;
							document.getElementById("resultat_"+numero).value = '0';
						}
						else if(dispoavant != "" && reserve != "" && dispoavant>=reserve) 
						{ 		   
						   var resultat_ = parseFloat(dispoavant) - parseFloat(reserve);
						   document.getElementById("resultat_"+numero).value = resultat_.toFixed(2);
						} 
						else if(dispoavant<reserve)
						{
							document.getElementById("qtereservee_"+numero).value = dispoavant;
							document.getElementById("resultat_"+numero).value = '0';
							alert("Vous ne pouvez réserver plus que le stock Dispo Avant");
						}
						else 
						{
						   document.getElementById("resultat_"+numero).value = "0";
						}
						
						if(document.getElementById("qtereservee_"+numero).value !="" && document.getElementById("qtereservee_"+numero).value>0)
						{
							document.getElementById("ligne_"+numero).className = "row bg-vert text-white";
						}
						else if(document.getElementById("qtereservee_"+numero).value !="" && document.getElementById("qtereservee_"+numero).value==0)
						{
							if(numero/2 == Math.round(numero/2)) 
							{
								document.getElementById("ligne_"+numero).className = "row bg-light";
							}
							else 
							{
								document.getElementById("ligne_"+numero).className = "row bg-grey";
							}
							   
						}
							
					}
					
					
					var aujourdhui = new Date();
					var datemin = new Date();
					datemin.setDate(aujourdhui.getDate()-5);	
					var datemax = new Date();
					datemax.setDate(aujourdhui.getDate()+365);						
					
					<!--{if $action<>'modifier-valider'}-->	
					
						$('#date_depart').datepicker({ 
							uiLibrary: 'bootstrap4', 
							iconsLibrary: 'fontawesome', 
							modal: true, 
							header: true, 
							footer: true,
							locale: 'fr-fr',
							format: 'dd-mm-yyyy',
							weekStartDay: 1,
							disableDaysOfWeek: [0, 2, 3, 4, 6],
							minDate: datemin,
							maxDate: datemax,				
							keyboardNavigation: true,
						});		
	
	
						/*
						$('#date_retour').datepicker({ 
							uiLibrary: 'bootstrap4', 
							iconsLibrary: 'fontawesome', 
							modal: true, 
							header: true, 
							footer: true,
							locale: 'fr-fr',
							format: 'dd-mm-yyyy',
							weekStartDay: 1,
							disableDaysOfWeek: [0, 2, 3, 4, 6],
							minDate: datemin,
							maxDate: (datemax),				
							keyboardNavigation: true,
						});		
						*/
						
						$('#date_don').datepicker({ 
							uiLibrary: 'bootstrap4', 
							iconsLibrary: 'fontawesome', 
							modal: true, 
							header: true, 
							footer: true,
							locale: 'fr-fr',
							format: 'dd-mm-yyyy',
							weekStartDay: 1,
							maxDate: (datemax),				
							keyboardNavigation: true,
						});		
					<!--{/if}-->

				</script>
			</div>			
			
		</div>
		
		
		<div class="form-group row mr-1 float-right">
			<a href="<!--{$smarty.session.nom_table}-->_liste.php?id_admin_menu=<!--{$smarty.session.id_admin_menu_selectionne}-->" class="btn btn-lg btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Annuler" aria-pressed="true"><i class="fas fa-times-circle fa-lg"></i></a>
			<!--{if $smarty.session.droit==1}-->
				&nbsp;&nbsp;&nbsp;
				<button id="btnSubmit" type="submit" class="btn btn-lg btn-vert"><i class="fas fa-check-circle fa-lg"></i></button>
				
				<!--{if $email_client!=''}-->
					&nbsp;&nbsp;&nbsp;
							
					<a href="" class="btn btn-info btn-lg" role="button" data-toggle="tooltip" data-placement="bottom" title="33 Valider et Envoyer la confirmation de la réservation" aria-pressed="true" onclick="Envoyer();"><i class="fas fa-envelope"></i></a>


				<!--{/if}-->
			<!--{/if}-->
		</div>		


		<div class="container-fluid">		
			<hr class="style-5 mt-5 bg-vert">
			<div class="text-left text-gris">
				<b>Qté Totale :</b> stock au dernier inventaire <br />
				<b>Qté Disponible Avant :</b> quantité totale - (toutes les réservations sorties jusqu'à la veille et dont le retour est au moins au lendemain) <br />
				<b>Qté Réservée :</b> quantité à réserver pour ce client <br />
				<b>Qté Disponible Après :</b> quantité totale - (toutes les réservations sorties jusqu'à la veille et dont le retour est au moins au lendemain) - (Qté réservée) <br /><br />
			</div>
		</div>

		
	</form> 		
	<script>
		
		$('#formulaire-reservations').submit(function() {
		
			var id_reservation = $('#id_reservation').val();
			var id_client = $('#id_client').val();
			var nom_client =  $("#id_client option[value="+id_client+"]").text();

			if(id_reservation==0)
			{
				var status = confirm("Confirmez-vous la réservation de "+ nom_client +" départ le "+$("#date_depart").val()+" et le retour le "+$("#date_retour").val()+" ?");
				if(status == false){
					return false;
				}
				else
				{
					return true; 
				}
			}
			else
			{
				return true; 
			}
		});
		
	
		$('#formulaire-reservations').keypress(function(e){
			if( e.which == 13 ){e.preventDefault();}
		});
	</script>

</body>
</html>