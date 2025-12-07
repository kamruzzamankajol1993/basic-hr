<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Validation\Rule;
class DepartmentController extends Controller
{
    public function index()
    {
        
        return view('admin.departments.index');
    }

    
    public function data(Request $request)
    {
        $query = Department::query();
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Fetch paginated results
        $departments = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => $departments->items(),
            'total' => $departments->total(),
            'current_page' => $departments->currentPage(),
            'last_page' => $departments->lastPage(),
            'per_page' => $departments->perPage(),
        ]);
    }

    
    public function store(Request $request)
    {
      
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
        ]);

        Department::create($request->only('name'));

        return redirect()->route('departments.index')
                         ->with('success', 'Department created successfully!');
    }

   
    public function update(Request $request, Department $department)
    {
        
        $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('departments')->ignore($department->id)
            ],
        ]);

        $department->update($request->only('name'));

        return redirect()->route('departments.index')
                         ->with('success', 'Department updated successfully!');
    }

    
    public function destroy(Department $department)
    {
        
        $department->delete();

        return redirect()->route('departments.index')
                         ->with('success', 'Department deleted successfully!');
    }
}
