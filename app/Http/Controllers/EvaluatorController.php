<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluatorController extends Controller
{
    public function dashboard() { return view('evaluator.dashboard'); }
    public function queue() { return view('evaluator.queue'); }
    public function review($id) { return view('evaluator.review'); }
}
