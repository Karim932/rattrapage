document.addEventListener('DOMContentLoaded', function () {
    setupCharts();
});

function setupCharts() {
    const data = window.dashboardData;

    try {
        var ctxRegistrations = document.getElementById('registrationsChart').getContext('2d');
        var registrationsChart = new Chart(ctxRegistrations, {
            type: 'line',
            data: {
                labels: data.months,
                datasets: [{
                    label: 'Inscriptions',
                    data: data.registrationData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctxBans = document.getElementById('bansChart').getContext('2d');
        var bansChart = new Chart(ctxBans, {
            type: 'line',
            data: {
                labels: data.months,
                datasets: [{
                    label: 'Bannissements',
                    data: data.bansData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctxRoles = document.getElementById('rolesChart').getContext('2d');
        var rolesChart = new Chart(ctxRoles, {
            type: 'pie',
            data: {
                labels: data.roleLabels,
                datasets: [{
                    label: 'Répartition par rôles',
                    data: data.roleCounts,
                    backgroundColor: [
                        'rgba(255, 205, 86, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 205, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        var ctxActiveUsers = document.getElementById('userStatusChart').getContext('2d');
        var activeUsersChart = new Chart(ctxActiveUsers, {
            type: 'bar',
            data: {
                labels: data.userStatusLabels, 
                datasets: [
                    {
                        label: 'Nombre d\'utilisateurs actifs',
                        data: [data.userStatusData[0], 0], 
                        backgroundColor: 'rgba(54, 162, 235, 0.6)', 
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Nombre total d\'utilisateurs',
                        data: [0, data.userStatusData[1]], 
                        backgroundColor: 'rgba(255, 99, 132, 0.6)', 
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        var ctxBenevole = document.getElementById('benevoleStatusChart').getContext('2d');
        var benevoleStatusChart = new Chart(ctxBenevole, {
                type: 'pie',
                data: {
                    labels: data.benevoleStatusLabels, 
                    datasets: [{
                        data: data.benevoleStatusData, 
                        backgroundColor: [
                            'rgba(255, 205, 86, 0.6)',  
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(25, 50, 100, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 205, 86, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(25, 50, 100, 1)'

                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
 
            var ctxCommercant = document.getElementById('commercantStatusChart').getContext('2d');
            var commercantStatusChart = new Chart(ctxCommercant, {
                type: 'pie',
                data: {
                    labels: data.commercantStatusLabels, 
                    datasets: [{
                        data: data.commercantStatusData, 
                        backgroundColor: [
                            'rgba(255, 205, 86, 0.6)', 
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                        ],
                        borderColor: [
                            'rgba(255, 205, 86, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                responsive: true,
                    maintainAspectRatio: false
                }
            });

    } catch (error) {
        console.error("Erreur lors de l'initialisation des graphiques : ", error);
    }
}