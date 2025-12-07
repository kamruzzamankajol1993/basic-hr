@extends('admin.master.master')

@section('title', 'Employee Details: ' . $employee->first_name)

@section('body')
<div class="main-content container-fluid pt-5 mt-5">
    <h1 class="h3 mb-4 text-gray-800 fw-bold" style="color: var(--bd-green);">Employee Details</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">{{ $employee->first_name }} {{ $employee->last_name }}</h6>
            <div>
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-warning me-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            
            <table class="table table-bordered detail-table">
                <tbody>
                    <tr>
                        <th style="width: 25%;">Full Name</th>
                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $employee->email }}</td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td>
                            <span class="badge bg-primary">{{ $employee->department->name ?? 'N/A' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Skills</th>
                        <td>
                            @forelse ($employee->skills as $skill)
                                <span class="badge bg-success me-1">{{ $skill->name }}</span>
                            @empty
                                <span class="text-muted">No skills assigned.</span>
                            @endforelse
                        </td>
                    </tr>
                    <tr>
                        <th>Hired Date</th>
                        <td>{{ $employee->created_at->format('M d, Y') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection