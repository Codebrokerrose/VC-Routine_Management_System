

document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");

  if (!calendarEl) return;

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",

    height: "auto",

    expandRows: true,

    fixedWeekCount: false,

    dayMaxEvents: true,

    dayMaxEventRows: false,

    eventDisplay: "block",

    displayEventTime: false,

    headerToolbar: {
      left: "prev",
      center: "title",
      right: "next",
    },

    events: calendarEvents,

    eventClick: function (info) {
      document.getElementById("eventTitle").innerHTML = info.event.title;

      document.getElementById("eventDate").innerHTML =
        info.event.start.toLocaleDateString();

      document.getElementById("eventDescription").innerHTML =
        info.event.extendedProps.description || "No Description";

      const modal = new bootstrap.Modal(document.getElementById("eventModal"));

      modal.show();
    },
  });

  calendar.render();
});

