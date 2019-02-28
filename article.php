<?php
$content .= '<h3>Page article</h3>
	        
			<div class="form-group">
		        <input type="text" id="titre" name="titre" class="form-control" value="' . $affichageArticles['title'] . '" readonly>
			</div>
							
			<div class="form-group">
			    <img class="form-control" src="img/' . $affichageArticles['image'] . '"></img>
			</div>	
			<div class="form-group">
                <p name="contenu" id="contenu">' . $affichageArticles['content'] . '</p>
			</div>';