<?php

namespace App\Http\Controllers;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::query()
            ->with('scholarship:id,name,provider_name')
            ->where('applicant_id', auth()->id())
            ->latest('submitted_at')
            ->latest('created_at')
            ->get();

        $statusMap = [
            'under_review' => ['filter' => 'under-review', 'class' => 'under-review', 'label' => 'Under Review'],
            'approved' => ['filter' => 'approved', 'class' => 'approved', 'label' => 'Approved'],
            'rejected' => ['filter' => 'rejected', 'class' => 'rejected', 'label' => 'Rejected'],
            'revision' => ['filter' => 'action-needed', 'class' => 'pending', 'label' => 'Action Needed'],
            'pending' => ['filter' => 'submitted', 'class' => 'submitted', 'label' => 'Submitted'],
            'submitted' => ['filter' => 'submitted', 'class' => 'submitted', 'label' => 'Submitted'],
        ];

        $remarksByStage = [
            'submitted' => 'Queued for Screening',
            'doc_review' => 'Document Review',
            'scoring' => 'Blind Evaluation',
            'decided' => 'Decision Released',
        ];

        $stats = [
            'totalApplied' => $applications->count(),
            'underReview' => $applications->where('status', 'under_review')->count(),
            'approved' => $applications->where('status', 'approved')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
            'shortlisted' => $applications
                ->where('status', 'under_review')
                ->where('stage', 'scoring')
                ->count(),
            'actionNeeded' => $applications->where('status', 'revision')->count(),
        ];

        return view('applicant.applications.index', [
            'applications' => $applications,
            'stats' => $stats,
            'statusMap' => $statusMap,
            'remarksByStage' => $remarksByStage,
        ]);
    }


    public function create()
    {
        return view('applicant.applications.create');
    }


    public function store(Request $request)
    {
        // Logic to save scholarship application
    }


    public function show($id)
    {
        return view('applicant.applications.show', compact('id'));
    }


    public function track($id)
    {
        return view('applicant.applications.track', compact('id'));
    }
}
