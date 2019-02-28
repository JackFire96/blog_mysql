<?php
if(!isset($_SESSION['membre']))
{
	header('location:' . URL);
}

$content .= '<h2 class="text-center">Profil</h2>
			<form method="post" action="">
				<div class="form-group">
					<label for="pseudo">Pseudo</label>
					<input type="text" id="pseudo" name="pseudo" placeholder="Ici votre pseudo" class="form-control" value="' . $_SESSION['membre']['nickname'] . '">
				</div>		
			
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" id="email" placeholder="Ici votre email" name="email" class="form-control" value="' . $_SESSION['membre']['email'] . '">
				</div>
				
				<div class="form-group">
					<input type="submit" name="modification" class="form-control btn-primary" value="Modifier mes informations">
				</div>
			</form>';
			