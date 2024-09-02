document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('detailsTab').addEventListener('click', function() {
        showDetails();
    });

    
    document.getElementById('messagesTab').addEventListener('click', function() {
        var id = this.getAttribute('data-id'); 
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


