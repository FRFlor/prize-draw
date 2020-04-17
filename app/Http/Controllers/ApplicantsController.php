<?php

namespace App\Http\Controllers;

use App\Applicant;
use App\Http\Requests\StoreApplicantRequest;
use App\Http\Requests\UpdateApplicantRequest;
use Illuminate\Http\Response;

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

    public function store(StoreApplicantRequest $request)
    {
        $newApplicant = Applicant::query()->create($request->validated());

        return response($newApplicant, Response::HTTP_CREATED);
    }

    public function destroy(Applicant $applicant)
    {
        $applicant->delete();
    }
}
