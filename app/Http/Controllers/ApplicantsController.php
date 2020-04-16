<?php

namespace App\Http\Controllers;

use App\Applicant;
use Illuminate\Http\Request;

class ApplicantsController extends Controller
{
    public function index()
    {
        return response()->json(Applicant::query()->select('id', 'name')->get());
    }
}
