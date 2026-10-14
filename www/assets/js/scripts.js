$(document).ready(function(){
	// Evite les problèmes de focusIn entre Bootstrap et TinyMCE
	$(document).on('focusin', function(e) {
		if ($(e.target).closest(".mce-window").length) {
			e.stopImmediatePropagation();
		}
	});


	// Javascript gérant la validité des champs du formulaire
	(function() {
		'use strict';
		window.addEventListener('load', function() {
			// Fetch all the forms we want to apply custom Bootstrap validation styles to
			var forms = document.getElementsByClassName('needs-validation');
			// Loop over them and prevent submission
			var validation = Array.prototype.filter.call(forms, function(form) {
				  form.addEventListener('submit', function(event) {
						if (form.checkValidity() === false) {
						  event.preventDefault();
						  event.stopPropagation();
						}
						form.classList.add('was-validated');
					}, false);
				});
		}, false);
	})();


	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})


	$("#recherche").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#TableListe tr").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});

	function StrToDate(str_Date) {

		var date_Date=new Date();
		var tab_Date=new Array();

		tab_Date=str_Date.split("-");

		date_Date.setDate(tab_Date[0]);
		date_Date.setMonth(tab_Date[1]);
		date_Date.setYear(tab_Date[2]);

		return date_Date;
	}



	// Prototype pour l'ajout de jours à une date. utilisé juste en dessous.
	Date.prototype.addDays = function(days) {
		var date = new Date(this.valueOf());
		date.setDate(date.getDate() + days);
		return date;
	}

	$('#date_depart').change(function(){
		date_depart_temp = $("#date_depart").val();
		date_depart = date_depart_temp.split('-').reverse().join('-');
		date_depart = new Date(date_depart);

		date_retour_temp = $("#date_retour").val();
		date_retour = date_retour_temp.split('-').reverse().join('-')
		date_retour = new Date(date_retour);

		if(date_depart>=date_retour)
		{
			// Calcul du nombre de jours à ajouter à la date de départ pour calculer la date de retour.
			depart_jour_semaine = +date_depart.getDay();
			if(depart_jour_semaine == 1){nb_jours = 4;}
			if(depart_jour_semaine == 5){nb_jours = 3;}

			// Calcul de la nouvelle date de retour
			date_retour_nouvelle = new Date();
			date_retour_nouvelle = date_depart.addDays(nb_jours);

			var dd = (date_retour_nouvelle.getDate()).toString();
			var mm = (date_retour_nouvelle.getMonth() + 1).toString(); //Months are zero based
			var yyyy = (date_retour_nouvelle.getFullYear()).toString();
			date_retour_nouvelle = (dd[1]?dd:"0"+dd[0]) + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + yyyy;

			alert("Attention, le système a automatiquement mis à jour la date de retour au " + date_retour_nouvelle + ". Si cela ne vous convient pas, merci de la modifier.");
			$('#date_retour').val(date_retour_nouvelle);
			}
	});








	$('#date_retour').change(function(){
		date_depart_temp = $("#date_depart").val();
		date_depart = date_depart_temp.split('-').reverse().join('-');
		date_depart = new Date(date_depart);

		date_retour_temp = $("#date_retour").val();
		date_retour = date_retour_temp.split('-').reverse().join('-')
		date_retour = new Date(date_retour);


		if( date_retour<=date_depart)
		{
			// Calcul du nombre de jours à enlever à la date de retour pour calculer la date de départ.
			retour_jour_semaine = +date_retour.getDay();
			if(retour_jour_semaine == 1){nb_jours_retour = 3;}
			if(retour_jour_semaine == 5){nb_jours_retour = 4;}

			// Calcul de la nouvelle date de retour
			date_depart_nouvelle = new Date();
			date_depart_nouvelle = date_retour.addDays(- nb_jours_retour);

			var dd = (date_depart_nouvelle.getDate()).toString();
			var mm = (date_depart_nouvelle.getMonth() + 1).toString(); //Months are zero based
			var yyyy = (date_depart_nouvelle.getFullYear()).toString();
			date_depart_nouvelle = (dd[1]?dd:"0"+dd[0]) + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + yyyy;

			alert("Attention, le système a automatiquement mis à jour la date de départ au " + date_depart_nouvelle + ". Si cela ne vous convient pas, merci de la modifier.");
			$('#date_depart').val(date_depart_nouvelle);
			}
	});






	$('#id_client_association').change(function(){
		valeur_id = $('#id_client_association').val();
		$("#id_client").val(valeur_id).change();
	});


	$('#id_client').change(function(){
		valeur_id = $('#id_client').val();
		$("#id_client_association").val(valeur_id).change();
	});
});
