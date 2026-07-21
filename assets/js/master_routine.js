// ======================================================
// MASTER ROUTINE.JS - PART 1
// ======================================================

let addModal;
let editModal;
let deleteModal;

let deleteRoutineId = 0;

document.addEventListener("DOMContentLoaded", function () {
  addModal = new bootstrap.Modal(document.getElementById("addClassModal"));

  editModal = new bootstrap.Modal(document.getElementById("editClassModal"));

  deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));

  bindEvents();

  loadMasterRoutine();
});

// ======================================================
// EVENT LISTENERS
// ======================================================

function bindEvents() {
  document.getElementById("session").addEventListener("change", sessionChanged);

  document.getElementById("course").addEventListener("change", courseChanged);

  document
    .getElementById("semester")
    .addEventListener("change", semesterChanged);

  const addBtn = document.getElementById("addClassBtn");

if (addBtn) {
    addBtn.addEventListener("click", function () {
    clearAddForm();

    addModal.show();
  });
}
}

// ======================================================
// SESSION CHANGE
// ======================================================

function sessionChanged() {
  const session = document.getElementById("session").value;

  const semester = document.getElementById("semester");

  semester.innerHTML = "";

  if (session === "Odd") {
    semester.innerHTML = `
            <option value="1">Semester I</option>
            <option value="3">Semester III</option>
            <option value="5">Semester V</option>
        `;
  } else {
    semester.innerHTML = `
            <option value="2">Semester II</option>
            <option value="4">Semester IV</option>
            <option value="6">Semester VI</option>
        `;
  }

  updateHiddenFields();

  loadMasterRoutine();
}

// ======================================================
// COURSE CHANGE
// ======================================================

function courseChanged() {
  updateHiddenFields();

  loadMasterRoutine();
}

// ======================================================
// SEMESTER CHANGE
// ======================================================

function semesterChanged() {
  updateHiddenFields();

  loadMasterRoutine();
}

// ======================================================
// UPDATE HIDDEN FIELDS
// ======================================================

function updateHiddenFields() {
  const session = document.getElementById("session").value;
  const course = document.getElementById("course").value;
  const semester = document.getElementById("semester").value;

  document.getElementById("session_type").value = session;
  document.getElementById("course_type").value = course;
  document.getElementById("semester_id").value = semester;

  document.getElementById("add_session_type").value = session;
  document.getElementById("add_course_type").value = course;
  document.getElementById("add_semester").value = semester;

  history.replaceState(
    {},
    "",
    `?session=${session}&course=${course}&semester=${semester}`,
  );

  updateInfoCards();
}

// ======================================================
// UPDATE INFO CARDS
// ======================================================

function updateInfoCards() {
  const session = document.getElementById("session").value;
  const course = document.getElementById("course").value;
  const semester = document.getElementById("semester").value;

  document.getElementById("infoSession").textContent = session;

  document.getElementById("infoCourse").textContent = course;

  document.getElementById("infoSemester").textContent = "Semester " + semester;
}

// ======================================================
// LOAD MASTER ROUTINE
// ======================================================

function loadMasterRoutine() {
  const session = document.getElementById("session").value;

  const course = document.getElementById("course").value;

  const semester = document.getElementById("semester").value;

  fetch(
    `../ajax/get_master_routine.php?session=${session}&course=${course}&semester=${semester}`,
  )
    .then((response) => response.text())
    .then((data) => {
      document.getElementById("routineBody").innerHTML = data;

      bindRoutineButtons();
    });
}

// ======================================================
// BIND ADD / EDIT / DELETE BUTTONS
// ======================================================

function bindRoutineButtons() {
  document.querySelectorAll(".addSlot").forEach((btn) => {
    btn.addEventListener("click", function () {
      clearAddForm();

      document.getElementById("day").value = this.dataset.day;

      document.getElementById("start_time").value = this.dataset.start;

      document.getElementById("end_time").value = this.dataset.end;

      addModal.show();
    });
  });

  bindEditButtons();

  bindDeleteButtons();
}

// ======================================================
// CLEAR ADD FORM
// ======================================================

function clearAddForm() {
  document.getElementById("addClassForm").reset();

  updateHiddenFields();
}


// ======================================================
// EDIT BUTTONS
// ======================================================

function bindEditButtons() {
  document.querySelectorAll(".editClass").forEach((btn) => {
    btn.addEventListener("click", function () {
      const id = this.dataset.id;

      fetch("../ajax/edit_master_routine.php?id=" + id)
        .then((response) => response.text())
        .then((html) => {
          document.getElementById("editFormContent").innerHTML = html;

          bindUpdateForm();

          editModal.show();
        });
    });
  });
}
// ======================================================
// DELETE BUTTONS
// ======================================================

function bindDeleteButtons() {
  document.querySelectorAll(".deleteClass").forEach((btn) => {
    btn.addEventListener("click", function () {
      deleteRoutineId = this.dataset.id;

      deleteModal.show();
    });
  });
}

// ======================================================
// SAVE NEW CLASS
// ======================================================

document.getElementById("addClassForm")
.addEventListener("submit", function (e) {

    e.preventDefault();

    const formData = new FormData(this);

    fetch("../ajax/save_master_routine.php", {

        method: "POST",

        body: formData

    })
    .then(response => response.json())
    .then(data => {

        if (data.success) {

            addModal.hide();

            loadMasterRoutine();

            alert("Class added successfully.");

        } else {

            alert(data.message);

        }

    });

});

// ======================================================
// UPDATE CLASS
// ======================================================

function bindUpdateForm() {

    const form = document.getElementById("editClassForm");

    form.addEventListener("submit", function (e) {

        e.preventDefault();

        const formData = new FormData(form);

        fetch("../ajax/update_master_routine.php", {

            method: "POST",

            body: formData

        })
        .then(response => response.json())
        .then(data => {

            if (data.success) {

                editModal.hide();

                loadMasterRoutine();

                alert("Routine updated successfully.");

            } else {

                alert(data.message);

            }

        });

    });

}

// ======================================================
// DELETE CLASS
// ======================================================

document.getElementById("confirmDelete")
.addEventListener("click", function () {

    fetch("../ajax/delete_master_routine.php", {

        method: "POST",

        headers: {

            "Content-Type":
            "application/x-www-form-urlencoded"

        },

        body: "id=" + deleteRoutineId

    })
    .then(response => response.json())
    .then(data => {

        if (data.success) {

            deleteModal.hide();

            loadMasterRoutine();

            alert("Class deleted successfully.");

        } else {

            alert(data.message);

        }

    });

});

// ======================================================
// LOAD ROUTINE NOTE
// ======================================================

// function loadRoutineNote() {

//     const session = document.getElementById("session").value;

//     const course = document.getElementById("course").value;

//     const semester = document.getElementById("semester").value;

//     fetch(
//         `../ajax/get_master_note.php?session=${session}&course=${course}&semester=${semester}`
//     )
//     .then(response => response.text())
//     .then(data => {

//         document.getElementById("masterRoutineNotes").innerHTML = data;

//     });

// }

// ======================================================
// REFRESH TOTAL CLASS COUNT
// ======================================================

function refreshTotalClasses() {

    const session = document.getElementById("session").value;

    const course = document.getElementById("course").value;

    const semester = document.getElementById("semester").value;

    fetch(
        `../ajax/get_master_total.php?session=${session}&course=${course}&semester=${semester}`
    )
    .then(response => response.text())
    // .then(count => {

    //     document.getElementById("infoTotalClasses").innerHTML = count;

    // });

}

// ======================================================
// OVERRIDE LOAD FUNCTION
// ======================================================

const originalLoad = loadMasterRoutine;

loadMasterRoutine = function () {

    originalLoad();

    refreshTotalClasses();

};

// ======================================================
// END OF FILE
// ======================================================

$(document).on("click", "#updateNoteBtn", function () {
  $.ajax({
    url: "../ajax/save_master_note.php",
    type: "POST",

    data: {
      note: $("#masterRoutineNote").val(),
    },

    success: function (response) {
      if (response.trim() === "success") {
        alert("Note updated successfully.");
      } else {
        alert(response);
      }
    },

    error: function (xhr) {
      alert(xhr.responseText);
    },
  });
});