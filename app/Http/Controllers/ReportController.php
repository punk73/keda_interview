<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //
    public function store(Request $request){

        if(!Gate::allows('make-reports')) {
            return response()->json([
                'success' => false,
                'message' => 'Access Denied!'
            ], 403);
        }

        $valid = $request->validate([
            'content' => 'required|max:199',
        ]);

        $reports = new Report($valid);
        $reports->reported_by = Auth::user()->id;
        $reports->reported_user_id = $request->reported_user_id;
        $reports->save();

        return [
            'success' => true,
            'message' => 'data saved!',
            'data' => $reports
        ];

    }
}
