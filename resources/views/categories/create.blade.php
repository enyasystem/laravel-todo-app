@extends('layouts.app')

@section('content')
@if (!isset($errors))
    @php $errors = new \Illuminate\Support\ViewErrorBag; @endphp
@endif
<div class="bg-white overflow-hidden shadow-xl rounded-lg">
    <div class="p-6 bg-white">
        <div class="flex items-center mb-6">
            <a href="{{ route('categories.index') }}" class="inline-flex items-center mr-4 text-primary-600 hover:text-primary-800 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                <span class="ml-1">Back to Categories</span>
            </a>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Create New Category
            </h2>
        </div>

        <form action="{{ route('categories.store') }}" method="POST" class="max-w-2xl">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Category Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-300 @error('name') border-red-500 @enderror" placeholder="Enter category name" required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="color" class="block text-gray-700 text-sm font-bold mb-2">Color</label>
                <div class="flex items-center">
                    <input type="color" name="color" id="color" value="{{ old('color', '#3b82f6') }}" class="h-10 w-10 border-0 rounded-md mr-2 cursor-pointer">
                    <input type="text" name="color_text" id="color_text" value="{{ old('color', '#3b82f6') }}" class="shadow-sm appearance-none border border-gray-300 rounded-md py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-300" readonly>
                </div>
                @error('color')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="inline-flex items-center px-5 py-3 bg-primary-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 focus:outline-none focus:border-primary-800 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorInput = document.getElementById('color');
        const colorText = document.getElementById('color_text');
        
        colorInput.addEventListener('input', function() {
            colorText.value = colorInput.value;
        });
    });
</script>
@endsection
