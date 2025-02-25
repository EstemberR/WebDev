@extends('layouts.dashboardTemp')
@section('title', 'Students')
@section('Pages', 'Students')
@section('content')
<!-- Add these lines for DataTables -->
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<!-- Add DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0">Student Lists</h6>
                        <button type="button" class="text-white bg-gradient-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table id="studentsTable" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->student_id }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->status }}</td>
                                        <td>
                                            <a class="btn bg-gradient-warning btn-sm" 
                                               onclick="editStudent('{{ $student->id }}', '{{ $student->student_id }}', '{{ $student->name }}', '{{ $student->email }}', '{{ $student->status }}')">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form id="delete-form-{{ $student->id }}" action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn bg-gradient-danger btn-sm" 
                                                        onclick="confirmDelete('delete-form-{{ $student->id }}')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
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

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addStudentForm" action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                               id="student_id" name="student_id" value="{{ old('student_id') }}" required>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-gradient-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStudentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="edit_student_id" name="student_id" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-gradient-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any())
<script>
    let errorMessages = [];
    @foreach ($errors->all() as $error)
        errorMessages.push("{{ $error }}");
    @endforeach
    alert(errorMessages.join("\n"));
</script>
@endif

@if(session('success'))
<script>
    alert("{{ session('success') }}");
</script>
@endif

<!-- Delete confirmation -->
<script>
function confirmDelete(formId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ea580c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(formId);
            
            fetch(form.action, {
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
                        title: 'Deleted!',
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
                    text: 'Error deleting student'
                });
            });
        }
    });
}
</script>

@push('scripts')
<script>
// Add Student Form Handler
document.getElementById('addStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
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
                text: data.message || 'Error adding student'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error adding student'
        });
    })
    .finally(() => {
        submitButton.disabled = false;
    });
});

function editStudent(id, studentId, name, email, status) {
    // Set form action
    const form = document.getElementById('editStudentForm');
    form.action = `/students/${id}`;
    
    // Fill form fields
    document.getElementById('edit_student_id').value = studentId;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_status').value = status || 'active';
    
    // Show modal using Bootstrap Modal instance
    const editModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
    editModal.show();
}

// Your existing delete confirmation function
function confirmDelete(formId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ea580c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(formId);
            
            fetch(form.action, {
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
                        title: 'Deleted!',
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
                    text: 'Error deleting student'
                });
            });
        }
    });
}

document.getElementById('editStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
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
                text: data.message || 'Error updating student'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error updating student'
        });
    })
    .finally(() => {
        submitButton.disabled = false;
    });
});

// Initialize DataTables
$(document).ready(function() {
    const studentsTable = $('#studentsTable').DataTable({
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>' +
             '<"row"<"col-12"tr>>' +
             '<"row"<"col-md-5"i><"col-md-7"p>>',
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'asc']],
        responsive: true,
        pagingType: "simple_numbers",
        language: {
            search: "",
            searchPlaceholder: "Search...",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                previous: '<i class="fas fa-angle-left"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                last: '<i class="fas fa-angle-double-right"></i>'
            }
        }
    });

    // Custom search functionality
    $('#customSearch').on('keyup', function() {
        studentsTable.search(this.value).draw();
    });
});
</script>
@endpush

<style>
    /* Add Student/Subject Button Styling */
    .card-header .btn.bg-gradient-primary {
        background: var(--light);
        color: var(--dark);
        border: 1px solid rgba(0, 0, 0, 0.1);
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .card-header .btn.bg-gradient-primary:hover {
        background: linear-gradient(310deg, var(--primary-orange), var(--primary-yellow));
        color: var(--light);
        border: none;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(234, 88, 12, 0.25);
    }

    .card-header .btn.bg-gradient-primary i {
        margin-right: 0.5rem;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper {
        padding: 20px;
    }

    .dataTables_length {
        margin-bottom: 15px;
    }

    .dataTables_length label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        font-size: 0.875rem;
        white-space: nowrap;
    }

    .dataTables_length select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 6px 30px 6px 10px;
        margin: 0;
        background-color: white;
        height: 38px;
        font-size: 0.875rem;
        min-width: 80px;
    }

    /* Adjust the container for better alignment */
    .dataTables_wrapper .row:first-child {
        align-items: center;
        margin-bottom: 1rem;
    }

    .dataTables_wrapper .col-md-6:first-child {
        display: flex;
        align-items: center;
    }

    .dataTables_info {
        font-size: 0.875rem;
        padding-top: 0.5rem;
    }

    .dataTables_paginate {
        margin-top: 1rem;
        text-align: right;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 5px;
    }

    .paginate_button {
        padding: 8px 12px;
        margin: 0 2px;
        border-radius: 4px;
        cursor: pointer;
        background: transparent;
        border: none;
        color: #333;
    }

    .paginate_button.current {
        background: linear-gradient(310deg, #ea580c, #facc15);
        color: white;
    }

    .paginate_button:hover:not(.current) {
        background: white !important;
        color: #ea580c;
    }

    .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        color: #999;
    }

    /* Table header styling */
    .table thead th {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 1rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    /* Entries text alignment */
    .dataTables_length {
        display: flex;
        align-items: center;
    }

    .dataTables_length label {
        white-space: nowrap;
    }
</style>

@endsection