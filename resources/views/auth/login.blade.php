@extends('layouts.app')

@section('content')
    <form method="POST" class="mt-12 max-w-md mx-auto flex flex-col items-center" action="{{ route('login') }}">
        @csrf
        <input id="email"
               aria-label="email"
               placeholder="Email"
               name="email"
               value="{{ old('email') }}"
               class="text-orange-700 block py-2 px-4 border-orange-500 w-full border-2 rounded"
               required type="email"
               autocomplete="email" autofocus>
        @error('email')
        <strong class="text-red-500" role="alert">{{ $message }}</strong>
        @enderror

        <input id="password"
               aria-label="password"
               placeholder="Password"
               name="password"
               class="text-orange-700 block py-2 px-4 mt-6 border-orange-500 w-full border-2 rounded"
               required type="password"
               autocomplete="current-password">
        @error('password')
        <strong class="text-red-500" role="alert">{{ $message }}</strong>
        @enderror

        <button type="submit" class="mt-12 hover:bg-orange-600 hover:text-gray-100 h-8 w-32 rounded border border-orange-600 text-orange-600 bg-gray-100">
            {{ __('Login') }}
        </button>
    </form>
@endsection
