<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::latest()->paginate(15);
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:courses,name',
            'code' => 'required|string|max:50|unique:courses,code',
            'description' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
            'fee' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courses')->ignore($course->id)
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courses')->ignore($course->id)
            ],
            'description' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
            'fee' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully');
    }
}
