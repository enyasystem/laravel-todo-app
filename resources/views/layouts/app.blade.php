<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Todo App</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        
        .task-card {
            transition: all 0.3s ease;
        }
        
        .task-card:hover {
            transform: translateY(-2px);
        }
        
        .sortable-ghost {
            opacity: 0.5;
            background-color: #f3f4f6;
            border: 2px dashed #d1d5db;
        }
        
        .sortable-drag {
            cursor: grabbing;
        }
        
        .sortable-handle {
            cursor: grab;
        }
        
        .task-complete-animation {
            animation: taskComplete 0.5s ease-in-out;
        }
        
        @keyframes taskComplete {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
            background-color: #10b981;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 50;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .toast.error {
            background-color: #ef4444;
        }
        
        .priority-indicator {
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 0;
            border-style: solid;
        }
        
        .priority-low {
            border-width: 0 30px 30px 0;
            border-color: transparent #93c5fd transparent transparent;
        }
        
        .priority-medium {
            border-width: 0 30px 30px 0;
            border-color: transparent #fcd34d transparent transparent;
        }
        
        .priority-high {
            border-width: 0 30px 30px 0;
            border-color: transparent #f87171 transparent transparent;
        }
        
        .quick-add-form {
            transition: all 0.3s ease;
            max-height: 0;
            overflow: hidden;
        }
        
        .quick-add-form.open {
            max-height: 60px;
        }
    </style>
    
    <!-- Sortable.js for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Hero Header -->
        <header class="bg-gradient-to-r from-primary-600 to-primary-800 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <a href="{{ route('todos.index') }}" class="ml-3 text-2xl font-bold text-white">
                            Todo Master
                        </a>
                    </div>
                    <nav class="hidden md:flex space-x-4">
                        <a href="{{ route('todos.index') }}" class="text-white hover:text-primary-100 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('todos.*') ? 'bg-primary-700' : '' }}">
                            Tasks
                        </a>
                        <a href="{{ route('categories.index') }}" class="text-white hover:text-primary-100 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('categories.*') ? 'bg-primary-700' : '' }}">
                            Categories
                        </a>
                    </nav>
                </div>
                <div class="mt-4 max-w-2xl">
                    <h1 class="text-3xl font-extrabold text-white sm:text-4xl">
                        Organize your tasks
                    </h1>
                    <p class="mt-3 text-lg text-primary-100">
                        A simple and elegant way to manage your daily tasks and boost your productivity.
                    </p>
                </div>
            </div>
        </header>

        <main class="flex-grow py-10 bg-gray-50">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md" role="alert">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-center text-gray-500 text-sm">
                    <p>&copy; {{ date('Y') }} Todo Master. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast" x-data="{ show: false, message: '', type: 'success' }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         @toast.window="
            show = true; 
            message = $event.detail.message; 
            type = $event.detail.type || 'success';
            setTimeout(() => { show = false }, 3000)
         "
         :class="{ 'error': type === 'error' }"
         x-cloak>
        <div class="flex items-center">
            <template x-if="type === 'success'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </template>
            <template x-if="type === 'error'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </template>
            <span x-text="message"></span>
        </div>
    </div>

    <script>
        // Helper function to show toast notifications
        function showToast(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: message,
                    type: type
                }
            }));
        }
        
        // Setup CSRF token for AJAX requests
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            window.axios = {
                post: function(url, data = {}) {
                    return fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    }).then(response => response.json());
                },
                patch: function(url, data = {}) {
                    return fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    }).then(response => response.json());
                }
            };
            
            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Alt+N to open quick add form
                if (e.altKey && e.key === 'n') {
                    const quickAddToggle = document.getElementById('quick-add-toggle');
                    if (quickAddToggle) {
                        quickAddToggle.click();
                        e.preventDefault();
                    }
                }
                
                // Escape to close quick add form
                if (e.key === 'Escape') {
                    const quickAddForm = document.getElementById('quick-add-form');
                    if (quickAddForm && quickAddForm.classList.contains('open')) {
                        quickAddForm.classList.remove('open');
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
