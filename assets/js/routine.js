/*
==========================================================
VC Routine Management System
Routine JavaScript
==========================================================
*/

document.addEventListener("DOMContentLoaded", function () {
  // console.log("routine.js loaded");
  /*=====================================================
        DOM ELEMENTS
    =====================================================*/

  const department = document.getElementById("department");

  const semester = document.getElementById("semester");

  const addClassBtn = document.getElementById("addClassBtn");

  const routineTable = document.getElementById("routineBody");

  const exportRoutineBtn = document.getElementById("exportRoutineBtn");

  const downloadRoutineBtn = document.getElementById("downloadRoutineBtn");

  const exportRoutineModal = new bootstrap.Modal(
    document.getElementById("exportRoutineModal"),
  );

  if (exportRoutineBtn) {
    exportRoutineBtn.addEventListener("click", function () {
      exportRoutineModal.show();
    });
  }

  if (downloadRoutineBtn) {
    downloadRoutineBtn.addEventListener("click", function () {
      const format = document.getElementById("routineExportFormat").value;

      const departmentId = department.value;

      const semesterId = semester.value;

      if (!departmentId || !semesterId) {
        alert("Please select Department and Semester first.");

        return;
      }

      let exportUrl = "";

      if (format === "excel") {
        exportUrl = "../export/routine_excel.php";
      } 

      exportUrl +=
        "?department_id=" +
        encodeURIComponent(departmentId) +
        "&semester_id=" +
        encodeURIComponent(semesterId);

      window.location.href = exportUrl;

      exportRoutineModal.hide();
    });
  }

  /*=====================================================
        BOOTSTRAP MODALS
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
        DEPARTMENT CHANGED
    =====================================================*/

  if (department) {
    department.addEventListener("change", function () {
      loadSemesters();
    });
  }

  /*=====================================================
        SEMESTER CHANGED
    =====================================================*/

  if (semester) {
    semester.addEventListener("change", function () {
      document.getElementById("semester_id").value = this.value;

      loadRoutine();
    });
  }

  /*=====================================================
        ADD CLASS BUTTON
    =====================================================*/

  if (addClassBtn) {
    addClassBtn.addEventListener("click", function () {
      document.getElementById("addClassForm").reset();

      document.getElementById("department_id").value = department.value;

      document.getElementById("semester_id").value = semester.value;

      addModal.show();
    });
  }

  /*=====================================================
        LOAD SEMESTERS
    =====================================================*/

  function loadSemesters() {
    semester.innerHTML = `<option>Loading...</option>`;

    fetch(
      "../ajax/get_semesters.php?department_id=" +
        encodeURIComponent(department.value),
    )
      .then((response) => response.json())

      .then(function (data) {
        semester.innerHTML = "";

        if (data.length === 0) {
          semester.innerHTML = `<option value="">No Semester</option>`;
          return;
        }

        data.forEach(function (item) {
          semester.innerHTML += `<option
                    value="${item.semester_id}">
                    ${item.semester_name}
                </option>`;
        });

        semester.selectedIndex = 0;

        history.replaceState(
          {},
          "",
          "?department=" + department.value + "&semester=" + semester.value,
        );

        document.getElementById("department_id").value = department.value;

        document.getElementById("semester_id").value = semester.value;

        loadRoutine();
      })

      .catch(function () {
        alert("Unable to load semesters.");
      });
  }

  /*=====================================================
        LOAD ROUTINE
    =====================================================*/

  function loadRoutine() {
    
    const departmentId = department.value;

    const semesterId = semester.value;

    department.value = departmentId;
    semester.value = semesterId;

    history.replaceState(
      {},
      "",
      "?department=" + departmentId + "&semester=" + semesterId,
    );

    // console.log(
    //   "Department:",
    //   department.value,
    //   department.options[department.selectedIndex].text,
    // );

    // console.log(
    //   "Semester:",
    //   semester.value,
    //   semester.options[semester.selectedIndex].text,
    // );

    fetch(
  "../ajax/get_routine.php?department_id=" +
    departmentId +
    "&semester_id=" +
    semesterId,
)
  .then((response) => response.text())

  .then(function (html) {

    routineTable.innerHTML = html;

    // Update department card
    document.getElementById("infoDepartment").textContent =
      department.options[department.selectedIndex].text;

    // Update semester card
    document.getElementById("infoSemester").textContent =
      semester.options[semester.selectedIndex].text;


    // ==========================================
    // UPDATE TOTAL CLASSES
    // ==========================================

    fetch(
      "../ajax/get_routine_count.php?department_id=" +
        encodeURIComponent(departmentId) +
        "&semester_id=" +
        encodeURIComponent(semesterId)
    )
      .then((response) => response.text())
      .then(function (count) {

        document.getElementById("infoTotalClasses").textContent =
          count.trim();

      });


    // Re-bind buttons
    bindButtons();

  })

      .catch(function () {
        routineTable.innerHTML = `<tr>

                <td colspan="7"

                class="text-center text-danger">

                Unable to load routine.

                </td>

            </tr>`;
      });


  }
  /*=====================================================
        BIND ALL BUTTONS
    =====================================================*/

  function bindButtons() {
    bindAddButtons();

    bindEditButtons();

    bindDeleteButtons();
  }

  /*=====================================================
        ADD SLOT BUTTON
    =====================================================*/

  function bindAddButtons() {
    document
      .querySelectorAll(".addSlot")

      .forEach(function (btn) {
        btn.onclick = function () {
          document.getElementById("addClassForm").reset();

          document.getElementById("department_id").value = department.value;

          document.getElementById("semester_id").value = semester.value;

          document.getElementById("day").value = this.dataset.day;

          document.getElementById("start_time").value = this.dataset.start;

          document.getElementById("end_time").value = this.dataset.end;

          addModal.show();
        };
      });
  }

  /*=====================================================
        EDIT BUTTON
    =====================================================*/

  function bindEditButtons() {
    document
      .querySelectorAll(".editClass")

      .forEach(function (btn) {
        btn.onclick = function () {
          const id = this.dataset.id;

          fetch("../ajax/edit_class.php?id=" + id)
            .then((response) => response.text())

            .then(function (html) {
              document.getElementById("editFormContent").innerHTML = html;

              editModal.show();
            })

            .catch(function () {
              alert("Unable to load class details.");
            });
        };
      });
  }

  /*=====================================================
        DELETE BUTTON
    =====================================================*/

  let deleteRoutineId = 0;

  function bindDeleteButtons() {
    document
      .querySelectorAll(".deleteClass")

      .forEach(function (btn) {
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
      fetch(
        "../ajax/delete_routine.php",

        {
          method: "POST",

          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },

          body: "routine_id=" + deleteRoutineId,
        },
      )
        .then((response) => response.text())

        .then(function (result) {
          deleteModal.hide();

          deleteRoutineId = 0;

          loadRoutine();
        })

        .catch(function () {
          alert("Unable to delete class.");
        });
    });
  /*=====================================================
        ADD CLASS FORM
    =====================================================*/

  document
    .getElementById("addClassForm")

    .addEventListener("submit", function (e) {
      e.preventDefault();

      const form = this;

      const saveBtn = form.querySelector("button[type='submit']");

      saveBtn.disabled = true;

      saveBtn.innerHTML =
        '<span class="spinner-border spinner-border-sm"></span> Saving...';

      const formData = new FormData(form);

      fetch(
        "../ajax/save_routine.php",

        {
          method: "POST",

          body: formData,
        },
      )
        .then((response) => response.json())

        .then(function (result) {
          saveBtn.disabled = false;

          saveBtn.innerHTML = '<i class="bi bi-check-circle"></i> Save Class';

          if (result.status === "success") {
            addModal.hide();

            form.reset();

            loadRoutine();
          } else {
            alert(result.message);
          }
        })

        .catch(function () {
          saveBtn.disabled = false;

          saveBtn.innerHTML = '<i class="bi bi-check-circle"></i> Save Class';

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

      const form = this;

      const updateBtn = form.querySelector("button[type='submit']");

      updateBtn.disabled = true;

      updateBtn.innerHTML =
        '<span class="spinner-border spinner-border-sm"></span> Updating...';

      const formData = new FormData(form);

      fetch(
        "../ajax/update_routine.php",

        {
          method: "POST",

          body: formData,
        },
      )
        .then((response) => response.json())

        .then(function (result) {
          updateBtn.disabled = false;

          updateBtn.innerHTML = "Update Class";

          if (result.status === "success") {
            editModal.hide();

            loadRoutine();
          } else {
            alert(result.message);
          }
        })

        .catch(function () {
          updateBtn.disabled = false;

          updateBtn.innerHTML = "Update Class";

          alert("Unable to update class.");
        });
    });

  /*=====================================================
        INITIAL PAGE LOAD
    =====================================================*/

  loadRoutine();
});
