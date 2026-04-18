<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index() { return view('scholarships.index'); }
    public function show($id) { return view('scholarships.show'); }
    public function create() { return view('admin.scholarships.create'); }
    public function store(Request $request) { /* Logic later */ }
    public function edit($id) { return view('admin.scholarships.edit'); }
    public function update(Request $request, $id) { /* Logic later */ }
    public function destroy($id) { /* Logic later */ }
}
