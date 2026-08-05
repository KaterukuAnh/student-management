<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('student.classroom');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'student' => $user->student ? [
                'id' => $user->student->id,
                'name' => $user->student->name,
                'classroom' => $user->student->classroom?->name,
            ] : null,
        ]);
    }
}
