/*
==========================================================
VC Routine Management System
Routine JavaScript
==========================================================
*/

document.addEventListener("DOMContentLoaded", function () {
  /*=====================================================
        DOM ELEMENTS
    =====================================================*/

  const department = document.getElementById("department");

  const semester = document.getElementById("semester");

  const addClassBtn = document.getElementById("addClassBtn");

  const routineTable = document.querySelector(".routine-table tbody");

  /*=====================================================
        Bootstrap Modals
    =====================================================*/

  const addModal = new bootstrap.Modal(
    document.getElementById("addClassModal"),
  );

  const editModal = new bootstrap.Modal(
    document.getElementById("editClassModal"),
  );

  const deleteModal = new bootstrap.Modal(
    document.getElementById("deleteModal"),
  );

  /*=====================================================
        Department Changed
    =====================================================*/

  if (department) {
    department.addEventListener("change", function () {
      loadSemesters(this.value);
    });
  }

  /*=====================================================
        Semester Changed
    =====================================================*/

  if (semester) {
    semester.addEventListener("change", function () {
      loadRoutine();
    });
  }

  /*=====================================================
        Add Class Button
    =====================================================*/

  if (addClassBtn) {
    addClassBtn.addEventListener("click", function () {
      document.getElementById("addClassForm").reset();

      addModal.show();
    });
  }

  /*=====================================================
        Load Semesters
    =====================================================*/

  function loadSemesters(departmentId) {
    semester.innerHTML = "<option>Loading...</option>";

    fetch("../ajax/get_semesters.php?department_id=" + departmentId)
      .then((response) => response.json())

      .then((data) => {
        semester.innerHTML = "";

        data.forEach(function (item) {
          semester.innerHTML += `<option value="${item.semester_id}">

                    ${item.semester_name}

                </option>`;
        });

        loadRoutine();
      })

      .catch(function () {
        alert("Unable to load semesters.");
      });
  }

  /*=====================================================
        Load Routine
    =====================================================*/

  function loadRoutine() {
    const departmentId = department.value;

    const semesterId = semester.value;

    routineTable.innerHTML = `<tr>

            <td colspan="7" class="text-center">

                <div class="spinner-border text-danger">

                </div>

            </td>

        </tr>`;

    fetch(
      "../ajax/get_routine.php" +
        "?department_id=" +
        departmentId +
        "&semester_id=" +
        semesterId,
    )
      .then((response) => response.text())

      .then((html) => {
        routineTable.innerHTML = html;

        bindButtons();
      })

      .catch(function () {
        routineTable.innerHTML = `<tr>

                <td colspan="7">

                Error loading routine.

                </td>

            </tr>`;
      });
  }

  /*=====================================================
        Bind Dynamic Buttons
    =====================================================*/

  function bindButtons() {
    bindEditButtons();

    bindDeleteButtons();
  }
  /*=====================================================
        EDIT BUTTON
    =====================================================*/

  function bindEditButtons() {
    document.querySelectorAll(".editClass").forEach(function (btn) {
      btn.onclick = function () {
        const id = this.dataset.id;

        fetch("../ajax/edit_class.php?id=" + id)
          .then((response) => response.text())

          .then(function (html) {
            document.getElementById("editFormContent").innerHTML = html;

            editModal.show();
          })

          .catch(function () {
            alert("Unable to load class.");
          });
      };
    });
  }

  /*=====================================================
        DELETE BUTTON
    =====================================================*/

  let deleteRoutineId = 0;

  function bindDeleteButtons() {
    document.querySelectorAll(".deleteClass").forEach(function (btn) {
      btn.onclick = function () {
        deleteRoutineId = this.dataset.id;

        deleteModal.show();
      };
    });
  }

  /*=====================================================
        DELETE CONFIRM
    =====================================================*/

  document
    .getElementById("confirmDelete")

    .addEventListener("click", function () {
      fetch("../ajax/delete_routine.php", {
        method: "POST",

        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },

        body: "routine_id=" + deleteRoutineId,
      })
        .then((response) => response.text())

        .then(function () {
          deleteModal.hide();

          loadRoutine();
        })

        .catch(function () {
          alert("Delete failed.");
        });
    });

  /*=====================================================
        ADD CLASS FORM
    =====================================================*/

  document
    .getElementById("addClassForm")

    .addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch("../ajax/save_routine.php", {
        method: "POST",

        body: formData,
      })
        .then((response) => response.text())

        .then(function () {
          addModal.hide();

          document.getElementById("addClassForm").reset();

          loadRoutine();
        })

        .catch(function () {
          alert("Unable to save class.");
        });
    });

  /*=====================================================
        UPDATE CLASS FORM
    =====================================================*/

  document
    .getElementById("editClassForm")

    .addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch("../ajax/update_routine.php", {
        method: "POST",

        body: formData,
      })
        .then((response) => response.text())

        .then(function () {
          editModal.hide();

          loadRoutine();
        })

        .catch(function () {
          alert("Unable to update class.");
        });
    });

  /*=====================================================
        INITIAL LOAD
    =====================================================*/

  bindButtons();
});

