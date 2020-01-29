<?php

namespace App\Http\Controllers;

use App\Applicant;
use Illuminate\Http\Request;

class PhpstormHandoutController extends Controller
{
    public function register(Request $request)
    {
        Applicant::query()->create(['name' => $request->input('name')]);
    }
}
