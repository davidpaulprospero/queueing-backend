<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentNumberController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studentId = $id;
        $exists = Student::where('studentId', $studentId)->exists();

        if ($exists) {
            $student = Student::where('studentId', $studentId)->first();
            $name = $student->name;
        } else {
            $name = null;
        }

        return response()->json([
            'exists' => $exists,
            'name' => $name,
        ]);
    }
}