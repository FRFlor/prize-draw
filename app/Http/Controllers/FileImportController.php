<?php

namespace App\Http\Controllers;

use App\Applicant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Console\Input\Input;

class FileImportController extends Controller
{
    public function importFromCsv(Request $request)
    {
        $path = $request->file('applicants_csv')->getRealPath();
        $csv = array_map('str_getcsv', file($path));

        $headers = $csv[0];
        $content = array_slice($csv, 1);

        if($headers[0] !== 'name' || $headers[1] !== 'email') {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, "Invalid csv! Please make sure the headers are 'name' and 'email' respectively");
        }

        $applicants = collect($content)->map(function($csvValues) use ($headers) {
            return [
                $headers[0] => $csvValues[0],
                $headers[1] => $csvValues[1]
            ];
        })->toArray();


        Applicant::query()->truncate();

        return response()->json(Applicant::query()->insert($applicants), Response::HTTP_CREATED);

    }
}
