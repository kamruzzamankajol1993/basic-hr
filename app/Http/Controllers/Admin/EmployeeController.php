<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.employees.index', compact('departments'));
    }

    
    public function data(Request $request)
    {
        $query = Employee::query()->with(['department', 'skills']);
        $search = $request->get('search');
        $departmentId = $request->get('department_id');
        $perPage = $request->get('per_page', 10);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $employees = $query->latest()->paginate($perPage);

        return response()->json($employees); 
    }

    
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();
        return view('admin.employees.create', compact('departments', 'skills'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:employees,email'],
            'department_id' => ['required', 'exists:departments,id'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['nullable', 'exists:skills,id'],
        ]);

        $employee = Employee::create($request->only('first_name', 'last_name', 'email', 'department_id'));
        
        // Sync Skills: The employee can have an optional array of skill IDs
        $employee->skills()->sync($request->input('skills', []));

        return redirect()->route('employees.index')
                         ->with('success', 'Employee created successfully!');
    }

    
    public function show(Employee $employee)
    {
        // Eager load relationships for the view
        $employee->load(['department', 'skills']);
        return view('admin.employees.show', compact('employee'));
    }

    
    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();
        // Get current skill IDs for pre-selecting in the form
        $currentSkillIds = $employee->skills->pluck('id')->toArray();
        
        return view('admin.employees.edit', compact('employee', 'departments', 'skills', 'currentSkillIds'));
    }

    
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('employees')->ignore($employee->id)],
            'department_id' => ['required', 'exists:departments,id'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['nullable', 'exists:skills,id'],
        ]);

        $employee->update($request->only('first_name', 'last_name', 'email', 'department_id'));
        
       
        $employee->skills()->sync($request->input('skills', []));

        return redirect()->route('employees.index')
                         ->with('success', 'Employee updated successfully!');
    }

    
    public function destroy(Employee $employee)
    {
        $employee->delete();
        
        return redirect()->route('employees.index')
                         ->with('success', 'Employee deleted successfully!');
    }
    
    
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $ignoreId = $request->input('id'); 
        
        $query = Employee::where('email', $email);
        
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        
        $isUnique = $query->doesntExist();
        
        return response()->json(['is_unique' => $isUnique]);
    }
}
