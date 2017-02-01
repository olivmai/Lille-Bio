window.onscroll = function() {
	if (window.scrollY > 100) {
		var header = getElementById('header');
		header.style.position = 'absolute';
		header.style.top = 0;
	}
};