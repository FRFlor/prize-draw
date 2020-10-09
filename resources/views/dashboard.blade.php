@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <form action="{{route('import.csv')}}"
              class="flex inline-flex flex-col p-6 border-orange-700 border rounded mx-auto mb-6"
              method="post"
              enctype="multipart/form-data">
            @csrf
            <label class="text-orange-700 font-bold mb-4">Populate Raffle from CSV</label>
            <input type="file" name="applicants_csv" class="mb-1" required>
            <p class="text-sm mb-4">The headers of the csv must be <strong>'name'</strong> and <strong>'email'</strong> respectively</p>
            <button type="submit"
                    class="center w-64 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                Upload File
            </button>
            <p class="text-red-700">
                {{$errors->first()}}
            </p>

        </form>
    </div>


    <applicants-manager></applicants-manager>
@endsection
