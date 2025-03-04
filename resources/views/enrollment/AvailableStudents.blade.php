@extends('layouts.dashboardTemp')
@section('title', 'Available Students')
@section('Pages', 'Enrollment / Available Students')
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
                                            <button class="btn bg-gradient-warning btn-sm" 
                                                    onclick="enrollStudent({{ $student->id }})">
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
</style>

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

function enrollStudent(studentId) {
    document.getElementById('enrollStudentId').value = studentId;
    const enrollmentModal = new bootstrap.Modal(document.getElementById('enrollmentModal'));
    enrollmentModal.show();
}

// Add your existing enrollment form submission handler here
</script>
@endpush
