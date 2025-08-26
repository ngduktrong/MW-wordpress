<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $age = 22;
        $skills = ["PHP", "Laravel", "JavaScript", "MySQL"];

        // If/Else
        if ($age >= 18) {
            $status = "Người lớn ✅";
        } else {
            $status = "Trẻ em 👶";
        }

        // For
        $numbers = [];
        for ($i = 1; $i <= 5; $i++) {
            $numbers[] = "Số $i";
        }

        // Foreach
        $skillList = [];
        foreach ($skills as $skill) {
            $skillList[] = strtoupper($skill); // ví dụ: PHP -> PHP (in hoa)
        }

        return view('test', compact('age', 'status', 'numbers', 'skillList'));
    }
}
