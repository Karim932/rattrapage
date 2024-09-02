import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import bootstrapPlugin from '@fullcalendar/bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin, bootstrapPlugin],
        themeSystem: 'bootstrap',
        initialView: 'listWeek',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today: 'Aujourd’hui',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
            list: 'Liste'
        },
        buttonIcons: {
            prev: 'fa-chevron-left',
            next: 'fa-chevron-right'
        },
        noEventsContent: function() {
            return "Il n'y a pas de planning";
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            calendarEl.classList.add('loading');

            var city = document.getElementById('city') ? document.getElementById('city').value : '';
            var service = document.getElementById('service_id') ? document.getElementById('service_id').value : '';

            var url = '/api/plannings';
            var params = [];

            if (city) {
                params.push('city=' + encodeURIComponent(city));
            }
            if (service) {
                params.push('service_id=' + encodeURIComponent(service));
            }

            if (params.length > 0) {
                url += '?' + params.join('&');
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    setTimeout(function() {
                        calendarEl.classList.remove('loading');
                    }, 1000);
                    successCallback(data);

                })
                .catch(error => {
                    calendarEl.classList.remove('loading');
                    console.error('Error fetching events:', error);
                    failureCallback(error);
                });
        }
    });

    calendar.render();
});
