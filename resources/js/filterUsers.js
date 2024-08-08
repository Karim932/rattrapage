$(document).ready(function() {
    $(document).ready(function() {
        $('button[data-role]').on('click', function() {
            const role = $(this).data('role');
            console.log("Button clicked, role: ", role);

            $.ajax({
                url: `/api/users/role?role=${role}`,
                success: function(data) {
                    updateTable(data);
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred during the AJAX request:", status, error);
                    alert("Failed to load data: " + xhr.statusText);
                }
            });
        });

        $('#cancel-role').on('click', function() {
            $.ajax({
                url: `/api/users/all`,
                success: function(data) {
                    updateTable(data);
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred during the AJAX request:", status, error);
                    alert("Failed to load data: " + xhr.statusText);
                }
            });
        });

        function updateTable(data) {
            const table = $('#users-table table tbody');
            table.empty();

            if (data.length === 0) {
                table.append('<tr><td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Aucun utilisateur trouvé</td></tr>');
            } else {
                data.forEach(user => {
                    const viewUrl = `/users/${user.id}`;
                    const editUrl = `/users/${user.id}/edit`;
                    const deleteUrl = `/users/${user.id}`;
                    const isBanned = user.banned; // Assurez-vous que l'attribut 'banned' est présent dans l'objet 'user'

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
                            </td>
                        </tr>
                    `);
                });
            }
        }

        $(document).ready(function() {
            let currentRole = null;

            function loadUserTable(role = null, sortField = 'id', sortOrder = 'asc', url = '/filter-users') {
                const data = {
                    role: role,
                    sort: sortField,
                    order: sortOrder
                };

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: data,
                    success: function(response) {
                        $('#users-table').html(response.html);
                        $('#pagination-container').html(response.pagination);

                        const firstTdContent = $('#users-table table tbody tr td:first').text().trim();
                        console.log('Content after load:', firstTdContent);

                        if (firstTdContent === "" || firstTdContent === "Aucun utilisateur trouvé") {
                            console.log('Table is empty or shows "Aucun utilisateur trouvé". No need to reload.');
                        } else {
                            currentRole = role;
                            attachSortListeners();
                            // Update sort indicators on headers
                            updateSortIcons(sortField, sortOrder);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('An error occurred:', status, error);
                        console.log('Failed to load user table.');
                    }
                });
            }

            $('#cancel-role').on('click', function() {
                const firstTdContent = $('#users-table table tbody tr td:first').text().trim();
                console.log('First TD Content:', firstTdContent);

                if (firstTdContent === "" || firstTdContent === "Aucun utilisateur trouvé") {
                    console.log('Table is empty or shows "Aucun utilisateur trouvé". No need to reload.');
                    return;
                }

                if (currentRole !== null) {
                    loadUserTable();
                }
            });

            function attachSortListeners() {
                $('.sort-link').on('click', function() {
                    const sortField = $(this).data('sort');
                    let sortOrder = $(this).data('order');

                    // Toggle sort order
                    sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
                    $(this).data('order', sortOrder); // Update the data attribute

                    // Load or reload data with new sort order
                    loadUserTable(currentRole, sortField, sortOrder);
                });
            }

            function updateSortIcons(sortField, sortOrder) {
                $('.sort-link').find('.sort-icon').text(''); // Clear all icons
                $(`.sort-link[data-sort="${sortField}"]`).find('.sort-icon').text(sortOrder === 'asc' ? '▲' : '▼');
            }

            // Initial call to attach listeners on page load
            $(document).ready(function() {
                attachSortListeners();
                attachPaginationListeners();
            });

            $(document).ready(function() {
                let currentAction = null;
                let currentUrl = null;

                // Afficher la modal de confirmation
                function showModal(action, url) {
                    currentAction = action;
                    currentUrl = url;

                    $('#modal-message').text(`Êtes-vous sûr de vouloir ${action} cet utilisateur ?`);
                    $('#confirmation-modal').removeClass('hidden').fadeIn('fast');
                }

                // Écouteur d'événement pour les boutons de bannissement/débannissement
                $('body').on('click', '.ban-button', function(e) {
                    e.preventDefault();
                    const userId = $(this).data('id');
                    const action = $(this).data('action');
                    const url = `/users/${action}/${userId}`;
                    showModal(action, url);
                });

                // Écouteur d'événement pour les boutons de suppression
                $('body').on('submit', 'form.delete-user-form', function(e) {
                    e.preventDefault();
                    const url = $(this).attr('action');
                    showModal('supprimer', url);
                });

                // Gérer la confirmation de la modal
                $('#confirm-btn').on('click', function() {
                    if (!currentUrl || !currentAction) return;
                    $.ajax({
                        url: currentUrl,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: currentAction === 'supprimer' ? 'DELETE' : 'POST'
                        },
                        success: function(response) {
                            $('#success-text').text(response.message || 'Action réalisée avec succès.');
                            $('#success-message').removeClass('hidden').fadeIn('fast').delay(2000).fadeOut('slow', function() {
                                $(this).addClass('hidden');
                            });
                            $('#confirmation-modal').fadeOut('fast', function() {
                                $(this).addClass('hidden');
                            });
                            // Recharger la table ou faire des mises à jour d'interface ici
                            //loadUserTable(currentRole);
                            location.reload();

                        },
                        error: function(xhr) {
                            alert('Une erreur est survenue : ' + xhr.statusText);
                            console.error('An error occurred:', xhr.statusText);
                        }
                    });
                });

                // Gérer l'annulation de la modal
                $('#cancel-btn').on('click', function() {
                    $('#confirmation-modal').fadeOut('fast', function() {
                        $(this).addClass('hidden');
                    });
                });
            });
        });
    });

    $('#search-users').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        try {
            $("#users-table table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        } catch (e) {
            console.error("An error occurred while filtering the users:", e);
        }
    });
});
