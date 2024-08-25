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

document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    dropdownButton.addEventListener('click', function(e) {
        dropdownMenu.classList.toggle('hidden');
        e.stopPropagation(); // Empêche l'événement de remonter,
    });

    document.addEventListener('click', function(e) {
        if (!dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });

    dropdownMenu.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('type-user').addEventListener('change', function() {
        var skillsSection = document.getElementById('skills-section');
        if (this.value === 'benevole') {
            skillsSection.style.display = 'block';
        } else {
            skillsSection.style.display = 'none';
        }
    });

    // Masquer la section des compétences par défaut si Commerçant est sélectionné
    if (document.getElementById('type_user').value !== 'benevole') {
        document.getElementById('skills-section').style.display = 'none';
    }

    document.getElementById('type_user').addEventListener('change', function() {
        let selectedType = this.value;

        fetch(`/mise-a-jour-user?type=${selectedType}`)
            .then(response => response.json())
            .then(data => {
                let userSelect = document.getElementById('user_id');
                userSelect.innerHTML = '';

                data.forEach(user => {
                    let option = document.createElement('option');
                    option.value = user.id;
                    option.text = `${user.firstname} ${user.lastname} - ${user.email}`;
                    userSelect.add(option);
                });
            });
    }); 
});

