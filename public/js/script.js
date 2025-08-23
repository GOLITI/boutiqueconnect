const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		// Supprime la classe 'active' de tous les éléments de menu
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		// Ajoute la classe 'active' à l'élément cliqué
		li.classList.add('active');
	})
});




// TOGGLE SIDEBAR (Afficher/Masquer la barre latérale)
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})


// Gérer la barre de recherche sur mobile
const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault(); // Empêche la soumission du formulaire par défaut sur mobile
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x'); // Change l'icône de recherche en croix
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search'); // Change l'icône de croix en recherche
		}
	}
})


// Ajuster la barre latérale et la barre de recherche en fonction de la taille de l'écran
if(window.innerWidth < 768) {
	sidebar.classList.add('hide'); // Cache la barre latérale sur les petits écrans
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}

window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})


// Mode Sombre (Dark Mode)
const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
	if(this.checked) {
		document.body.classList.add('dark');
		// Optionnel: Stocker la préférence de l'utilisateur dans localStorage pour qu'elle persiste
		localStorage.setItem('darkMode', 'enabled');
	} else {
		document.body.classList.remove('dark');
		// Optionnel: Supprimer la préférence de localStorage
		localStorage.setItem('darkMode', 'disabled');
	}
})

// Appliquer le mode sombre au chargement si la préférence est enregistrée
if (localStorage.getItem('darkMode') === 'enabled') {
    switchMode.checked = true;
    document.body.classList.add('dark');
} else {
    switchMode.checked = false;
    document.body.classList.remove('dark');
}
