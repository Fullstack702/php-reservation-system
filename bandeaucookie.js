function getConteneurInfoCookie(){
	if(localStorage['BandeauInfoCookie'] != 1){
		document.getElementById('BandeauInfoCookie').style.display = 'block';
	}else{
		document.getElementById('BandeauInfoCookie').style.display = 'none';
		document.getElementById('ConteneurInfosCookie').style.display = 'none';
	}
}

function getInfosCookie(){
	if(document.getElementById('ConteneurInfosCookie').style.display == "none"){
		document.getElementById('ConteneurInfosCookie').style.display = "block";
	}else{
		document.getElementById('ConteneurInfosCookie').style.display = "none";
	}
}