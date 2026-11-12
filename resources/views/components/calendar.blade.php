<!-- FullCalendar view of all upcoming planned activations -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<style>
    /* let the toolbar wrap instead of overflowing on narrow screens */
    #calendar .fc-header-toolbar { flex-wrap: wrap; gap: 0.5rem; }
</style>
<div id="calendar"></div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: window.innerWidth < 768 ? 'listMonth' : 'timeGridWeek', // list is readable on phones
        headerToolbar: { // month view shows multi-day activations as one bar
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: { month: 'Month', week: 'Week', list: 'List', today: 'Today' },
        events: '/planned_activations/export',
        eventClick: function(info) {
            // Don't let the browser navigate
            info.jsEvent.preventDefault(); 

            // Open URL in new window
            if (info.event.url) {
                window.open(info.event.url);
            }
        },
        locale: 'en-gb', // British English for 24-hour format
        firstDay: 1, // Monday as the first day of the week
        height: 'auto',
        eventTimeFormat: { // Event times in 24-hour format
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        slotLabelFormat: { // Time slots in 24-hour format
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        columnHeaderText: function(date) {
            // Custom format for column headers: "Wed 22.01"
            const weekday = date.toLocaleDateString('en-US', { weekday: 'short' }); // Force English weekday
            const day = String(date.getDate()).padStart(2, '0'); // Add leading zero
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Add leading zero
            return `${weekday} ${day}.${month}`; // Return in "DDD DD.MM" format
        }
    });
    calendar.render();
});

</script>
