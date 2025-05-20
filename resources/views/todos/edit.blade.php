@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-xl rounded-lg">
    <div class="p-6 bg-white">
        <div class="flex items-center mb-6">
            <a href="{{ route('todos.index') }}" class="inline-flex items-center mr-4 text-primary-600 hover:text-primary-800 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                <span class="ml-1">Back to Tasks</span>
            </a>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Edit Task
            </h2>
        </div>

        <form action="{{ route('todos.update', $todo) }}" method="POST" class="max-w-2xl">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Task Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $todo->title) }}" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-300 @error('title') border-red-500 @enderror" required>
                @error('title')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                <select name="category_id" id="category_id" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-300">
                    <option value="">No Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $todo->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label for="priority" class="block text-gray-700 text-sm font-bold mb-2">Priority</label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="priority" value="1" class="form-radio text-blue-600" {{ old('priority', $todo->priority) == 1 ? 'checked' : '' }}>
                        <span class="ml-2 flex items-center">
                            <span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-1"></span>
                            Low
                        </span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="priority" value="2" class="form-radio text-yellow-600" {{ old('priority', $todo->priority) == 2 ? 'checked' : '' }}>
                        <span class="ml-2 flex items-center">
                            <span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mr-1"></span>
                            Medium
                        </span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="priority" value="3" class="form-radio text-red-600" {{ old('priority', $todo->priority) == 3 ? 'checked' : '' }}>
                        <span class="ml-2 flex items-center">
                            <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-1"></span>
                            High
                        </span>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea name="description" id="description" rows="4" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-300 @error('description') border-red-500 @enderror">{{ old('description', $todo->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="completed" class="form-checkbox h-5 w-5 text-primary-600 rounded focus:ring-primary-500 border-gray-300 transition duration-150" {{ $todo->completed ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Mark as completed</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button type="button" onclick="window.history.back()" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-200 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                        Cancel
                    </button>
                </div>
                <div class="flex items-center">
                    <button type="submit" class="inline-flex items-center px-5 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 focus:outline-none focus:border-primary-800 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Update Task
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Task History -->
    <div class="p-6 bg-gray-50 border-t border-gray-200">
        <h3 class="text-lg font-medium text-gray-700 mb-4">Task History</h3>
        <div class="space-y-3">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-700">
                        Created on <span class="font-medium">{{ $todo->created_at->format('M d, Y \a\t h:i A') }}</span>
                    </p>
                </div>
            </div>
            @if($todo->created_at != $todo->updated_at)
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-700">
                        Last updated on <span class="font-medium">{{ $todo->updated_at->format('M d, Y \a\t h:i A') }}</span>
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
