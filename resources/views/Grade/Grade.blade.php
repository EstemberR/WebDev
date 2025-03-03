@extends('layouts.dashboardTemp')
@section('title', 'Grades')
@section('Pages', 'Grades')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-3">
                        <h6>Student Grades</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table id="gradesTable" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->student_id }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>
                                            <button class="btn bg-gradient-warning btn-sm" 
                                                    onclick="showGradesModal({{ $student->id }}, '{{ $student->name }}')">
                                                Manage Grades
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grades Modal -->
<div class="modal fade" id="gradesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Grades for <span id="studentName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="subjectsTable">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Midterm</th>
                                <th>Finals</th>
                                <th>Average</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="subjectsTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Grades Modal -->
<div class="modal fade" id="manageGradesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Subject Grades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="gradesForm">
                    <input type="hidden" id="gradeStudentId" name="student_id">
                    <input type="hidden" id="gradeSubjectId" name="subject_id">
                    
                    <div class="mb-3">
                        <label for="midterm" class="form-label">Midterm Grade</label>
                        <input type="number" class="form-control" id="midterm" name="midterm" 
                               step="0.01" min="1" max="5" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="finals" class="form-label">Finals Grade</label>
                        <input type="number" class="form-control" id="finals" name="finals" 
                               step="0.01" min="1" max="5" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn bg-gradient-warning" onclick="saveGrades()">Save Grades</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
console.log('Document ready, initializing DataTable and modal events');
$(document).ready(function() {
    console.log('Document ready, initializing DataTable and modal events');
    $('#gradesTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "info": true,
        "lengthChange": true,
        "searching": true,
        language: {
            paginate: {
                previous: '⟵', 
                next: '⟶'      
            }
        }
    });

});
function showGradesModal(studentId, studentName) {
    document.getElementById('studentName').textContent = studentName;
    
    // Initialize the modal first
    const gradesModal = new bootstrap.Modal(document.getElementById('gradesModal'));
    
    fetch(`/grades/subjects/${studentId}`)
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.error || 'Failed to load student grades');
            });
        }
        return response.json();
    })
        .then(data => {
            const tbody = document.getElementById('subjectsTableBody');
            tbody.innerHTML = '';
            
            data.forEach(subject => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${subject.name}</td>
                    <td>${subject.grade ? subject.grade.midterm : '-'}</td>
                    <td>${subject.grade ? subject.grade.finals : '-'}</td>
                    <td>${subject.grade ? subject.grade.average : '-'}</td>
                    <td>
                        ${subject.grade ? 
                            `<span class="badge bg-${subject.grade.remarks === 'Passed' ? 'success' : 'danger'}">
                                ${subject.grade.remarks}
                            </span>` : '-'}
                    </td>
                    <td>
                        <button class="btn bg-gradient-warning btn-sm" 
                                onclick="manageGrades(${studentId}, ${subject.id}, 
                                         '${subject.grade ? subject.grade.midterm : ''}', 
                                         '${subject.grade ? subject.grade.finals : ''}')">
                            ${subject.grade ? 'Edit' : 'Add'} Grades
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            gradesModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load student grades'
            });
        });
}

function manageGrades(studentId, subjectId, midterm, finals) {
    // Set the values
    document.getElementById('gradeStudentId').value = studentId;
    document.getElementById('gradeSubjectId').value = subjectId;
    document.getElementById('midterm').value = midterm || '';
    document.getElementById('finals').value = finals || '';
    
    // Hide first modal
    const gradesModal = bootstrap.Modal.getInstance(document.getElementById('gradesModal'));
    if (gradesModal) {
        gradesModal.hide();
    }
    
    // Show second modal
    const manageGradesModal = new bootstrap.Modal(document.getElementById('manageGradesModal'));
    manageGradesModal.show();
    
    // Log for debugging
    console.log('Managing grades for student:', studentId, 'subject:', subjectId);
}

function saveGrades() {
    const form = document.getElementById('gradesForm');
    const formData = new FormData(form);
    
    fetch('/grades/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close the manage grades modal
            bootstrap.Modal.getInstance(document.getElementById('manageGradesModal')).hide();
            
            // Refresh the grades table
            const studentId = document.getElementById('gradeStudentId').value;
            const studentName = document.getElementById('studentName').textContent;
            showGradesModal(studentId, studentName);
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to save grades'
        });
    });
}
</script>
@endpush

<style>
/* Keep your existing styles and add these DataTables specific styles */
.dataTables_wrapper {
    padding: 20px;
}

.dataTables_filter input {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 6px 12px;
    margin-left: 8px;
}

.dataTables_length select {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 6px 30px 6px 10px;
    margin: 0 8px;
}

/* Keep your existing button and table styles */
.bg-gradient-warning {
    background: linear-gradient(310deg, #ea580c, #facc15);
    color: white;
    border: none;
}

.bg-gradient-warning:hover {
    background: linear-gradient(310deg, #c2410c, #eab308);
    transform: translateY(-1px);
}

.bg-gradient-danger {
    background: linear-gradient(310deg, #dc2626, #ef4444);
    color: white;
    border: none;
}

.bg-gradient-danger:hover {
    background: linear-gradient(310deg, #b91c1c, #dc2626);
    transform: translateY(-1px);
}

.btn-sm {
    margin: 0 2px;
}

/* Hide the original text */
.paginate_button.previous span,
.paginate_button.next span {
    display: none;
}

/* Adjust padding for icon buttons */
.paginate_button.previous,
.paginate_button.next {
    padding: 8px 16px;
}

/* Pagination arrow styling */
.paginate_button.previous,
.paginate_button.next {
    font-size: 1.5rem;
    font-weight: bold;
    display: flex;
    align-items: center;
    height: 100%;
    padding: 0 16px;
    margin: 0;
}

/* Ensure arrows are centered vertically with numbers */
.dataTables_paginate {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 5px;
    height: 38px; /* Fixed height for pagination container */
}

.paginate_button {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    min-width: 38px; /* Fixed width for consistent spacing */
    padding: 0 8px;
    margin: 0 2px;
    border-radius: 4px;
    cursor: pointer;
    background: transparent;
    border: none;
    color: #333;
    transition: all 0.3s ease;
}

.paginate_button.current {
    background: linear-gradient(310deg, #ea580c, #facc15);
    color: white;
}

.paginate_button:hover:not(.current) {
    background: #f5f5f5;
    color: #ea580c;
}

/* Modal stacking */
.modal {
    background: rgba(0, 0, 0, 0.5);
}

#gradesModal {
    z-index: 1050;
}

#manageGradesModal {
    z-index: 1052;
}

.modal-backdrop {
    z-index: 1040;
}

.modal-backdrop + .modal-backdrop {
    z-index: 1051;
}

.modal.fade.show {
    display: block !important;
    opacity: 1 !important;
}
</style>

@endsection