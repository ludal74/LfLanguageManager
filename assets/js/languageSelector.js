/*************************************************/
// LANGUAGE SELECTOR JS
/*************************************************/
function changeLang( lang ) 
{   
    var newUrl = location.href.replace(/[a-z]{2}-[A-Z]{2}/,lang);

    if( location.host == '127.0.0.1' )
    {
    	var newUrl = 'http://' + location.host + '/Isabaud/';
    	var url = document.location.href.split(newUrl);
    }
    else
    {
    	var newUrl = 'http://' + location.host + '/';
    	var url = document.location.href.split(newUrl);
    }
    
    console.log( newUrl );

    
    var currentUrl = url[1].substring(2, url[1].length);

   if( currentUrl == '') {
	   $retour = location.href = newUrl +  strtolower(lang);
   }else{
	   $retour = location.href = newUrl + strtolower(lang) + currentUrl;
   }
   
   return 
}

function strtolower(chaine) {
    return chaine.toLowerCase();
}