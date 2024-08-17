document.addEventListener('DOMContentLoaded', function () {
    // Ajoute un écouteur d'événements pour le bouton des détails
    document.getElementById('detailsTab').addEventListener('click', function() {
        showDetails();
    });

    // Ajoute un écouteur d'événements pour le bouton des messages
    // Supposition : L'ID est stocké dans un attribut data-id sur le bouton des messages
    document.getElementById('messagesTab').addEventListener('click', function() {
        var id = this.getAttribute('data-id');  // Récupère l'ID stocké dans l'attribut data-id
        showMessages();
    });
});

function showDetails() {
    document.getElementById('detailsContent').style.display = 'block';
    document.getElementById('messagesContent').style.display = 'none';
    document.getElementById('detailsTab').classList.add('active');
    document.getElementById('messagesTab').classList.remove('active');
}

function showMessages() {
    var messagesContent = document.getElementById('messagesContent');
    document.getElementById('detailsContent').style.display = 'none';
    messagesContent.style.display = 'block';
    document.getElementById('detailsTab').classList.remove('active');
    document.getElementById('messagesTab').classList.add('active');
}


