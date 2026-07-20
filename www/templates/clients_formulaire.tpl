<!--{include file="header.tpl"}-->

<!--{if $smarty.session.id_utilisateur_groupe<=2}-->
	<!--{$readonly = ""}-->
	<!--{$readonly_adhesion = ''}-->
<!--{else}-->
	<!--{$readonly = "readonly"}-->
	<!--{$readonly_adhesion ='readonly'}-->
<!--{/if}-->


	<form name="formulaire-clients" id="formulaire-clients" class="col-12 needs-validation mb-5" action="clients_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<!--{$action}-->">
		<input type="hidden" id="id_client" name="id_client" value="<!--{$id_client}-->">
		
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
			<label class="col-sm-2 col-form-label" for="libelle_etat">Etat:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="libelle_etat" name="libelle_etat" value="<!--{$libelle_etat}-->" readonly>
			</div>
			<label class="col-sm-2 col-form-label text-left" for="id_statut_client">Statut:</label>
			<div class="col-sm-2">
				<select class="form-control form-control-danger" id="id_statut_client" name="id_statut_client" <!--{$readonly}-->>
					
					<!--{if $id_statut_client!=''}-->
						<option value="<!--{$id_statut_client}-->" selected><!--{$libelle_statut}--></option>
					<!--{/if}-->
					<!--{section name=liste_statuts loop=$liste_statuts}-->
						<option value="<!--{$liste_statuts[liste_statuts].id_statut_client}-->"><!--{$liste_statuts[liste_statuts].libelle_statut}--></option>
					<!--{/section}-->
				</select>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="nom">Association / Société :</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="association" name="association" value="<!--{$association}-->" <!--{$readonly}-->>
				<div class="invalid-feedback">Veuillez saisir une association valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="nom">Nom:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="nom" name="nom" value="<!--{$nom}-->" <!--{$readonly}--> required>
				<div class="invalid-feedback">Veuillez saisir un nom valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="prenom">Prénom:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="prenom" name="prenom" value="<!--{$prenom}-->" <!--{$readonly}-->>
				<div class="invalid-feedback">Veuillez saisir un prenom valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="adresse1">Adresse:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="adresse1" name="adresse1" value="<!--{$adresse1}-->" <!--{$readonly}-->>
				<input type="text" class="form-control form-control-danger" id="adresse2" name="adresse2" value="<!--{$adresse2}-->" <!--{$readonly}-->>
				<input type="text" class="form-control form-control-danger" id="adresse3" name="adresse3" value="<!--{$adresse3}-->" <!--{$readonly}-->>
				<div class="invalid-feedback">Veuillez saisir une adresse valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="cp">CP:</label>
			<div class="col-sm-4">
				<input type="text" class="form-control form-control-danger" id="cp" name="cp" value="<!--{$cp}-->" <!--{$readonly}--> required>
				<div class="invalid-feedback">Veuillez saisir un cp valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="ville">Ville:</label>
			<div class="col-sm-4">
				<input type="text" class="form-control form-control-danger" id="ville" name="ville" value="<!--{$ville}-->" <!--{$readonly}--> required>
			<div class="invalid-feedback">Veuillez saisir une ville valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="telephone">Téléphone:</label>
			<div class="col-sm-4">
				<input type="tel" class="form-control form-control-danger" id="telephone" name="telephone" value="<!--{$telephone}-->" pattern="^(?:0?)[1-9]([\s]\d\d){4}$" placeholder="ex: 00 00 00 00 00" <!--{$readonly}--> >
		
				<div class="invalid-feedback">Veuillez saisir un telephone valide</div>
			</div>
			<label class="col-sm-2 col-form-label text-right" for="email">Email:</label>
			<div class="col-sm-4">
				<input type="email" class="form-control form-control-danger" id="email" name="email" value="<!--{$email}-->" <!--{$readonly}-->>
				<div class="invalid-feedback">Veuillez saisir un email valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="commentaire">Commentaire:</label>
			<div class="col-sm-10">
				<textarea class="form-control form-control-danger" id="commentaire=" name="commentaire" rows="1" maxlength="120"  <!--{$readonly}-->><!--{$commentaire}--></textarea>
				<div class="invalid-feedback">Veuillez saisir un commentaire valide</div>
			</div>
		</div>
		
		
		
		
		
		
		
		
		<div class="container-fluid">		
			<hr class="style-5 mt-1 bg-vert">
			<h2 class="text-left text-gris mb-3">Historique des réglements</h2>
		</div>
		
		
		<div class="form-group row">
			<div class="col-4"></div>

			<div class="col-4" id="tableau">
				<div class="row bg-gris text-white">
					<h5 class="col-4 text-left">&nbsp;Année</h5>
					<h5 class="col-4 text-center">Adhésion €</h5>
					<h5 class="col-4 text-center">Dons €</h5>
				</div>
				
				<!--{$compteur=0}-->
				
				<!--{section name=liste_adhesions loop=$liste_adhesions}-->
				
					<!--{if ($compteur % 2 == 0)}-->
						<!--{$couleur_fond='bg-light'}-->
					<!--{else}-->
						<!--{$couleur_fond='bg-grey'}-->
					<!--{/if}-->
					
					
					
				
					<div id="ligne_<!--{$compteur}-->" name="ligne_<!--{$compteur}-->" class="row <!--{$couleur_fond}-->">
						<div class="col-4 text-left">
							<label class="col-form-label" for="montant_<!--{$compteur}-->"><!--{$liste_adhesions[liste_adhesions].annee}--></label>
						</div>
						<div class="col-4 text-center">
							<input type="hidden" id="id_adhesion_<!--{$compteur}-->" name="id_adhesion_<!--{$compteur}-->" value="<!--{$liste_adhesions[liste_adhesions].id_adhesion}-->" >
							<input type="hidden" id="annee_<!--{$compteur}-->" name="annee_<!--{$compteur}-->" value="<!--{$liste_adhesions[liste_adhesions].annee}-->" >

							<input type="number" class="form-control text-center" id="montant_<!--{$compteur}-->" name="montant_<!--{$compteur}-->" value="<!--{$liste_adhesions[liste_adhesions].montant}-->" <!--{$readonly_adhesion}-->>
						</div>
						<div class="col-4 text-center">
						
						<!--{if $smarty.session.id_utilisateur_groupe==1}-->
							<input type="number" class="form-control text-center" id="don_<!--{$compteur}-->" name="don_<!--{$compteur}-->" value="<!--{$liste_adhesions[liste_adhesions].don}-->" readonly>			
						<!--{else}-->
							<input type="number" class="form-control text-center" id="don_<!--{$compteur}-->" name="don_<!--{$compteur}-->" value="" readonly>
						<!--{/if}-->	
							
						</div>
					</div>
					<!--{$compteur = ($compteur+1)}-->
					<!--{$readonly_adhesion = "readonly"}-->

				<!--{/section}-->
			</div>
			<div class="col-4"></div>

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
		$('#formulaire-clients').keypress(function(e){
			if( e.which == 13 ){e.preventDefault();}
		});
	</script>

</body>
</html>