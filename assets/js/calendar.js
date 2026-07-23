// ======================================================
// CALENDAR.JS
// ======================================================

let calendar;
let addModal;
let editModal;
let deleteModal;
let deleteEventID = 0;

// ======================================================
// PAGE LOAD
// ======================================================

document.addEventListener("DOMContentLoaded", function () {
  addModal = new bootstrap.Modal(document.getElementById("addEventModal"));
  editModal = new bootstrap.Modal(document.getElementById("editEventModal"));
  deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));

  initializeCalendar();
  bindEvents();
});

// ======================================================
// EVENT LISTENERS
// ======================================================

function bindEvents() {
  // Add Event Button
  document.getElementById("addEventBtn").addEventListener("click", function () {
    document.getElementById("addEventForm").reset();
    addModal.show();
  });

  // Save Event
  document
    .getElementById("addEventForm")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch("../ajax/save_event.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            addModal.hide();
            reloadCalendar();
            alert("Event Added Successfully.");
          } else {
            alert(data.message);
          }
        });
    });

  // Delete Event
  document
    .getElementById("confirmDelete")
    .addEventListener("click", function () {
      fetch("../ajax/delete_event.php", {
        method: "POST",

        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },

        body: "event_id=" + deleteEventID,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            deleteModal.hide();
            reloadCalendar();
            alert("Event Deleted Successfully.");
          } else {
            alert(data.message);
          }
        });
    });
}

// ======================================================
// INITIALIZE CALENDAR
// ======================================================

function initializeCalendar() {
  calendar = new FullCalendar.Calendar(document.getElementById("calendar"), {
    initialView: "dayGridMonth",

    height: "auto",

    fixedWeekCount: false,

    expandRows: true,

    dayMaxEvents: true,

    displayEventTime: false,

    headerToolbar: {
      left: "prev,next today",

      center: "title",

      right: "dayGridMonth,timeGridWeek,listMonth",
    },

    events: calendarEvents,

    eventDidMount: function (info) {
      let color = "#6B1322";

      switch ((info.event.extendedProps.event_type || "").toLowerCase()) {
        case "holiday":
          color = "#FF5B57";
          break;

        case "meeting":
          color = "#18A9E6";
          break;

        case "seminar":
          color = "#FFF200";
          break;

        case "workshop":
          color = "#50C878";
          break;

        case "examination":
          color = "#7B61FF";
          break;

        case "festival":
          color = "#F4A261";
          break;

        default:
          color = "#6B1322";
      }

      info.el.style.background = color;
      info.el.style.border = "none";
      info.el.style.color = "#fff";
    },

    eventClick: function (info) {
      showEvent(info.event);
    },
  });

  calendar.render();
}

// ======================================================
// SHOW EVENT DETAILS
// ======================================================

function showEvent(event) {
  document.getElementById("eventTitle").innerHTML = event.title;

  document.getElementById("eventDate").innerHTML =
    event.start.toLocaleDateString();

  document.getElementById("eventDescription").innerHTML =
    event.extendedProps.description || "No Description";

  const footer = document.querySelector("#eventModal .modal-footer");

  footer.innerHTML = `

        <button class="btn btn-primary" onclick="editEvent(${event.id})">
            <i class="bi bi-pencil"></i> Edit
        </button>

        <button class="btn btn-danger" onclick="deleteEvent(${event.id})">
            <i class="bi bi-trash"></i> Delete
        </button>

        <button class="btn btn-secondary" data-bs-dismiss="modal">
            Close
        </button>

    `;

  new bootstrap.Modal(document.getElementById("eventModal")).show();
}

// ======================================================
// EDIT EVENT
// ======================================================

function editEvent(id) {
  bootstrap.Modal.getInstance(document.getElementById("eventModal")).hide();

  fetch("../ajax/edit_event.php?id=" + id)
    .then((res) => res.text())
    .then((html) => {
      document.getElementById("editEventContent").innerHTML = html;

      editModal.show();

      bindUpdateEvent();
    });
}

// ======================================================
// UPDATE EVENT
// ======================================================

function bindUpdateEvent() {
  const form = document.getElementById("editEventForm");

  form.onsubmit = function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("../ajax/update_event.php", {
      method: "POST",

      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          editModal.hide();

          reloadCalendar();

          alert("Event Updated Successfully.");
        } else {
          alert(data.message);
        }
      });
  };
}

// ======================================================
// DELETE EVENT
// ======================================================

function deleteEvent(id) {
  deleteEventID = id;

  const detailsModal = bootstrap.Modal.getInstance(
    document.getElementById("eventModal"),
  );

  if (detailsModal) {
    detailsModal.hide();
  }

  deleteModal.show();
}

// ======================================================
// RELOAD CALENDAR
// ======================================================

function reloadCalendar() {
  fetch("../ajax/get_events.php")
    .then((res) => res.json())
    .then((events) => {
      calendar.removeAllEvents();
      calendar.addEventSource(events);
    });
}

// ======================================================
// ESC KEY
// ======================================================

document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    if (addModal) addModal.hide();
    if (editModal) editModal.hide();
    if (deleteModal) deleteModal.hide();
  }
});

// ======================================================
// AUTO REFRESH
// ======================================================

setInterval(function () {
  reloadCalendar();
}, 60000);

// ======================================================
// END
// ======================================================
