@extends('layouts.app')

@section('title', 'Find My Email')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white shadow-md rounded-lg px-8 py-6">
            <h2 class="text-2xl font-bold text-center mb-2">Find My Email</h2>
            <p class="text-sm text-gray-500 text-center mb-6">Enter your Kirkwood ID (e.g. K0844510) and phone number to look up your registered email.</p>

            @if (session('found_email'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600 mb-1">Your registered email is:</p>
                    <p class="text-lg font-bold text-green-700">{{ session('found_email') }}</p>
                    <a href="{{ route('login') }}" class="mt-3 inline-block text-sm text-blue-600 hover:text-blue-800">
                        Go to login
                    </a>
                </div>
            @endif

            <form method="POST" action="{{ route('email.lookup') }}">
                @csrf

                <div class="mb-4">
                    <label for="kirkwood_id" class="block text-gray-700 text-sm font-bold mb-2">Kirkwood ID</label>
                    <input type="text" name="kirkwood_id" id="kirkwood_id" value="{{ old('kirkwood_id') }}" required autofocus
                           placeholder="K0844510"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('kirkwood_id') border-red-500 @enderror">
                    @error('kirkwood_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required
                           placeholder="+1-555-0101"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone_number') border-red-500 @enderror">
                    @error('phone_number')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Find My Email
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">Back to login</a>
            </div>
        </div>
    </div>
@endsection
