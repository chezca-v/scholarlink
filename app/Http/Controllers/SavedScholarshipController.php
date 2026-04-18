<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SavedScholarshipController extends Controller
{
    public function store(Request $request) { /* Save to saved_scholarships table */ }
    public function destroy($id) { /* Remove from saved_scholarships table */ }
}
