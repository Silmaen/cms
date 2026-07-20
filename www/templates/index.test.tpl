<!--{include file="header.tpl"}-->



	<div class="container-fluid">
		<h1 class="text-center text-gris mt-1 mb-1">Identification</h1>
		<br/>
		
	</div>
	
	<div class="container-fluid col-12" style="max-width: 450px;">
		<form class="needs-validation text-center" action="accueil.php" method="POST">
			
			<h3 class="mb-3 font-weight-normal">Veuillez vous connecter</h3>
			
			<!--{if $message_identification!=""}-->
				<h6 class="mb-3 text-danger font-weight-bold"><!--{$message_identification}--></h6>
			<!--{/if}-->
			
			
			<div class="form-group row justify-content-center">
				<div class="col-12">
					<input type="email" class="form-control form-control-danger" id="email" name="email" placeholder="Email" required autofocus>
					<div class="invalid-feedback">Veuillez saisir un email valide</div>
				</div>
			</div>
			<div class="form-group row justify-content-center">
				<div class="col-12">
					<input type="password" class="form-control form-control-danger" id="mdp" name="mdp" placeholder="Mot de passe" required autofocus>
					<div class="invalid-feedback">Veuillez saisir un mot de passe valide</div>
				</div>
			</div>
			
			<div class="row justify-content-center">
				<div class="col-12 mb-3"><a class="text-vert" href="mdp_perdu.php">J'ai perdu mon mot de passe</a></div>
			</div>
			
			<div class="form-group row justify-content-center">
				<button id="btnSubmit" type="submit" class="btn btn-vert">Valider</button>
			</div>		
		</form>
	</div>
	

</body>
</html>