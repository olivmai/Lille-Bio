var input_nbPers = document.getElementById('nbrPers'),
	msg_form = document.getElementById('msgForm');

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