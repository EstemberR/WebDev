@extends('layouts.dashboardTemp')
@section('title', 'Enrollment')
@section('Pages', 'Enrollment')
@section('content')
<!-- Add these lines for DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-3">
                        <h6>Available Students</h6>
                       
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table id="availableStudentsTable" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->student_id }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>
                                            <button class="btn bg-gradient-warning btn-sm" onclick="enrollStudent({{ $student->id }})">
                                                Enroll
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

        <!-- Enrolled Students Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-3">
                        <h6>Enrolled Students</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table id="enrolledStudentsTable" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Enrolled Subjects</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrolledStudents as $student)
                                    <tr>
                                        <td>{{ $student->student_id }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>
                                            @foreach($student->subjects as $subject)
                                                <span class="badge bg-primary">{{ $subject->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <button class="btn bg-gradient-primary btn-sm" onclick="manageSubjects({{ $student->id }})">
                                                Manage Subjects
                                            </button>
                                            <button class="btn bg-gradient-danger btn-sm" onclick="confirmUnenroll({{ $student->id }})">
                                                <i class="fas fa-user-minus"></i> Unenroll
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

<!-- Manage Subjects Modal -->
<div class="modal fade" id="manageSubjectsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Subjects</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="subjectForm" action="{{ route('enrollment.subjects') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" id="modalStudentId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Subjects to Enroll</label>
                        @foreach($subjects as $subject)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="subjects[]" 
                                   value="{{ $subject->id }}" id="subject{{ $subject->id }}">
                            <label class="form-check-label" for="subject{{ $subject->id }}">
                                {{ $subject->name }} ({{ $subject->subject_code }})
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-gradient-warning">Enroll Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Enrollment Modal -->
<div class="modal fade" id="enrollmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enroll Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="enrollmentForm" action="{{ route('enrollment.enroll') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" id="enrollStudentId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Subjects</label>
                        @foreach($subjects as $subject)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="subjects[]" 
                                   value="{{ $subject->id }}" id="enrollSubject{{ $subject->id }}">
                            <label class="form-check-label" for="enrollSubject{{ $subject->id }}">
                                {{ $subject->name }} ({{ $subject->subject_code }})
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-gradient-warning">Enroll</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-gradient-warning {
        background: linear-gradient(310deg, #ea580c, #facc15);
        color: white;
        border: none;
    }
    
    .bg-gradient-warning:hover {
        background: linear-gradient(310deg, #c2410c, #eab308);
        transform: translateY(-1px);
    }

    .bg-gradient-primary {
        background: linear-gradient(310deg, #ea580c, #facc15);
        color: white;
        border: none;
    }

    .bg-gradient-primary:hover {
        background: linear-gradient(310deg, #c2410c, #eab308);
        transform: translateY(-1px);
    }

    .badge.bg-primary {
        background: linear-gradient(310deg, #ea580c, #facc15) !important;
    }
</style>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script>
function enrollStudent(studentId) {
    document.getElementById('enrollStudentId').value = studentId;
    const enrollmentModal = new bootstrap.Modal(document.getElementById('enrollmentModal'));
    enrollmentModal.show();
}

document.getElementById('enrollmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Disable submit button to prevent double submission
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error enrolling student'
        });
    })
    .finally(() => {
        submitButton.disabled = false;
    });
});

function manageSubjects(studentId) {
    document.getElementById('modalStudentId').value = studentId;
    
    // Fetch current subjects for this student
    fetch(`/api/student/${studentId}/subjects`)
        .then(response => response.json())
        .then(enrolledSubjects => {
            // Reset all checkboxes
            document.querySelectorAll('input[name="subjects[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Check the boxes for enrolled subjects
            enrolledSubjects.forEach(subjectId => {
                const checkbox = document.getElementById(`subject${subjectId}`);
                if (checkbox) checkbox.checked = true;
            });
            
            // Show the modal
            const manageModal = new bootstrap.Modal(document.getElementById('manageSubjectsModal'));
            manageModal.show();
        });
}

document.getElementById('subjectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Disable submit button to prevent double submission
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error managing subjects'
        });
    })
    .finally(() => {
        submitButton.disabled = false;
    });
});

function confirmUnenroll(studentId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will unenroll the student from all subjects and delete their grades!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, unenroll!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/enrollment/unenroll/${studentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Unenrolled!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error unenrolling student'
                });
            });
        }
    });
}

$(document).ready(function() {
    // Initialize Available Students DataTable
    $('#availableStudentsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'asc']],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search students...",
        }
    });

    // Initialize Enrolled Students DataTable
    $('#enrolledStudentsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'asc']],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Enrolled students...",
        }
    });
});
</script>

<style>
/* DataTables Custom Styling */
.dataTables_wrapper {
    padding: 20px;
}

.dataTables_length {
    margin-bottom: 15px;
}

.dataTables_length select {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 6px 30px 6px 10px;
    margin: 0 5px;
    background-color: white;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M8 11l-4-4h8l-4 4z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}

.dataTables_filter {
    margin-bottom: 15px;
}

.dataTables_filter input {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 6px 12px;
    margin-left: 8px;
    width: 300px;
}

.dataTables_info {
    padding-top: 10px;
}

.dataTables_paginate {
    padding-top: 10px;
}

.paginate_button {
    padding: 5px 12px;
    margin: 0 2px;
    border-radius: 4px;
    border: 1px solid #ddd;
    background: white;
    cursor: pointer;
}

.paginate_button.current {
    background: linear-gradient(310deg, #ea580c, #facc15);
    color: white;
    border: none;
}

.paginate_button:hover:not(.current) {
    background: #f5f5f5;
}

.paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Table Styling */
.table thead th {
    font-weight: 600;
    padding: 12px 16px;
    border-bottom: 2px solid #ddd;
}

.table tbody td {
    padding: 12px 16px;
    vertical-align: middle;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .dataTables_length,
    .dataTables_filter {
        text-align: left;
        width: 100%;
    }
    
    .dataTables_filter input {
        width: 100%;
        margin-left: 0;
        margin-top: 5px;
    }
}
</style>
@endpush