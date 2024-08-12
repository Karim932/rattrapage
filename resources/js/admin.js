// Attente que le DOM soit entièrement chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', function() {
    // Sélection de tous les liens dans la barre de navigation latérale
    const navLinks = document.querySelectorAll('.sidebar-nav a');
    // Obtention de l'URL actuelle
    const currentUrl = window.location.href;

    // Boucle sur chaque lien pour ajouter la classe 'active' si l'URL du lien correspond à l'URL courante
    navLinks.forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add('active');
        }
    });

    // Ajout d'un écouteur d'événements sur chaque lien pour gérer les clics
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Suppression de la classe 'active' de tous les liens lors d'un clic
            navLinks.forEach(navLink => navLink.classList.remove('active'));
            // Ajout de la classe 'active' sur le lien cliqué
            this.classList.add('active');
        });
    });
});

// Ajout d'un écouteur d'événement sur le bouton de bascule
document.getElementById('toggle-button').addEventListener('click', function () {
    // Sélection des éléments du DOM impliqués dans la bascule
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleIcon = document.getElementById('toggle-icon');
    const toggleButton = document.getElementById('toggle-button');

    // Bascule de la classe 'hidden' pour montrer ou cacher la barre latérale
    sidebar.classList.toggle('hidden');
    mainContent.classList.toggle('ml-64');

    // Modification de l'interface en fonction de l'état visible ou caché de la barre latérale
    if (sidebar.classList.contains('hidden')) {
        mainContent.classList.remove('ml-64');
        toggleIcon.textContent = 'chevron_right';  // Changement de l'icône
        toggleButton.classList.remove('left-64');
        toggleButton.classList.add('left-0');  // Déplacement du bouton
    } else {
        mainContent.classList.add('ml-64');
        toggleIcon.textContent = 'chevron_left';  // Retour à l'icône originale
        toggleButton.classList.remove('left-0');
        toggleButton.classList.add('left-64');  // Restauration de la position initiale du bouton
    }
});

// // Ajout d'un écouteur d'événement sur le bouton de bascule
// document.getElementById('dropdownButton').addEventListener('click', function () {


//     console.log('test');
//     const dropdownMenu = document.getElementById('dropdownMenu');
//     dropdownMenu.classList.toggle('hidden');

//     // Fermer le menu lors du clic à l'extérieur
//     window.addEventListener('click', function (event) {
//         if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
//             dropdownMenu.classList.add('hidden');
//         }
//     });
// });

document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');


    console.log(dropdownButton);
    console.log(dropdownMenu);

    // Toggle the dropdown menu visibility
    dropdownButton.addEventListener('click', function(e) {
        dropdownMenu.classList.toggle('hidden');
        e.stopPropagation(); // Empêche l'événement de remonter, important pour la gestion du clic à l'extérieur.
        // console.log('test');
    });

    // Close the dropdown menu if clicking outside of it
    document.addEventListener('click', function(e) {
        if (!dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });

    // Prevent menu from closing when clicking inside
    dropdownMenu.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});



