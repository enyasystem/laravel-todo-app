<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categoryId = $request->query('category');
        $status = $request->query('status');
        $priority = $request->query('priority');
        $search = $request->query('search');
    
        // Get categories for the filter dropdown
        $categories = Cache::remember('categories', 60, function () {
            return Category::all();
        });
    
        // Build the query with filters
        $query = Todo::query();
    
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
    
        if ($status === 'completed') {
            $query->where('completed', true);
        } elseif ($status === 'active') {
            $query->where('completed', false);
        }
        
        if ($priority) {
            $query->where('priority', $priority);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
    
        // Get the filtered todos and order by position
        $todos = $query->orderBy('position')->get();
    
        return view('todos.index', compact('todos', 'categories', 'categoryId', 'status', 'priority', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('todos.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'required|integer|min:1|max:3',
        ]);

        // Get the highest position value
        $maxPosition = Todo::max('position') ?? 0;

        Todo::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'priority' => $validated['priority'],
            'position' => $maxPosition + 1,
            'completed' => false,
        ]);

        // Clear the cache when a new todo is added
        Cache::forget('todos');

        if ($request->has('save_and_new')) {
            return redirect()->route('todos.create')
                ->with('success', 'Task created successfully. Add another task.');
        }

        return redirect()->route('todos.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        $categories = Category::all();
        return view('todos.edit', compact('todo', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'required|integer|min:1|max:3',
            'completed' => 'nullable|boolean',
        ]);

        $todo->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'priority' => $validated['priority'],
            'completed' => $request->has('completed'),
        ]);

        // Clear the cache when a todo is updated
        Cache::forget('todos');

        return redirect()->route('todos.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Toggle the completion status of the specified todo.
     */
    public function toggleComplete(Todo $todo)
    {
        $todo->update([
            'completed' => !$todo->completed,
        ]);

        // Clear the cache when a todo is updated
        Cache::forget('todos');

        if ($todo->completed) {
            $message = 'Task marked as completed!';
        } else {
            $message = 'Task marked as active!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'completed' => $todo->completed
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        // Clear the cache when a todo is deleted
        Cache::forget('todos');

        return redirect()->route('todos.index')
            ->with('success', 'Task deleted successfully.');
    }
    
    /**
     * Update the positions of todos.
     */
    public function updatePositions(Request $request)
    {
        $positions = $request->input('positions', []);
        
        foreach ($positions as $position) {
            $id = $position['id'];
            $newPosition = $position['position'];
            
            Todo::where('id', $id)->update(['position' => $newPosition]);
        }
        
        // Clear the cache when positions are updated
        Cache::forget('todos');
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Quick add a new todo.
     */
    public function quickAdd(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        
        // Get the highest position value
        $maxPosition = Todo::max('position') ?? 0;
        
        $todo = Todo::create([
            'title' => $validated['title'],
            'description' => '',
            'priority' => 1, // Default to Low priority
            'position' => $maxPosition + 1,
            'completed' => false,
        ]);
        
        // Clear the cache when a new todo is added
        Cache::forget('todos');
        
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully!',
            'todo' => $todo
        ]);
    }
}
