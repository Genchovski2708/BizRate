@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-6 text-center">
                <h1 class="text-3xl font-bold text-gray-800">
                    BizRate
                </h1>
                <p class="text-gray-600 mt-2">
                    Discover and rate businesses
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-sm font-medium text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
                    <input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Password') }}</label>
                    <input id="password" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" type="password" name="password" required autocomplete="current-password">
                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button type="submit" class="ms-3 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>

            <!-- Getting Started Section -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    New to BizRate?
                    <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Create an account
                    </a>
                </p>
                <div class="mt-4 text-xs text-gray-500">
                    <p>Discover businesses, read reviews, and share your experiences.</p>
                    <p class="mt-2">Join our community and help others make informed choices!</p>
                </div>
            </div>
        </div>
    </div>
@endsection
