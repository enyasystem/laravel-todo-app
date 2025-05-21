{{-- vscode-css-disable-next-line --}}
@extends('layouts.app')

@section('content')
<style>
    /* Only custom classes, no Tailwind utility classes here */
    .priority-indicator {
        position: absolute;
        top: 0;
        right: 0;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 40px 40px 0;
        border-top-color: transparent;
        border-right-color: transparent;
        border-bottom-color: transparent;
        border-left-color: transparent;
        z-index: 10;
    }
    .priority-low {
        border-right-color: #22c55e;
    }
    .priority-medium {
        border-right-color: #facc15;
    }
    .priority-high {
        border-right-color: #ef4444;
    }
</style>
<div x-data="todoApp()" class="bg-white overflow-hidden shadow-xl rounded-lg">
    <div class="p-6 bg-white">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                My Tasks
            </h2>
            <div class="flex space-x-2">
                <button id="quick-add-toggle" @click="toggleQuickAdd()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Quick Add
                </button>
                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:border-gray-800 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    Manage Categories
                </a>
                <a href="{{ route('todos.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 focus:outline-none focus:border-primary-800 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    New Task
                </a>
            </div>
        </div>
        
        <!-- Quick Add Form -->
        <div id="quick-add-form" class="quick-add-form mb-4">
            <form @submit.prevent="quickAddTask()" class="flex items-center gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                <input 
                    type="text" 
                    x-model="quickAddTitle" 
                    placeholder="Type a task and press Enter..." 
                    class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                    @keydown.escape="toggleQuickAdd()"
                >
                <button 
                    type="submit" 
                    class="inline-flex items-center px-3 py-2 bg-primary-600 text-white text-sm font-medium rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >
                    Add
                </button>
            </form>
        </div>

        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <form action="{{ route('todos.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Tasks</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            id="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search by title or description" 
                            class="pl-10 shadow-sm border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-300"
                        >
                    </div>
                </div>
                <div class="flex-1">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Filter by Category</label>
                    <select name="category" id="category" class="shadow-sm border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-300">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                    <select name="status" id="status" class="shadow-sm border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-300">
                        <option value="">All Tasks</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Filter by Priority</label>
                    <select name="priority" id="priority" class="shadow-sm border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-300">
                        <option value="">All Priorities</option>
                        <option value="1" {{ request('priority') == '1' ? 'selected' : '' }}>Low</option>
                        <option value="2" {{ request('priority') == '2' ? 'selected' : '' }}>Medium</option>
                        <option value="3" {{ request('priority') == '3' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 focus:outline-none focus:border-primary-800 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Filter
                    </button>
                    @if(request('category') || request('status') || request('priority') || request('search'))
                        <a href="{{ route('todos.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if($todos->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-4 text-gray-500 text-lg">No tasks found. Start by creating your first task!</p>
                <div class="mt-6">
                    <a href="{{ route('todos.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 focus:outline-none focus:border-primary-800 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 000-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Create First Task
                    </a>
                </div>
            </div>
        @else
            <!-- Task Progress Bar -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Task Progress</span>
                    <span class="text-sm font-medium text-gray-700">
                        {{ $todos->where('completed', true)->count() }} / {{ $todos->count() }} completed
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-primary-600 h-2.5 rounded-full" style="width: {{ $progressPercentage ?? 0 }}%"></div>
                </div>
            </div>
            
            <!-- Task List -->
            <div id="task-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($todos as $todo)
                    <div 
                        id="task-{{ $todo->id }}" 
                        class="task-card bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden relative"
                        data-id="{{ $todo->id }}"
                        data-position="{{ $todo->position }}"
                    >
                        <!-- Priority Corner -->
                        <div class="priority-indicator {{ $todo->priority == 1 ? 'priority-low' : ($todo->priority == 2 ? 'priority-medium' : 'priority-high') }}"></div>
                        
                        <div class="p-5">
                            <!-- Drag Handle -->
                            <div class="absolute top-2 left-2 sortable-handle text-gray-400 hover:text-gray-600 cursor-grab">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </div>
                            
                            <div class="flex justify-between items-start mb-3 pl-6">
                                <h3 class="text-lg font-semibold text-gray-900 {{ $todo->completed ? 'line-through text-gray-500' : '' }}">
                                    {{ $todo->title }}
                                </h3>
                                <div class="flex items-center space-x-1">
                                    @if($todo->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $todo->category->color }}; color: {{ $todo->category->color }};">
                                            {{ $todo->category->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <p class="text-gray-600 mb-4 {{ $todo->completed ? 'line-through text-gray-400' : '' }}">
                                {{ Str::limit($todo->description, 100) }}
                            </p>
                            
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $todo->priority_color }}">
                                        {{ $todo->priority_label }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $todo->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <button 
                                        @click="toggleComplete({{ $todo->id }})" 
                                        class="text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-300"
                                        title="{{ $todo->completed ? 'Mark as incomplete' : 'Mark as complete' }}"
                                    >
                                        @if($todo->completed)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </button>
                                    <a href="{{ route('todos.edit', $todo) }}" class="text-primary-600 hover:text-primary-800 transition-colors duration-300" title="Edit task">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors duration-300" onclick="return confirm('Are you sure you want to delete this task?')" title="Delete task">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Keyboard Shortcuts Help -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Keyboard Shortcuts...</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="flex items-center">
                        <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg">Alt</kbd>
                        <span class="mx-1">+</span>
                        <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg">N</kbd>
                        <span class="ml-2 text-sm text-gray-600">Quick add a new task</span>
                    </div>
                    <div class="flex items-center">
                        <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg">Esc</kbd>
                        <span class="ml-2 text-sm text-gray-600">Close quick add form</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function todoApp() {
        return {
            quickAddTitle: '',
            init() {
                this.initSortable();
            },
            initSortable() {
                const taskList = document.getElementById('task-list');
                if (!taskList) return;
                const self = this;
                const sortable = new Sortable(taskList, {
                    animation: 150,
                    handle: '.sortable-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: function(evt) {
                        self.updatePositions();
                    }
                });
            },
            updatePositions() {
                const tasks = document.querySelectorAll('.task-card');
                const positions = [];
                tasks.forEach((task, index) => {
                    const id = task.dataset.id;
                    positions.push({
                        id: id,
                        position: index
                    });
                    task.dataset.position = index;
                });
                window.axios.post("{{ route('todos.update-positions') }}", {
                    positions: positions
                }).then(response => {
                    if (response.success) {
                        showToast('Task order updated');
                    }
                });
            },
            toggleComplete(id) {
                window.axios.patch(`/todos/${id}/toggle-complete`).then(response => {
                    if (response.success) {
                        const taskElement = document.getElementById(`task-${id}`);
                        const titleElement = taskElement.querySelector('h3');
                        const descriptionElement = taskElement.querySelector('p');
                        if (response.completed) {
                            titleElement.classList.add('line-through', 'text-gray-500');
                            descriptionElement.classList.add('line-through', 'text-gray-400');
                        } else {
                            titleElement.classList.remove('line-through', 'text-gray-500');
                            descriptionElement.classList.remove('line-through', 'text-gray-400');
                        }
                        taskElement.classList.add('task-complete-animation');
                        setTimeout(() => {
                            taskElement.classList.remove('task-complete-animation');
                        }, 500);
                        const completeButton = taskElement.querySelector('button[title^="Mark as"]');
                        if (completeButton) {
                            if (response.completed) {
                                completeButton.innerHTML = `
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                `;
                                completeButton.title = "Mark as incomplete";
                            } else {
                                completeButton.innerHTML = `
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                `;
                                completeButton.title = "Mark as complete";
                            }
                        }
                        this.updateProgressBar();
                        showToast(response.message);
                    }
                });
            },
            updateProgressBar() {
                const completedTasks = document.querySelectorAll('.task-card h3.line-through').length;
                const totalTasks = document.querySelectorAll('.task-card').length;
                const progressBar = document.querySelector('.bg-primary-600.h-2\\.5');
                const progressText = document.querySelector('.text-sm.font-medium.text-gray-700:last-child');
                if (progressBar && progressText) {
                    const percentage = (completedTasks / totalTasks) * 100;
                    progressBar.style.width = `${percentage}%`;
                    progressText.textContent = `${completedTasks} / ${totalTasks} completed`;
                }
            },
            toggleQuickAdd() {
                const quickAddForm = document.getElementById('quick-add-form');
                quickAddForm.classList.toggle('open');
                if (quickAddForm.classList.contains('open')) {
                    setTimeout(() => {
                        quickAddForm.querySelector('input').focus();
                    }, 100);
                }
            },
            quickAddTask() {
                if (!this.quickAddTitle.trim()) {
                    showToast('Please enter a task title', 'error');
                    return;
                }
                window.axios.post("{{ route('todos.quick-add') }}", {
                    title: this.quickAddTitle
                }).then(response => {
                    if (response.success) {
                        showToast(response.message);
                        this.quickAddTitle = '';
                        window.location.reload();
                    }
                });
            }
        };
    }
</script>
@endpush
