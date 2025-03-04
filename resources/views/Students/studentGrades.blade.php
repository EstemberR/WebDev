@extends('layouts.dashboardTemp')

@section('title', 'My Grades')
@section('Pages', 'My Grades')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>My Grades</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Midterm</th>
                                    <th>Finals</th>
                                    <th>Average</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allGrades as $grade)
                                    <tr>
                                        <td>{{ $grade->subject_code }}</td>
                                        <td>{{ $grade->subject_name }}</td>
                                        <td>{{ $grade->midterm ?? 'N/A' }}</td>
                                        <td>{{ $grade->finals ?? 'N/A' }}</td>
                                        <td>{{ $grade->average ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-{{ $grade->remarks === 'Passed' ? 'success' : 'danger' }}">
                                                {{ $grade->remarks }}
                                            </span>
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
@endsection