<!--{include file="header.tpl"}-->

	<form name="formulaire-groupes-utilisateurs" id="formulaire-groupes-utilisateurs" class="col-12 needs-validation mb-5" action="admin_utilisateurs_groupes_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<!--{$action}-->">
		<input type="hidden" id="id_utilisateur_groupe" name="id_utilisateur_groupe" value="<!--{$id_utilisateur_groupe}-->">
		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="libelle_utilisateur_groupe">Libellé:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="libelle_utilisateur_groupe" name="libelle_utilisateur_groupe" value="<!--{$libelle_utilisateur_groupe}-->" required>
				<div class="invalid-feedback">Veuillez saisir un libellé valide</div>
			</div>
		</div>

		<div class="form-group row">
			<div class="col-sm-10">
				<input type="hidden" class="form-control form-control-danger" id="ordre" name="ordre" value="<!--{$ordre}-->" required>	
			</div>
		</div>


		<div class="container-fluid">		
			<hr class="style-5 mt-5 bg-vert">
			<h2 class="text-left text-gris mb-5">Droits des utilisateurs</h2>
		</div>
		
		
		
		<div class="form-group row">
			
			<!--{section name=liste_menus loop=$liste_menus}-->
				<label class="col-sm-2 col-form-label" for="id_amin_menu_<!--{$liste_menus[liste_menus].id_admin_menu}-->"><!--{$liste_menus[liste_menus].titre_menu}--></label>
				<div class="col-sm-10">
					<select class="form-control form-control-danger" id="id_amin_menu_<!--{$liste_menus[liste_menus].id_admin_menu}-->" name="id_amin_menu_<!--{$liste_menus[liste_menus].id_admin_menu}-->">
						
						<!--{if $liste_menus[liste_menus].droit=='0'}-->
							<option value="0" selected>Aucun droit</option>
						<!--{elseif $liste_menus[liste_menus].droit=='1'}-->
							<option value="1" selected>Tous les droits</option>
						<!--{elseif $liste_menus[liste_menus].droit=='2'}-->
							<option value="1" selected>Lecture seule</option>
						<!--{/if}-->
						<option value="0">Lecture et demande de réservation</option>
						<option value="1">Tous les droits</option>
						<option value="2"></option>
					</select>
				</div>
			
			<!--{/section}-->
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
		$('#formulaire-groupes-utilisateurs').keypress(function(e){
			if( e.which == 13 ){e.preventDefault();}
		});
	</script>

	

</body>
</html>