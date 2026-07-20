<!--{include file="header.tpl"}-->



<!--{if $smarty.session.id_utilisateur_groupe<=1}-->
	<!--{$readonly = ""}--> 
<!--{else}-->
	<!--{$readonly = "readonly"}-->
<!--{/if}-->


	<form name="formulaire-utilisateurs" id="formulaire-utilisateurs" class="col-12 needs-validation mb-5" action="admin_utilisateurs_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<!--{$action}-->">
		<input type="hidden" id="id_utilisateur" name="id_utilisateur" value="<!--{$id_utilisateur}-->">
		
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
			<label class="col-sm-2 col-form-label" for="libelle_etat">Etat:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="libelle_etat" name="libelle_etat" value="<!--{$libelle_etat}-->" readonly>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="nom_utilisateur">Nom:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="nom_utilisateur" name="nom_utilisateur" value="<!--{$nom_utilisateur}-->" <!--{$readonly}--> required>
				<div class="invalid-feedback">Veuillez saisir un nom valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="prenom_utilisateur">Prénom:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="prenom_utilisateur" name="prenom_utilisateur" value="<!--{$prenom_utilisateur}-->" <!--{$readonly}-->>
				<div class="invalid-feedback">Veuillez saisir un prenom valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="telephone_utilisateur">Téléphone:</label>
			<div class="col-sm-10">
				<input type="telephone" class="form-control form-control-danger" id="telephone_utilisateur" name="telephone_utilisateur" value="<!--{$telephone_utilisateur}-->" pattern="^(?:0?)[1-9]([\s]\d\d){4}$" placeholder="ex: 00 00 00 00 00" <!--{$readonly}-->>
				<div class="invalid-feedback">Veuillez saisir un telephone valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="email_utilisateur">Email:</label>
			<div class="col-sm-10">
				<input type="email" class="form-control form-control-danger" id="email_utilisateur" name="email_utilisateur" value="<!--{$email_utilisateur}-->" <!--{$readonly}-->>
				<div class="invalid-feedback">Veuillez saisir un email valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="mdp_utilisateur">Mot de passe:</label>
			<div class="col-sm-10">
				<input type="password" class="form-control form-control-danger" id="mdp_utilisateur" name="mdp_utilisateur" value="<!--{$mdp_utilisateur}-->" <!--{$readonly}--> >
				<div class="invalid-feedback">Veuillez saisir un mot de passe valide</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="groupe_utilisateur">Groupe</label>
			<div class="col-sm-10">
				<select class="form-control form-control-danger" id="id_utilisateur_groupe" name="id_utilisateur_groupe" <!--{$readonly}-->>
					
					<!--{if $id_utilisateur_groupe!=''}-->
						<option value="<!--{$id_utilisateur_groupe}-->" selected><!--{$libelle_utilisateur_groupe}--></option>
					<!--{/if}-->
					<!--{section name=liste_groupes loop=$liste_groupes}-->
						<option value="<!--{$liste_groupes[liste_groupes].id_utilisateur_groupe}-->"><!--{$liste_groupes[liste_groupes].libelle_utilisateur_groupe}--></option>
					<!--{/section}-->

				</select>
			</div>
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
		$('#formulaire-utilisateurs').keypress(function(e){
			if( e.which == 13 ){e.preventDefault();}
		});
	</script>
	

</body>
</html>