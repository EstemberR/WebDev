@extends('layouts.dashboardTemp')
@section('title', 'Grades')
@section('Pages', 'Grades')
@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Student Grades</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Midterm</th>
                                        <th>Finals</th>
                                        <th>Average</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        @foreach($student->subjects as $subject)
                                            @php
                                                $grade = $student->grades->where('subject_id', $subject->id)->first();
                                            @endphp
                                            <tr>
                                                <td>{{ $student->student_id }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $subject->name }}</td>
                                                <td>{{ $grade ? $grade->midterm : '-' }}</td>
                                                <td>{{ $grade ? $grade->finals : '-' }}</td>
                                                <td>{{ $grade ? number_format($grade->average, 2) : '-' }}</td>
                                                <td>
                                                    @if($grade)
                                                        <span class="badge bg-{{ $grade->remarks === 'Passed' ? 'success' : 'danger' }}">
                                                            {{ $grade->remarks }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn bg-gradient-warning btn-sm" 
                                                            onclick="manageGrades({{ $student->id }}, {{ $subject->id }}, '{{ $grade ? $grade->midterm : '' }}', '{{ $grade ? $grade->finals : '' }}')">
                                                        {{ $grade ? 'Edit' : 'Add' }} Grades
                                                    </button>
                                                    @if($grade)
                                                        <button class="btn bg-gradient-danger btn-sm" 
                                                                onclick="confirmDeleteGrade({{ $student->id }}, {{ $subject->id }})">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
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

<!-- Grade Modal -->
<div class="modal fade" id="gradeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Grades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="gradeForm" action="{{ route('grades.store') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" id="grade_student_id">
                <input type="hidden" name="subject_id" id="grade_subject_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="midterm" class="form-label">Midterm Grade</label>
                        <input type="number" class="form-control" id="midterm" name="midterm" 
                               required min="1" max="5" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="finals" class="form-label">Finals Grade</label>
                        <input type="number" class="form-control" id="finals" name="finals" 
                               required min="1" max="5" step="0.01">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-gradient-warning">Save Grades</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function manageGrades(studentId, subjectId, midterm, finals) {
    document.getElementById('grade_student_id').value = studentId;
    document.getElementById('grade_subject_id').value = subjectId;
    document.getElementById('midterm').value = midterm;
    document.getElementById('finals').value = finals;
    
    const gradeModal = new bootstrap.Modal(document.getElementById('gradeModal'));
    gradeModal.show();
}

document.getElementById('gradeForm').addEventListener('submit', function(e) {
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
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error saving grades'
        });
    })
    .finally(() => {
        submitButton.disabled = false;
    });
});

function confirmDeleteGrade(studentId, subjectId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the student's grade for this subject.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ea580c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteGrade(studentId, subjectId);
        }
    });
}

function deleteGrade(studentId, subjectId) {
    fetch(`/grades/delete/${studentId}/${subjectId}`, {
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
            text: 'Error deleting grade'
        });
    });
}
</script>
@endpush

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
</style>

@endsection