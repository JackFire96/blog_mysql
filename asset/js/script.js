//var monlien =document.getElementById("lienConnexion");
//console.dir(monlien); 

//désactivation lien se connecter : 
if(typeof(lienConnexion) != "undefined"){

document.getElementById("lienConnexion").addEventListener("click", function(event){
    event.preventDefault()
});
}
if(typeof(editeurNewsletter) != "undefined"){
CKEDITOR.replace( 'editeurNewsletter' );
}
