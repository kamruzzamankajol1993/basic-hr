@extends('admin.master.master')

@section('title')
Dashboard | HR System
@endsection


@section('css')

@endsection

@section('body')
<div class="main-content container-fluid pt-5 mt-5">
            
            <h1 class="h3 mb-4 text-gray-800 fw-bold" style="color: var(--bd-green);">Dashboard Summary</h1>

            <div class="row">
                
                {{-- Total Employee Card --}}
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card dashboard-card shadow border-start border-4 border-employee-color">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-uppercase mb-1 fw-bold small text-employee-color">Total Employee</div>
                                    <div class="h2 mb-0 fw-bold">{{ $totalEmployees }}</div>
                                </div>
                                <i class="fas fa-users fa-3x text-employee-icon-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Department Card --}}
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card dashboard-card shadow border-start border-4 border-department-color">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-uppercase mb-1 fw-bold small text-department-color">Total Department</div>
                                    <div class="h2 mb-0 fw-bold">{{ $totalDepartments }}</div>
                                </div>
                                <i class="fas fa-building fa-3x text-department-icon-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Skill Card --}}
                <div class="col-xl-4 col-md-12 mb-4">
                    <div class="card dashboard-card shadow border-start border-4 border-skill-color">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-uppercase mb-1 fw-bold small text-skill-color">Total Skill</div>
                                    <div class="h2 mb-0 fw-bold">{{ $totalSkills }}</div>
                                </div>
                                <i class="fas fa-wrench fa-3x text-skill-icon-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            

        </div>
@endsection

@section('script')

@endsection