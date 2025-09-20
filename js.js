"use strict";

document.addEventListener("DOMContentLoaded", () => {
  // initial load
  loadPrograms();
  loadStudents();
  loadYears();

  // Buttons
  document.getElementById("showStudentsBtn").addEventListener("click", loadStudents);
  document.getElementById("clearStudentBtn").addEventListener("click", clearStudentForm);
  document.getElementById("clearProgramBtn").addEventListener("click", clearProgramForm);

  // Student form
  document.getElementById("studentForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const studId = fd.get("stud_id");
    const url = studId ? "api/Student/updateStudent.php" : "api/Student/addStudent.php";

    try {
      const res = await fetch(url, { method: "POST", body: fd });
      const resp = await safeJson(res);
      if (!resp) return;
      alert(resp.message || (resp.success ? "Saved" : "Error"));
      if (resp.success) {
        e.target.reset();
        loadStudents();
      }
    } catch (err) {
      console.error(err);
      alert("Request failed");
    }
  });

  // Program form
  document.getElementById("programForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const programId = fd.get("program_id");
    const url = programId ? "api/Program/updateProgram.php" : "api/Program/addProgram.php";

    try {
      const res = await fetch(url, { method: "POST", body: fd });
      const resp = await safeJson(res);
      if (!resp) return;
      alert(resp.message || (resp.success ? "Saved" : "Error"));
      if (resp.success) {
        e.target.reset();
        loadPrograms();
      }
    } catch (err) {
      console.error(err);
      alert("Request failed");
    }
  });

  // Year form
  document.getElementById("yearForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const yearId = fd.get("year_id");
    const url = yearId ? "api/Year/updateYear.php" : "api/Year/addYear.php";

    try {
      const res = await fetch(url, { method: "POST", body: fd });
      const resp = await res.json();
      alert(resp.message || (resp.success ? "Saved" : "Error"));
      if (resp.success) {
        e.target.reset();
        loadYears();
      }
    } catch (err) {
      console.error("Year request failed:", err);
    }
  });
});

// ---------------- Helpers ----------------
async function safeJson(response) {
  const text = await response.text();
  try {
    return JSON.parse(text);
  } catch (e) {
    console.error("Invalid JSON from server:", text);
    return null;
  }
}

// ---------------- Programs ----------------
async function loadPrograms() {
  try {
    const res = await fetch("api/Program/getProgram.php");
    const resp = await res.json();
    const arr = resp.data || [];
    const sel = document.getElementById("program_id");
    const tbl = document.getElementById("programTableBody");

    sel.innerHTML = '<option value="">-- Select Program --</option>';
    tbl.innerHTML = "";

    if (!arr.length) {
      tbl.innerHTML = '<tr><td colspan="3">No programs found.</td></tr>';
      return;
    }

    arr.forEach(p => {
      const id = Number(p.program_id);
      const name = p.program_name ?? "";

      const opt = document.createElement("option");
      opt.value = id;
      opt.textContent = name;
      sel.appendChild(opt);

      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${id}</td>
        <td>${name}</td>
        <td>
          <button type="button" onclick="editProgram(${id}, '${name}')">Edit</button>
          <button type="button" onclick="deleteProgram(${id})">Delete</button>
        </td>`;
      tbl.appendChild(tr);
    });
  } catch (err) {
    console.error("loadPrograms error:", err);
  }
}

function editProgram(id, name) {
  document.getElementById("program_id").value = id;
  document.getElementById("program_name").value = name;
  document.getElementById("program_name").focus();
}

function clearProgramForm() {
  document.getElementById("programForm").reset();
  document.getElementById("program_id").value = "";
}

async function deleteProgram(id) {
  if (!confirm("Delete program?")) return;
  try {
    const res = await fetch("api/Program/deleteProgram.php?id=" + encodeURIComponent(id));
    const resp = await res.json();
    if (!resp.success) alert(resp.message || "Delete failed");
    loadPrograms();
    loadStudents();
  } catch (err) {
    console.error(err);
    alert("Delete failed");
  }
}

// ---------------- Students ----------------
async function loadStudents() {
  try {
    const res = await fetch("api/Student/getStudent.php");
    const resp = await safeJson(res);
    const arr = resp?.data || [];
    const tbody = document.getElementById("studentTableBody");

    tbody.innerHTML = "";

    if (!arr.length) {
      tbody.innerHTML = '<tr><td colspan="5">No students found.</td></tr>';
      return;
    }

    arr.forEach(s => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${s.stud_id ?? ""}</td>
        <td>${s.name ?? ""}</td>
        <td>${s.program_name ?? ""}</td>
        <td>${s.allowance ?? ""}</td>
        <td>
          <button type="button" onclick="editStudent(${s.stud_id}, '${s.name}', ${s.program_id}, ${s.allowance ?? 0})">Edit</button>
          <button type="button" onclick="deleteStudent(${s.stud_id})">Delete</button>
        </td>`;
      tbody.appendChild(tr);
    });
  } catch (err) {
    console.error(err);
  }
}

function clearStudentForm() {
  document.getElementById("studentForm").reset();
  document.getElementById("stud_id").value = "";
}

function editStudent(id, name, programId, allowance) {
  document.getElementById("stud_id").value = id;
  document.getElementById("name").value = name;
  document.getElementById("program_id").value = programId;
  document.getElementById("allowance").value = allowance;
}

async function deleteStudent(id) {
  if (!confirm("Delete student?")) return;
  try {
    const res = await fetch("api/Student/deleteStudent.php?id=" + encodeURIComponent(id));
    const resp = await safeJson(res);
    if (resp && !resp.success) alert(resp.message || "Failed");
    loadStudents();
  } catch (err) {
    console.error(err);
    alert("Delete failed");
  }
}

// ---------------- Years ----------------
async function loadYears() {
  try {
    const res = await fetch("api/Year/getYear.php");
    const resp = await res.json();
    const arr = resp.data || [];
    const tbody = document.getElementById("yearTableBody");
    tbody.innerHTML = "";

    if (!arr.length) {
      tbody.innerHTML = '<tr><td colspan="4">No years found.</td></tr>';
      return;
    }

    arr.forEach((y) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${y.year_id}</td>
        <td>${y.year_from}</td>
        <td>${y.year_to}</td>
        <td>
          <button type="button" onclick="editYear(${y.year_id}, ${y.year_from}, ${y.year_to})">Edit</button>
          <button type="button" onclick="deleteYear(${y.year_id})">Delete</button>
        </td>`;
      tbody.appendChild(tr);
    });
  } catch (err) {
    console.error(err);
  }
}

function editYear(id, from, to) {
  document.getElementById("year_id").value = id;
  document.getElementById("year_from").value = from;
  document.getElementById("year_to").value = to;
}

async function deleteYear(id) {
  if (!confirm("Delete year?")) return;
  try {
    const res = await fetch("api/Year/deleteYear.php?id=" + encodeURIComponent(id));
    const resp = await res.json();
    if (!resp.success) alert(resp.message || "Delete failed");
    loadYears();
  } catch (err) {
    console.error(err);
  }
}
// ---------------- Subject Management ----------------

// Load semesters for dropdown
async function loadSemesters() {
  try {
    const res = await fetch("api/Semester/getSemester.php");
    const resp = await res.json();
    const arr = resp.data || [];
    const sel = document.getElementById("sem_id");
    sel.innerHTML = '<option value="">-- Select Semester --</option>';
    arr.forEach(s => {
      const opt = document.createElement("option");
      opt.value = s.sem_id;
      opt.textContent = s.sem_name;
      sel.appendChild(opt);
    });
  } catch (err) {
    console.error("loadSemesters failed:", err);
  }
}

// Handle add / update subject
document.getElementById("subjectForm").addEventListener("submit", async (e) => {
  e.preventDefault();
  const data = {
    subject_id: document.getElementById("subject_id").value || 0,
    subject_name: document.getElementById("subject_name").value.trim(),
    sem_id: document.getElementById("sem_id").value
  };
  const url = data.subject_id ? "api/Subject/updateSubject.php" : "api/Subject/addSubject.php";

  try {
    const res = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data)
    });
    const resp = await res.json();
    alert(resp.message || (resp.success ? "Saved" : "Error"));
    if (resp.success) {
      e.target.reset();
      document.getElementById("subject_id").value = "";
      loadSubjects();
    }
  } catch (err) {
    console.error("Subject save failed:", err);
  }
});

document.getElementById("clearSubjectBtn").addEventListener("click", () => {
  document.getElementById("subjectForm").reset();
  document.getElementById("subject_id").value = "";
});

// Load subjects
async function loadSubjects() {
  try {
    const res = await fetch("api/Subject/getSubject.php");
    const resp = await res.json();
    const arr = resp.data || [];
    const tbody = document.getElementById("subjectTableBody");
    tbody.innerHTML = "";

    if (!arr.length) {
      tbody.innerHTML = '<tr><td colspan="4">No subjects found.</td></tr>';
      return;
    }

    arr.forEach(sub => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${sub.subject_id}</td>
        <td>${sub.subject_name}</td>
        <td>${sub.sem_name ?? ""}</td>
        <td>
          <button type="button" onclick="editSubject(${sub.subject_id}, '${sub.subject_name}', ${sub.sem_id})">Edit</button>
          <button type="button" onclick="deleteSubject(${sub.subject_id})">Delete</button>
        </td>`;
      tbody.appendChild(tr);
    });
  } catch (err) {
    console.error("loadSubjects failed:", err);
  }
}

function editSubject(id, name, sem) {
  document.getElementById("subject_id").value = id;
  document.getElementById("subject_name").value = name;
  document.getElementById("sem_id").value = sem;
}

async function deleteSubject(id) {
  if (!confirm("Delete subject?")) return;
  try {
    const res = await fetch("api/Subject/deleteSubject.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ subject_id: id })
    });
    const resp = await res.json();
    alert(resp.message || (resp.success ? "Deleted" : "Error"));
    if (resp.success) loadSubjects();
  } catch (err) {
    console.error("deleteSubject failed:", err);
  }
}

// Auto-load semesters + subjects when page opens
document.addEventListener("DOMContentLoaded", () => {
  loadSemesters();
  loadSubjects();
});

// ---------------- Enrollment Management ----------------

// Load dropdown data for enrollment form
async function loadEnrollmentDropdowns() {
  try {
    const studentRes = await fetch("api/Student/getStudent.php");
    const studentResp = await safeJson(studentRes);
    const students = studentResp?.data || [];

    const subjectRes = await fetch("api/Subject/getSubject.php");
    const subjectResp = await safeJson(subjectRes);
    const subjects = subjectResp?.data || [];

    const studentSel = document.getElementById("enroll_student");
    const subjectSel = document.getElementById("enroll_subject");

    studentSel.innerHTML = '<option value="">-- Select Student --</option>';
    subjectSel.innerHTML = '<option value="">-- Select Subject --</option>';

    students.forEach(s => {
      const opt = document.createElement("option");
      opt.value = s.stud_id;
      opt.textContent = s.name;
      studentSel.appendChild(opt);
    });

    subjects.forEach(sub => {
      const opt = document.createElement("option");
      opt.value = sub.subject_id;
      opt.textContent = sub.subject_name;
      subjectSel.appendChild(opt);
    });
  } catch (err) {
    console.error("loadEnrollmentDropdowns failed:", err);
  }
}

// Load enrollments
async function loadEnrollments() {
  try {
    const res = await fetch("api/Enrollment/getEnrollments.php");
    const resp = await safeJson(res);
    const arr = resp?.data || [];
    const tbody = document.getElementById("enrollmentTableBody");

    tbody.innerHTML = "";

    if (!arr.length) {
      tbody.innerHTML = '<tr><td colspan="4">No enrollments found.</td></tr>';
      return;
    }

    arr.forEach(e => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${e.enrollment_id}</td>
        <td>${e.student_name ?? ""}</td>
        <td>${e.subject_name ?? ""}</td>
        <td>
          <button type="button" onclick="editEnrollment(${e.enrollment_id}, ${e.student_id}, ${e.subject_id})">Edit</button>
          <button type="button" onclick="deleteEnrollment(${e.enrollment_id})">Remove</button>
        </td>`;
      tbody.appendChild(tr);
    });
  } catch (err) {
    console.error("loadEnrollments failed:", err);
  }
}

// Save enrollment (add or update)
document.getElementById("enrollmentForm").addEventListener("submit", async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const id = fd.get("enrollment_id");
  const url = id ? "api/Enrollment/updateEnrollment.php" : "api/Enrollment/enrollStudent.php";

  try {
    const res = await fetch(url, { method: "POST", body: fd });
    const resp = await safeJson(res);
    alert(resp?.message || (resp?.success ? "Saved" : "Error"));
    if (resp?.success) {
      e.target.reset();
      document.getElementById("enrollment_id").value = "";
      loadEnrollments();
    }
  } catch (err) {
    console.error("saveEnrollment failed:", err);
  }
});

// Edit enrollment
function editEnrollment(id, studentId, subjectId) {
  document.getElementById("enrollment_id").value = id;
  document.getElementById("enroll_student").value = studentId;
  document.getElementById("enroll_subject").value = subjectId;
}

// Delete enrollment
async function deleteEnrollment(id) {
  if (!confirm("Remove this enrollment?")) return;
  try {
    const res = await fetch("api/Enrollment/removeEnrollment.php?id=" + encodeURIComponent(id));
    const resp = await safeJson(res);
    alert(resp?.message || (resp?.success ? "Deleted" : "Error"));
    if (resp?.success) loadEnrollments();
  } catch (err) {
    console.error("deleteEnrollment failed:", err);
  }
}

// Clear form
document.getElementById("clearEnrollmentBtn").addEventListener("click", () => {
  document.getElementById("enrollmentForm").reset();
  document.getElementById("enrollment_id").value = "";
});

// Auto-load on page ready
document.addEventListener("DOMContentLoaded", () => {
  loadEnrollmentDropdowns();
  loadEnrollments();
});


