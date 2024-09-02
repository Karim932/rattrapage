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

document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    dropdownButton.addEventListener('click', function(e) {
        dropdownMenu.classList.toggle('hidden');
        e.stopPropagation(); 
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

