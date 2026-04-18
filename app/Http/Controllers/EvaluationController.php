<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index() { return view('evaluator.index'); }
    public function show($id) { return view('evaluator.show'); }
    public function store(Request $request, $id) { /* Save score/grades */ }
    public function reject(Request $request, $id) { /* Finalize rejection */ }
    public function completed() { return view('evaluator.completed'); }
}
