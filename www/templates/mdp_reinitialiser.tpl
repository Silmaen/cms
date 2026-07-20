<!--{include file="header_mdp.tpl"}-->



	<div class="container-fluid">
		<h1 class="text-center text-gris mt-1 mb-1">Réinitialisation de votre mot de passe</h1>
		<br/>
	</div>
	
	<!--{if $resultat!="1"}-->
	
		<div class="container-fluid col-12" style="max-width: 600px;">
			<form id="reinitialisation" name="reinitialisation" class="needs-validation text-center" action="mdp_reinitialiser.php" method="POST">
			<input type="hidden" id="email" name="email" value="<!--{$email}-->">
			<input type="hidden" id="cle" name="cle" value="<!--{$cle}-->">
				
				<h3 class="mb-3 font-weight-normal">Veuillez saisir votre mot de passe</h3>
				
				<!--{if $message_mdp!=""}-->
					<h6 class="mb-3 text-danger font-weight-bold"><!--{$message_mdp}--></h6>
				<!--{/if}-->
						
				<div class="form-group row justify-content-center">
					<div class="col-12">
						<label class="col-form-label" for="mdp1">Nouveau mot de passe</label>
						<input type="password" class="form-control form-control-danger" id="mdp1" name="mdp1" required autofocus pattern="(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])\S{8,}">
						<div class="invalid-feedback">Veuillez saisir un email valide</div>
					</div>
					<div class="col-12">
						<label class="col-form-label" for="mdp2">Confirmer votre mot de passe</label>
						<input type="password" class="form-control form-control-danger" id="mdp2" name="mdp2" required pattern="(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])\S{8,}">
						<div class="invalid-feedback">Veuillez saisir un email valide</div>
					</div>
				</div>


				<div class="container-fluid">		
					<hr class="col-12 style-5 mt-5 bg-vert">
					<div class="row text-left text-gris">
						Votre mot de passe doit contenir 8 caractères<br />
						Votre mot de passe doit contenir au moins 1 lettre majuscule <br />
						Votre mot de passe doit contenir au moins 1 nombre 
					</div>
					<hr class="style-5 bg-vert">
				</div>
				
				<div class="form-group row justify-content-center">
					<button id="btnSubmit" type="submit" class="btn btn-vert">Réinitialiser</button>
				</div>		
			</form>
		</div>
		
	<!--{else}-->
	
		<!--{if $message_mdp!=""}-->
			<div class="container-fluid col-12 text-center" style="max-width: 600px;"
				<h6 class="mb-3 text-danger font-weight-bold text-center"><!--{$message_mdp}--></h6>
				<br />
				<h6 class="mb-3 text-success font-weight-bold text-center">
					<br />
					<a href="index.php">Cliquer ici pour vous connecter.</a>
				</h6>
			</div>
		<!--{/if}-->	
		
	<!--{/if}-->
	
	
	
	
	
	
<script type="text/javascript">
$('#reinitialisation').submit(function(e) {
    if ($('#mdp1').val() !== $('#mdp2').val()) {
        alert('Attention, le mot de passe de confirmation est différent du mot de passe !');
        e.preventDefault();
        return false;
    }
});
</script> 	
	

</body>
</html>