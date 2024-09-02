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
                const isBanned = user.banned; 

                table.append(`
                    <tr>
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

    let currentRole = null;

    attachSortListeners();


    function loadUserTable(role = null, sortField, sortOrder, url = '/filter-users') {
        const data = { role, sort: sortField, order: sortOrder }; 

        $.ajax({
            url,
            method: 'GET',
            data,
            success: function(response) {
                $('#users-table').html(response.html); 
                $('#pagination-container').html(response.pagination); 

                const firstTdContent = $('#users-table table tbody tr td:first').text().trim();
                
                if (firstTdContent === "" || firstTdContent === "Aucun utilisateur trouvé") {
                    console.log('Table is empty or shows "Aucun utilisateur trouvé". No need to reload.');
                } else {
                    currentRole = role; 
                    attachSortListeners(); 
                    updateSortIcons(sortField, sortOrder); 
                }
            },
            error: function(xhr, status, error) {
                console.error('An error occurred:', status, error);
                console.log('Failed to load user table Non.');
            }
        });
    }

    function attachSortListeners() {
        $('.sort-link').off('click').on('click', function() {
            const $this = $(this);
            let sortOrder = $this.data('order');

            sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';

            $this.data('order', sortOrder);
            $this.attr('data-order', sortOrder);

            updateSortIcons($this.data('sort'), sortOrder);
            loadUserTable(currentRole, $this.data('sort'), sortOrder);
        });
    }

    function updateSortIcons(currentSortField, currentSortOrder) {
        $('.sort-link').each(function() {
            const $this = $(this);
            const sortField = $this.data('sort');

            $this.find('.sort-icon').text(sortField === currentSortField ?
                (currentSortOrder === 'asc' ? '▲' : '▼') : '↕'); 
        });
    }

    $('#cancel-role').on('click', function() {
        const firstTdContent = $('#users-table table tbody tr td:first').text().trim();
        console.log('First TD Content:', firstTdContent);

        if (firstTdContent === "" || firstTdContent === "Aucun utilisateur trouvé") {
            return;
        }

        if (currentRole !== null) {
            loadUserTable();
        }
    });

    $(document).ready(function() {
        let currentAction = null;
        let currentUrl = null;

        function showModal(action, url) {
            currentAction = action; 
            currentUrl = url; 

            $('#modal-message').text(`Êtes-vous sûr de vouloir ${action} cet utilisateur ?`); 
            $('#confirmation-modal').removeClass('hidden').fadeIn('fast'); 
        }

        $('body').on('click', '.ban-button', function(e) {
            e.preventDefault();
            const userId = $(this).data('id'); 
            const action = $(this).data('action'); 
            const url = `/users/${action}/${userId}`; 
            showModal(action, url); 
        });

        $('body').on('submit', 'form.delete-user-form', function(e) {
            e.preventDefault();
            const url = $(this).attr('action'); 
            showModal('supprimer', url);
        });

        $('body').on('submit', 'form.refuse-user-form', function(e) {
            e.preventDefault();
            const url = $(this).attr('action');
            showModal('refuser', url);
        });

        $('body').on('submit', 'form.revoque-user-form', function(e) {
            e.preventDefault();
            const url = $(this).attr('action');
            showModal('revoquer', url);
        });

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


        $('#confirm-btn').on('click', function() {
            if (!currentUrl || !currentAction) {
                console.warn('URL or action not defined.');
                return; 
            }

            $.ajax({
                url: currentUrl,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), 
                    _method: currentAction === 'supprimer' ? 'DELETE' : 'POST' 
                },
                success: handleSuccess,
                error: handleError
            });
        });

        function handleSuccess(response) {
            showSuccessMessage(response.message);  
            $('#confirmation-modal').fadeOut('fast', function() {
                $(this).addClass('hidden');  
            });
        
            setTimeout(function() {
                if (response.redirectUrl && response.redirectUrl.trim() !== '') {
                    window.location.href = response.redirectUrl;  
                } else {
                    location.reload();  
                }
            }, 1000);  
        }
        
        

        function handleError(xhr) {
            alert('Une erreur est survenue : ' + xhr.statusText);
            console.error('An error occurred:', xhr.statusText);
        }

        function showSuccessMessage(message) {
            let successText = $('#success-text');
            let successMessage = $('#success-message');

            successText.text(message || 'La candidature a été mise à jour.');

            successMessage.css('opacity', '1').fadeIn('fast').delay(800).fadeOut('slow', function() {
                $(this).css('opacity', '0'); 
            });
        }


        $('#cancel-btn').on('click', function() {
            $('#confirmation-modal').fadeOut('fast', function() {
                $(this).addClass('hidden'); 
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
