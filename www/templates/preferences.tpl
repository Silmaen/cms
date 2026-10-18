<!--{include file="header.tpl"}-->

	<form name="formulaire-preferences" id="formulaire-preferences" class="col-12 mb-5" action="preferences.php" method="POST">
		<input type="hidden" id="action" name="action" value="valider">

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="theme">Thème :</label>
			<div class="col-sm-4">
				<select class="form-control" id="theme" name="theme">
					<!--{foreach $liste_themes as $valeur => $libelle}-->
						<option value="<!--{$valeur}-->"<!--{if $valeur==$theme}--> selected<!--{/if}-->><!--{$libelle}--></option>
					<!--{/foreach}-->
				</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="items_par_page">Lignes par page :</label>
			<div class="col-sm-4">
				<select class="form-control" id="items_par_page" name="items_par_page">
					<!--{foreach $liste_items_par_page as $valeur => $libelle}-->
						<option value="<!--{$valeur}-->"<!--{if $valeur==$items_par_page}--> selected<!--{/if}-->><!--{$libelle}--></option>
					<!--{/foreach}-->
				</select>
				<small class="form-text text-muted">Appliqué à toutes les listes ; chaque liste peut ensuite être réglée par son propre sélecteur.</small>
			</div>
		</div>

		<div class="form-group row mr-1 float-right">
			<a href="accueil.php" class="btn btn-lg btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Annuler" aria-pressed="true"><i class="fas fa-times-circle fa-lg"></i></a>
			&nbsp;&nbsp;&nbsp;
			<button id="btnSubmit" type="submit" class="btn btn-lg btn-vert" title="Enregistrer"><i class="fas fa-check-circle fa-lg"></i></button>
		</div>

	</form>

</body>
</html>
