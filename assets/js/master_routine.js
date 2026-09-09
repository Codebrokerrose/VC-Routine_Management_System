// ======================================================
// MASTER ROUTINE.JS
// ======================================================

let addModal;
let editModal;
let deleteModal;
let exportModal;

let deleteRoutineId = 0;

document.addEventListener("DOMContentLoaded", function () {

    // ==================================================
    // BOOTSTRAP MODALS
    // ==================================================

    addModal = new bootstrap.Modal(
        document.getElementById("addClassModal")
    );

    editModal = new bootstrap.Modal(
        document.getElementById("editClassModal")
    );

    deleteModal = new bootstrap.Modal(
        document.getElementById("deleteModal")
    );

    // Export modal
    const exportModalElement =
        document.getElementById("exportMasterRoutineModal");

    if (exportModalElement) {
        exportModal = new bootstrap.Modal(exportModalElement);
    }

    bindEvents();

    loadMasterRoutine();
});


// ======================================================
// EVENT LISTENERS
// ======================================================

function bindEvents() {

    document
        .getElementById("session")
        .addEventListener("change", sessionChanged);

    document
        .getElementById("course")
        .addEventListener("change", courseChanged);

    document
        .getElementById("semester")
        .addEventListener("change", semesterChanged);


    // ==================================================
    // ADD CLASS BUTTON
    // ==================================================

    const addBtn = document.getElementById("addClassBtn");

    if (addBtn) {

        addBtn.addEventListener("click", function () {

            clearAddForm();

            addModal.show();

        });

    }


    // ==================================================
    // EXPORT MASTER ROUTINE BUTTON
    // ==================================================

    const exportMasterRoutineBtn =
        document.getElementById("exportMasterRoutineBtn");

    if (exportMasterRoutineBtn) {

        exportMasterRoutineBtn.addEventListener(
            "click",
            function () {

                if (exportModal) {
                    exportModal.show();
                }

            }
        );

    }


    // ==================================================
    // DOWNLOAD MASTER ROUTINE BUTTON
    // ==================================================

    const downloadMasterRoutineBtn =
        document.getElementById("downloadMasterRoutineBtn");

    if (downloadMasterRoutineBtn) {

        downloadMasterRoutineBtn.addEventListener(
            "click",
            function () {

                const format =
                    document.getElementById(
                        "masterRoutineExportFormat"
                    ).value;

                const session =
                    document.getElementById("session").value;

                const course =
                    document.getElementById("course").value;

                const semester =
                    document.getElementById("semester").value;


                // ------------------------------------------
                // Validate filters
                // ------------------------------------------

                if (!session || !course || !semester) {

                    alert(
                        "Please select Session, Course and Semester first."
                    );

                    return;

                }


                // ------------------------------------------
                // Export URL
                // ------------------------------------------

                let exportUrl = "";


                if (format === "excel") {

                    exportUrl =
                        "../export/master_routine_excel.php";

                }
                else if (format === "word") {

                    exportUrl =
                        "../export/master_routine_word.php";

                }
                else if (format === "pdf") {

                    exportUrl =
                        "../export/master_routine_pdf.php";

                }
                else {

                    alert("Invalid export format.");

                    return;

                }


                // ------------------------------------------
                // Add filters
                // ------------------------------------------

                exportUrl +=
                    "?session=" +
                    encodeURIComponent(session) +

                    "&course=" +
                    encodeURIComponent(course) +

                    "&semester=" +
                    encodeURIComponent(semester);


                // ------------------------------------------
                // Start download
                // ------------------------------------------

                window.location.href = exportUrl;


                // Close modal
                if (exportModal) {
                    exportModal.hide();
                }

            }
        );

    }

}


// ======================================================
// SESSION CHANGE
// ======================================================

function sessionChanged() {

    const session =
        document.getElementById("session").value;

    const semester =
        document.getElementById("semester");

    semester.innerHTML = "";


    if (session === "Odd") {

        semester.innerHTML = `
            <option value="1">Semester I</option>
            <option value="3">Semester III</option>
            <option value="5">Semester V</option>
        `;

    }
    else {

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

    const session =
        document.getElementById("session").value;

    const course =
        document.getElementById("course").value;

    const semester =
        document.getElementById("semester").value;


    // Main hidden fields

    document.getElementById(
        "session_type"
    ).value = session;

    document.getElementById(
        "course_type"
    ).value = course;

    document.getElementById(
        "semester_id"
    ).value = semester;


    // Add form hidden fields

    document.getElementById(
        "add_session_type"
    ).value = session;

    document.getElementById(
        "add_course_type"
    ).value = course;

    document.getElementById(
        "add_semester"
    ).value = semester;


    // Update URL

    history.replaceState(
        {},
        "",
        `?session=${session}&course=${course}&semester=${semester}`
    );


    updateInfoCards();

}


// ======================================================
// UPDATE INFO CARDS
// ======================================================

function updateInfoCards() {

    const session =
        document.getElementById("session").value;

    const course =
        document.getElementById("course").value;

    const semester =
        document.getElementById("semester").value;


    document.getElementById(
        "infoSession"
    ).textContent = session;


    document.getElementById(
        "infoCourse"
    ).textContent = course;


    document.getElementById(
        "infoSemester"
    ).textContent = "Semester " + semester;

}


// ======================================================
// LOAD MASTER ROUTINE
// ======================================================

function loadMasterRoutine() {

    const session =
        document.getElementById("session").value;

    const course =
        document.getElementById("course").value;

    const semester =
        document.getElementById("semester").value;


    fetch(
        `../ajax/get_master_routine.php?session=${encodeURIComponent(session)}&course=${encodeURIComponent(course)}&semester=${encodeURIComponent(semester)}`
    )

    .then(response => response.text())

    .then(data => {

        document.getElementById(
            "routineBody"
        ).innerHTML = data;


        bindRoutineButtons();

    })

    .catch(error => {

        console.error(
            "Error loading master routine:",
            error
        );

    });

}


// ======================================================
// BIND ADD / EDIT / DELETE BUTTONS
// ======================================================

function bindRoutineButtons() {

    // Add slot buttons

    document
        .querySelectorAll(".addSlot")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                function () {

                    clearAddForm();


                    document.getElementById(
                        "day"
                    ).value = this.dataset.day;


                    document.getElementById(
                        "start_time"
                    ).value = this.dataset.start;


                    document.getElementById(
                        "end_time"
                    ).value = this.dataset.end;


                    addModal.show();

                }
            );

        });


    bindEditButtons();

    bindDeleteButtons();

}


// ======================================================
// CLEAR ADD FORM
// ======================================================

function clearAddForm() {

    document
        .getElementById("addClassForm")
        .reset();


    updateHiddenFields();

}


// ======================================================
// EDIT BUTTONS
// ======================================================

function bindEditButtons() {

    document
        .querySelectorAll(".editClass")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                function () {

                    const id =
                        this.dataset.id;


                    fetch(
                        "../ajax/edit_master_routine.php?id=" +
                        encodeURIComponent(id)
                    )

                    .then(response => response.text())

                    .then(html => {

                        document.getElementById(
                            "editFormContent"
                        ).innerHTML = html;


                        bindUpdateForm();


                        editModal.show();

                    })

                    .catch(error => {

                        console.error(
                            "Edit error:",
                            error
                        );

                    });

                }
            );

        });

}


// ======================================================
// DELETE BUTTONS
// ======================================================

function bindDeleteButtons() {

    document
        .querySelectorAll(".deleteClass")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                function () {

                    deleteRoutineId =
                        this.dataset.id;


                    deleteModal.show();

                }
            );

        });

}


// ======================================================
// SAVE NEW CLASS
// ======================================================

document
    .getElementById("addClassForm")
    .addEventListener(
        "submit",
        function (e) {

            e.preventDefault();


            const formData =
                new FormData(this);


            fetch(
                "../ajax/save_master_routine.php",
                {
                    method: "POST",
                    body: formData
                }
            )

            .then(response =>
                response.json()
            )

            .then(data => {

                if (data.success) {

                    addModal.hide();

                    loadMasterRoutine();

                    alert(
                        "Class added successfully."
                    );

                }
                else {

                    alert(
                        data.message
                    );

                }

            })

            .catch(error => {

                console.error(
                    "Save error:",
                    error
                );

                alert(
                    "Unable to save class."
                );

            });

        }
    );


// ======================================================
// UPDATE CLASS
// ======================================================

function bindUpdateForm() {

    const form =
        document.getElementById(
            "editClassForm"
        );


    if (!form) {
        return;
    }


    form.addEventListener(
        "submit",
        function (e) {

            e.preventDefault();


            const formData =
                new FormData(form);


            fetch(
                "../ajax/update_master_routine.php",
                {
                    method: "POST",
                    body: formData
                }
            )

            .then(response =>
                response.json()
            )

            .then(data => {

                if (data.success) {

                    editModal.hide();

                    loadMasterRoutine();

                    alert(
                        "Routine updated successfully."
                    );

                }
                else {

                    alert(
                        data.message
                    );

                }

            })

            .catch(error => {

                console.error(
                    "Update error:",
                    error
                );

                alert(
                    "Unable to update routine."
                );

            });

        }
    );

}


// ======================================================
// DELETE CLASS
// ======================================================

document
    .getElementById("confirmDelete")
    .addEventListener(
        "click",
        function () {

            fetch(
                "../ajax/delete_master_routine.php",
                {
                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/x-www-form-urlencoded"
                    },

                    body:
                        "id=" +
                        encodeURIComponent(
                            deleteRoutineId
                        )
                }
            )

            .then(response =>
                response.json()
            )

            .then(data => {

                if (data.success) {

                    deleteModal.hide();

                    loadMasterRoutine();

                    alert(
                        "Class deleted successfully."
                    );

                }
                else {

                    alert(
                        data.message
                    );

                }

            })

            .catch(error => {

                console.error(
                    "Delete error:",
                    error
                );

                alert(
                    "Unable to delete routine."
                );

            });

        }
    );


// ======================================================
// REFRESH TOTAL CLASS COUNT
// ======================================================

function refreshTotalClasses() {

    const session =
        document.getElementById("session").value;

    const course =
        document.getElementById("course").value;

    const semester =
        document.getElementById("semester").value;


    fetch(
        `../ajax/get_master_total.php?session=${encodeURIComponent(session)}&course=${encodeURIComponent(course)}&semester=${encodeURIComponent(semester)}`
    )

    .then(response =>
        response.text()
    )

    .then(count => {

        const totalElement =
            document.getElementById(
                "infoTotalClasses"
            );


        if (totalElement) {

            totalElement.textContent =
                count.trim();

        }

    })

    .catch(error => {

        console.error(
            "Total count error:",
            error
        );

    });

}


// ======================================================
// LOAD + TOTAL COUNT
// ======================================================

const originalLoad =
    loadMasterRoutine;


loadMasterRoutine = function () {

    originalLoad();

    refreshTotalClasses();

};


// ======================================================
// MASTER ROUTINE NOTE
// ======================================================

$(document).on(
    "click",
    "#updateNoteBtn",
    function () {

        $.ajax({

            url:
                "../ajax/save_master_note.php",

            type:
                "POST",

            data: {

                note:
                    $("#masterRoutineNote").val()

            },

            success:
                function (response) {

                    if (
                        response.trim() ===
                        "success"
                    ) {

                        alert(
                            "Note updated successfully."
                        );

                    }
                    else {

                        alert(
                            response
                        );

                    }

                },

            error:
                function (xhr) {

                    alert(
                        xhr.responseText
                    );

                }

        });

    }
);