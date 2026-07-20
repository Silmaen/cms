<!--{include file="header_mdp.tpl"}-->



	<div class="container-fluid">
		<h1 class="text-center text-gris mt-1 mb-1">J'ai perdu mon mot de passe</h1>
		<br/>
		
	</div>
	
	<div class="container-fluid col-12" style="max-width: 450px;">
		<form class="needs-validation text-center" action="mdp_perdu.php" method="POST">
			
			<h3 class="mb-3 font-weight-normal">Veuillez saisir votre email</h3>
			
			<!--{if $message_mdp!=""}-->
				<h6 class="mb-3 text-danger font-weight-bold"><!--{$message_mdp}--></h6>
			<!--{else}-->
			
				<div class="form-group row justify-content-center">
					<div class="col-12">
						<input type="email" class="form-control form-control-danger" id="email" name="email" placeholder="Email" required autofocus>
						<div class="invalid-feedback">Veuillez saisir un email valide</div>
					</div>
				<div class="form-group row justify-content-center">
					<button id="btnSubmit" type="submit" class="btn btn-vert">Envoyer</button>
				</div>	
			<!--{/if}-->

		</form>
	</div>
	

</body>
</html>