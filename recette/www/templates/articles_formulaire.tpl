<!--{include file="header.tpl"}-->
<!--{include file="header.tpl"}-->


						
<!--{if $smarty.session.id_utilisateur_groupe<=2}-->
	<!--{$readonly = ""}-->
<!--{else}-->
	<!--{$readonly = "readonly"}-->
<!--{/if}-->



	<form name="formulaire-articles" id="formulaire-articles" class="col-12 needs-validation mb-5" action="articles_formulaire.php" method="POST">
		<input type="hidden" id="action" name="action" value="<!--{$action}-->">
		<input type="hidden" id="id_article" name="id_article" value="<!--{$id_article}-->">
		
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
			<label class="col-sm-2 col-form-label text-right" for="ordre_article">Ordre:</label>
			<div class="col-sm-2">
				<input type="text" class="form-control form-control-danger" id="ordre_article" name="ordre_article" value="<!--{$ordre_article}-->" >
				<div class="invalid-feedback">Veuillez saisir un ordre valide</div>
			</div>
		</div>

		
		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="designation">Désignation:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control form-control-danger" id="designation" name="designation" value="<!--{$designation}-->" <!--{$readonly}--> required>
				<div class="invalid-feedback">Veuillez saisir une désignation valide</div>
			</div>
		</div>


		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="commentaire">Commentaire:</label>
			<div class="col-sm-10">
				<textarea class="form-control form-control-danger" id="commentaire" name="commentaire" rows="2" <!--{$readonly}-->><!--{$commentaire}--></textarea>
				<div class="invalid-feedback">Veuillez saisir un commentaire valide</div>
			</div>
		</div>
	
	
		<div class="form-group row mr-1 float-right">
			<a href="<!--{$smarty.session.nom_table}-->_liste.php?id_admin_menu=<!--{$smarty.session.id_admin_menu_selectionne}-->" class="btn btn-lg btn-danger" role="button" data-toggle="tooltip" data-placement="bottom" title="Annuler" aria-pressed="true"><i class="fas fa-times-circle fa-lg"></i></a>
			<!--{if $smarty.session.droit==1}-->
				&nbsp;&nbsp;&nbsp;
				<button id="btnSubmit" type="submit" class="btn btn-lg btn-vert"><i class="fas fa-check-circle fa-lg"></i></button>
			<!--{/if}-->
		</div>		
	
		<!--{if $id_article!=0 && $smarty.session.droit==1}-->
		
		

			<div class="container-fluid">		
				<hr class="style-5 mt-5 bg-vert">
				<h2 class="text-left text-gris mb-5">Images et fichiers joints</h2>
			</div>
			
			
			<label for="inputfile">Utilisez cet espace pour gérer vos images et pièces jointes</label>
			<div class="file-loading">
				<input id="inputfile" name="inputfile[]" type="file" multiple>
			</div>


							
					
			<script>

				<!--{section name=liste_initialPreviewImages loop=$liste_initialPreviewImages}-->
					<!--{if $smarty.section.liste_initialPreviewImages.first}-->var <!--{/if}-->
						url<!--{$liste_initialPreviewImages[liste_initialPreviewImages].ordre}-->='fichiers/<!--{$liste_initialPreviewImages[liste_initialPreviewImages].nom_fichier}-->.<!--{$liste_initialPreviewImages[liste_initialPreviewImages].extension}-->'
					<!--{if $smarty.section.liste_initialPreviewImages.last}-->;<!--{else}-->,<!--{/if}-->
				<!--{/section}-->
					
					
				$("#inputfile").fileinput({
					language: 'fr',
					theme: 'fas',
					uploadUrl: 'fichier_ajouter.php',
					<!--{section name=liste_initialPreviewImages loop=$liste_initialPreviewImages}-->
						<!--{if $smarty.section.liste_initialPreviewImages.first}-->initialPreview: [<!--{/if}-->
							url<!--{$liste_initialPreviewImages[liste_initialPreviewImages].ordre}-->
						<!--{if $smarty.section.liste_initialPreviewImages.last}-->],<!--{else}-->,<!--{/if}-->
					<!--{/section}-->		

					initialPreviewAsData: true,

					<!--{section name=liste_initialPreviewImages loop=$liste_initialPreviewImages}-->
						<!--{if $smarty.section.liste_initialPreviewImages.first}-->initialPreviewConfig: [<!--{/if}-->
							{caption: '<!--{$liste_initialPreviewImages[liste_initialPreviewImages].nom_fichier}-->.<!--{$liste_initialPreviewImages[liste_initialPreviewImages].extension}-->', downloadUrl: url<!--{$liste_initialPreviewImages[liste_initialPreviewImages].ordre}-->, size: <!--{$liste_initialPreviewImages[liste_initialPreviewImages].poids}-->, width: '<!--{$liste_initialPreviewImages[liste_initialPreviewImages].largeur}-->px', key: <!--{$liste_initialPreviewImages[liste_initialPreviewImages].id_fichier}-->}
						<!--{if $smarty.section.liste_initialPreviewImages.last}-->],<!--{else}-->,<!--{/if}-->
					<!--{/section}-->
					browseClass: 'btn btn-vert',
					removeClass: 'btn btn-danger',
					uploadClass: 'btn btn-primary',		
					deleteUrl: 'fichier_supprimer.php',
					overwriteInitial: false,
					maxFileSize: 10000,
					uploadExtraData: {
						id_menu: '<!--{$smarty.session.id_admin_menu_selectionne}-->',
						id_parent: '<!--{$id_article}-->'			 
					},
					initialCaption: 'Cliquer sur parcourir pour ajouter vos fichiers',
					
					preferIconicPreview: true, // this will force thumbnails to display icons for following file extensions
					previewFileIconSettings: { // configure your icon file extensions
						'doc': '<i class="fa fa-file-word-o text-primary"></i>',
						'xls': '<i class="fa fa-file-excel-o text-success"></i>',
						'ppt': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
						'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
						'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
						'htm': '<i class="fa fa-file-code-o text-info"></i>',
						'txt': '<i class="fa fa-file-text-o text-info"></i>',
						'mov': '<i class="fa fa-file-movie-o text-warning"></i>',
						'mp3': '<i class="fa fa-file-audio-o text-warning"></i>',
					},
					previewFileExtSettings: { // configure the logic for determining icon file extensions
						'doc': function(ext) {return ext.match(/(doc|docx)$/i);},
						'xls': function(ext) {return ext.match(/(xls|xlsx)$/i);},
						'ppt': function(ext) {return ext.match(/(ppt|pptx)$/i);},
						'zip': function(ext) {return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);},
						'htm': function(ext) {return ext.match(/(htm|html)$/i);},
						'txt': function(ext) {return ext.match(/(txt|ini|csv|java|php|js|css)$/i);},
						'mov': function(ext) {return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);},
						'mp3': function(ext) {return ext.match(/(mp3|wav)$/i);}
					}		
				});	
					
			</script>		
				
		<!--{/if}-->		
	</form> 	
	<script>
		$('#formulaire-articles').keypress(function(e){
			if( e.which == 13 ){e.preventDefault();}
		});
	</script>
	

</body>
</html>