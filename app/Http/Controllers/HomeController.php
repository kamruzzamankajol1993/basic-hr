<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Skill;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard and fetch dynamic data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // 1. Fetch dynamic data counts
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $totalSkills = Skill::count();

        // 2. Pass data to the view
        return view('admin.dashboard.index', compact(
            'totalEmployees', 
            'totalDepartments', 
            'totalSkills'
        ));
    }
}