$(function(){

	// Bouton de fermeture des messages de confirmation
	$('.closeBtn').click(function(){
		$('.alert').fadeOut();
	});

	$('#submitResa').click(function(){
		$(this).hide();
		$('#loaderResa').css('display', 'block');
	});

});

var input_nbPers = document.getElementById('nbrPers'),
	msg_form = document.getElementById('msgForm'),
	msg_close = document.getElementById('pMsg');

function formVerify()
{
	msg_form.style.display = 'none';

	if (isNaN(input_nbPers.value)) {
		msg_form.style.display = 'inline-block';
		msg_form.innerHTML = "<strong>Attention </strong>: ce champ doit être un nombre";
		return false;
	}

	if (input_nbPers.value > 20) {
		msg_form.style.display = 'inline-block';
		msg_form.innerHTML = "<strong>Attention </strong>: les réservations son limitées à 20 personnes maximum";
		return false;
	}

	return true;

}

function prevent()
{
	return false;
}