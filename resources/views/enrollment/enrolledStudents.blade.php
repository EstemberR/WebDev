@extends('layouts.dashboardTemp')
@section('title', 'Enrolled Students')
@section('Pages', 'Enrollment / Enrolled Students')
@section('content')

<!-- Add DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">

<div class="panel-header panel-header-sm"></div>
<div class="content">
    <div class="container-fluid py-4">
        <div class="row">
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
                                                <span class="badge bg-primary">
                                                    {{ $subject->subject_code }} - {{ $subject->name }}
                                                </span>
                                            @endforeach
                                            
                                            @foreach($student->grades->whereNull('subject_id') as $grade)
                                                <span class="badge bg-secondary">
                                                    {{ $grade->subject_code }} - {{ $grade->subject_name }} (Deleted)
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <button class="btn bg-gradient-warning btn-sm me-2" 
                                                    onclick="manageSubjects({{ $student->id }}, '{{ $student->name }}')">
                                                Manage Subjects
                                            </button>
                                            <button class="btn btn-danger btn-sm" 
                                                    onclick="confirmUnenroll({{ $student->id }})">
                                                Unenroll
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

<style>
/* DataTables Custom Styling */
.dataTables_wrapper {
    padding: 20px;
}

.dataTables_length {
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

.dt-buttons {
    margin-bottom: 15px;
}

.dt-button {
    background: linear-gradient(310deg, #ea580c, #facc15) !important;
    color: white !important;
    border: none !important;
    border-radius: 4px !important;
    padding: 6px 12px !important;
    margin-right: 5px !important;
}

.dt-button:hover {
    background: linear-gradient(310deg, #c2410c, #eab308) !important;
    transform: translateY(-1px);
}

.paginate_button.current {
    background: linear-gradient(310deg, #ea580c, #facc15) !important;
    border: none !important;
    color: white !important;
}

.table thead th {
    font-weight: 600;
    padding: 12px 16px;
    border-bottom: 2px solid #ddd;
}

.nav-item .collapse {
    transition: all 0.3s ease;
}

.nav-item .nav-link .fa-chevron-down {
    transition: transform 0.3s ease;
}

.nav-item .nav-link[aria-expanded="true"] .fa-chevron-down {
    transform: rotate(180deg);
}
</style>

<!-- Manage Subjects Modal -->
<div class="modal fade" id="manageSubjectsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Subjects for <span id="studentName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="manageSubjectsForm" action="{{ route('enrollment.updateSubjects') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" id="manageStudentId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Subjects</label>
                        @foreach($subjects as $subject)
                        <div class="form-check">
                            <input class="form-check-input subject-checkbox" type="checkbox" 
                                   name="subjects[]" value="{{ $subject->id }}" 
                                   id="subject{{ $subject->id }}">
                            <label class="form-check-label" for="subject{{ $subject->id }}">
                                {{ $subject->name }} ({{ $subject->subject_code }})
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-gradient-warning">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with export buttons
    $('#availableStudentsTable, #enrolledStudentsTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'asc']],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search students...",
            paginate: {
                previous: "←",
                next: "→"
            }
        }
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
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
                        // Redirect to available students page after successful unenrollment
                        window.location.href = '/enrollment';
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

function manageSubjects(studentId, studentName) {
    document.getElementById('studentName').textContent = studentName;
    document.getElementById('manageStudentId').value = studentId;
    
    // Reset all checkboxes
    document.querySelectorAll('.subject-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Get current subjects and check the appropriate boxes
    fetch(`/enrollment/getSubjects/${studentId}`)
        .then(response => response.json())
        .then(data => {
            data.subjects.forEach(subjectId => {
                const checkbox = document.getElementById(`subject${subjectId}`);
                if (checkbox) checkbox.checked = true;
            });
            const modal = new bootstrap.Modal(document.getElementById('manageSubjectsModal'));
            modal.show();
        });
}

// Add this within your document.ready function
$('#manageSubjectsForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            $('#manageSubjectsModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred while updating subjects.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
        }
    });
});
</script>

@endpush
