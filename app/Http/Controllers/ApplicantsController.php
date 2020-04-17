<?php

namespace App\Http\Controllers;

use App\Applicant;
use App\Http\Requests\UpdateApplicantRequest;
use Illuminate\Http\Request;

class ApplicantsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index()
    {
        return response()->json(Applicant::query()->select('id', 'name')->get());
    }

    public function update(UpdateApplicantRequest $request, Applicant $applicant)
    {
        $applicant->update($request->validated());
    }
}
