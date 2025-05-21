@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-10 rounded shadow text-center">
        <h1 class="text-4xl font-bold mb-4 text-primary-600">Welcome to the Laravel Todo App</h1>
        <p class="mb-6 text-gray-700">Manage your tasks and categories efficiently.</p>
        <a href="{{ route('todos.index') }}" class="inline-block px-6 py-3 bg-primary-600 text-white rounded hover:bg-primary-700 font-semibold transition">Go to My Tasks</a>
    </div>
</div>
@endsection
