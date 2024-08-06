document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.sidebar-nav a');
    const currentUrl = window.location.href;

    navLinks.forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add('active');
        }
    });

    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navLinks.forEach(navLink => navLink.classList.remove('active'));
            this.classList.add('active');
         });
    });
});


document.getElementById('toggle-button').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleIcon = document.getElementById('toggle-icon');
    const toggleButton = document.getElementById('toggle-button');

    sidebar.classList.toggle('hidden');
    mainContent.classList.toggle('ml-64');

    if (sidebar.classList.contains('hidden')) {
      mainContent.classList.remove('ml-64');
      toggleIcon.textContent = 'chevron_right';
      toggleButton.classList.remove('left-64');
      toggleButton.classList.add('left-0');
    } else {
      mainContent.classList.add('ml-64');
      toggleIcon.textContent = 'chevron_left';
      toggleButton.classList.remove('left-0');
      toggleButton.classList.add('left-64');
    }
});

