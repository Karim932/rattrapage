// S'exécute lorsque le document HTML complet est chargé et analysé, sans attendre que les feuilles de style, images et iframes soient chargés.
$(document).ready(function() {
    // Attache un écouteur d'événements à tous les boutons avec l'attribut data-role.
    $('button[data-role]').on('click', function() {
        const role = $(this).data('role'); // Récupère la valeur de l'attribut data-role.
        console.log("Button clicked, role: ", role);

        // Effectue une requête AJAX pour obtenir les utilisateurs selon le rôle spécifié.
        $.ajax({
            url: `/api/users/role?role=${role}`,
            success: function(data) {
                updateTable(data); // Met à jour le tableau avec les données reçues.
            },
            error: function(xhr, status, error) {
                console.error("An error occurred during the AJAX request:", status, error);
                alert("Failed to load data: " + xhr.statusText);
            }
        });
    });

    // Écouteur pour le bouton d'ajout du filtre par rôle.
    $('#cancel-role').on('click', function() {
        // Effectue une requête AJAX pour obtenir tous les utilisateurs.
        $.ajax({
            url: `/api/users/all`,
            success: function(data) {
                updateTable(data); // Met à jour le tableau avec les données reçues.
            },
            error: function(xhr, status, error) {
                console.error("An error occurred during the AJAX request:", status, error);
                alert("Failed to load data: " + xhr.statusText);
            }
        });
    });

    // Fonction pour mettre à jour le contenu du tableau des utilisateurs.
    function updateTable(data) {
        const table = $('#users-table table tbody'); // Sélectionne le corps du tableau des utilisateurs.
        table.empty(); // Vide le contenu actuel du tableau.

        // Vérifie si les données sont vides et affiche un message si c'est le cas.
        if (data.length === 0) {
            table.append('<tr><td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Aucun utilisateur trouvé</td></tr>');
        } else {
            // Boucle à travers chaque utilisateur et ajoute une ligne au tableau pour chaque utilisateur.
            data.forEach(user => {
                const viewUrl = `/users/${user.id}`; // URL pour voir les détails de l'utilisateur.
                const editUrl = `/users/${user.id}/edit`; // URL pour modifier l'utilisateur.
                const deleteUrl = `/users/${user.id}`; // URL pour supprimer l'utilisateur.
                const isBanned = user.banned; // Vérifie si l'utilisateur est banni.

                // Construit la ligne du tableau avec les données de l'utilisateur.
                table.append(`
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.firstname}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.lastname}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.email}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.role}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="${viewUrl}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                            <a href="${editUrl}" class="text-indigo-600 hover:text-indigo-900 ml-4">Modifier</a>
                            <button
                                class="ban-button ${isBanned ? 'text-green-600 hover:text-green-900' : 'text-red-600 hover:text-red-900'} ml-4"
                                data-id="${user.id}"
                                data-action="${isBanned ? 'unban' : 'ban'}">
                                ${isBanned ? 'Unban' : 'Ban'}
                            </button>
                            <form action="${deleteUrl}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Supprimer</button>
                            </form>
                        </tr>
                `);
            });
        }
    }

    // Variable globale pour garder en mémoire le rôle courant filtré.
    let currentRole = null;

    // Appel initial à la fonction qui attache les écouteurs d'événements pour le tri.
    attachSortListeners();


    // Fonction principale pour charger les utilisateurs en fonction de filtres et de tris spécifiés.
    function loadUserTable(role = null, sortField, sortOrder, url = '/filter-users') {
        const data = { role, sort: sortField, order: sortOrder }; // Préparation des paramètres de requête.

        // Requête AJAX pour obtenir les données utilisateur avec les paramètres spécifiés.
        $.ajax({
            url,
            method: 'GET',
            data,
            success: function(response) {
                $('#users-table').html(response.html); // Insertion du HTML dans le tableau.
                $('#pagination-container').html(response.pagination); // Mise à jour des contrôles de pagination.

                // Extraction et vérification du premier contenu de cellule pour décider de la prochaine action.
                const firstTdContent = $('#users-table table tbody tr td:first').text().trim();
                console.log('Content after load:', firstTdContent);

                // Condition pour gérer l'absence de données.
                if (firstTdContent === "" || firstTdContent === "Aucun utilisateur trouvé") {
                    console.log('Table is empty or shows "Aucun utilisateur trouvé". No need to reload.');
                } else {
                    currentRole = role; // Mise à jour du rôle actuel.
                    attachSortListeners(); // Réattacher les écouteurs pour le tri après mise à jour du DOM.
                    updateSortIcons(sortField, sortOrder); // Mise à jour visuelle des icônes de tri.
                }
            },
            error: function(xhr, status, error) {
                console.error('An error occurred:', status, error);
                console.log('Failed to load user table Non.');
            }
        });
    }

    function attachSortListeners() {
        // Détachement des écouteurs précédents pour éviter les doublons
        $('.sort-link').off('click').on('click', function() {
            const $this = $(this);
            let sortOrder = $this.data('order');

            console.log("avant changement :", $this.data('sort'), sortOrder); // Affichage de l'ordre de tri actuel

            // Bascule de l'ordre de tri entre ascendant et descendant
            sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';

            // Mise à jour de l'attribut de données et de l'attribut DOM pour refléter le nouvel ordre
            $this.data('order', sortOrder);
            $this.attr('data-order', sortOrder);

            console.log("après changement :", $this.data('sort'), sortOrder); // Affichage du nouvel ordre de tri

            // Mise à jour des icônes de tri et rechargement du tableau avec les nouveaux paramètres de tri
            updateSortIcons($this.data('sort'), sortOrder);
            loadUserTable(currentRole, $this.data('sort'), sortOrder);
        });
    }

    function updateSortIcons(currentSortField, currentSortOrder) {
        // Parcours de tous les liens de tri pour mettre à jour les icônes
        $('.sort-link').each(function() {
            const $this = $(this);
            const sortField = $this.data('sort');

            // Condition pour déterminer quelle icône afficher basée sur si le champ est celui actuellement trié
            $this.find('.sort-icon').text(sortField === currentSortField ?
                (currentSortOrder === 'asc' ? '▲' : '▼') : '↕'); // Utilisation de '↕' pour indiquer un état neutre
        });
    }

    // Gestion du clic sur le bouton pour annuler les filtres de rôle.
    $('#cancel-role').on('click', function() {
        const firstTdContent = $('#users-table table tbody tr td:first').text().trim();
        console.log('First TD Content:', firstTdContent);

        if (firstTdContent === "" || firstTdContent === "Aucun utilisateur trouvé") {
            console.log('Table is empty or shows "Aucun utilisateur trouvé". No need to reload.');
            return;
        }

        // Rechargement de la table sans aucun filtre de rôle si un rôle était préalablement sélectionné.
        if (currentRole !== null) {
            loadUserTable();
        }
    });

    // Gère l'affichage de la modal de confirmation pour différentes actions (bannir, débannir, supprimer).
    $(document).ready(function() {
        let currentAction = null;
        let currentUrl = null;

        // Affiche une modal de confirmation pour une action donnée.
        function showModal(action, url) {
            currentAction = action; // Stocke l'action actuelle.
            currentUrl = url; // Stocke l'URL associée à l'action.

            $('#modal-message').text(`Êtes-vous sûr de vouloir ${action} cet utilisateur ?`); // Définit le message de la modal.
            $('#confirmation-modal').removeClass('hidden').fadeIn('fast'); // Affiche la modal.
        }

        // Écouteur pour les boutons de bannissement/débannissement.
        $('body').on('click', '.ban-button', function(e) {
            e.preventDefault();
            const userId = $(this).data('id'); // Récupère l'ID de l'utilisateur.
            const action = $(this).data('action'); // Récupère l'action (ban ou unban).
            const url = `/users/${action}/${userId}`; // Construit l'URL pour l'action.
            showModal(action, url); // Affiche la modal avec les détails de l'action.
        });

        // Écouteur pour les formulaires de suppression d'utilisateur.
        $('body').on('submit', 'form.delete-user-form', function(e) {
            e.preventDefault();
            const url = $(this).attr('action'); // Récupère l'URL du formulaire.
            showModal('supprimer', url); // Affiche la modal pour confirmer la suppression.
        });

        // Écouteur pour les formulaires de refus d'adhesion.
        $('body').on('submit', 'form.refuse-user-form', function(e) {
            e.preventDefault();
            const url = $(this).attr('action');
            showModal('refuser', url);
        });

        // Écouteur pour les formulaires d'adhesion accepté.
        $('body').on('submit', 'form.accept-user-form', function(e) {
            e.preventDefault();
            const url = $(this).attr('action');
            showModal('accepter', url);
        });

        window.openModal = function() {
            document.getElementById('responseModal').classList.remove('hidden');
        };

        window.closeModal = function() {
            document.getElementById('responseModal').classList.add('hidden');
        };

        // Gère la confirmation de l'action via la modal.
        $('#confirm-btn').on('click', function() {
            if (!currentUrl || !currentAction) return; // Si aucune URL ou action n'est définie, annule l'exécution.
            $.ajax({
                url: currentUrl,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // Inclut le jeton CSRF pour la validation.
                    _method: currentAction === 'supprimer' ? 'DELETE' : 'POST' // Définit la méthode HTTP en fonction de l'action.
                },
                success: function(response) {
                    $('#success-text').text(response.message || 'Action réalisée avec succès.'); // Affiche un message de succès.
                    $('#success-message').removeClass('hidden').fadeIn('fast').delay(2000).fadeOut('slow', function() {
                        $(this).addClass('hidden'); // Masque le message de succès après un délai.
                    });
                    $('#confirmation-modal').fadeOut('fast', function() {
                        $(this).addClass('hidden'); // Masque la modal.
                    });
                    // Recharge la table ou effectue des mises à jour de l'interface ici.
                    // loadUserTable(currentRole);
                    location.reload(); // Recharge la page pour refléter les changements.
                },
                error: function(xhr) {
                    alert('Une erreur est survenue : ' + xhr.statusText); // Affiche une alerte en cas d'erreur.
                    console.error('An error occurred:', xhr.statusText); // Log l'erreur dans la console.
                }
            });
        });

        // Gère l'annulation de la modal.
        $('#cancel-btn').on('click', function() {
            $('#confirmation-modal').fadeOut('fast', function() {
                $(this).addClass('hidden'); // Masque la modal rapidement.
            });
        });
    });

    // Écouteur pour le champ de recherche des utilisateurs.
    $('#search-users').on('keyup', function() {
        const value = $(this).val().toLowerCase(); // Récupère la valeur du champ en minuscules.
        try {
            // Filtre les lignes du tableau basées sur la saisie.
            $("#users-table table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1); // Affiche ou masque les lignes selon qu'elles contiennent la valeur saisie.
            });
        } catch (e) {
            console.error("An error occurred while filtering the users:", e); // Log les erreurs de filtrage dans la console.
        }
    });
});
