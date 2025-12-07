<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SkillController extends Controller
{
    public function index()
    {
        return view('admin.skills.index');
    }

   
    public function data(Request $request)
    {
        $query = Skill::query();
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $skills = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => $skills->items(),
            'total' => $skills->total(),
            'current_page' => $skills->currentPage(),
            'last_page' => $skills->lastPage(),
            'per_page' => $skills->perPage(),
        ]);
    }

    
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:skills,name'],
        ]);

        Skill::create($request->only('name'));

        return redirect()->route('skills.index')
                         ->with('success', 'Skill created successfully!');
    }

    
    public function update(Request $request, Skill $skill)
    {
       
        $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('skills')->ignore($skill->id)
            ],
        ]);

        $skill->update($request->only('name'));

        return redirect()->route('skills.index')
                         ->with('success', 'Skill updated successfully!');
    }

    
    public function destroy(Skill $skill)
    {
        
        $skill->delete();

        return redirect()->route('skills.index')
                         ->with('success', 'Skill deleted successfully!');
    }
}
