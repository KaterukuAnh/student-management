<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-700 text-white px-6 py-4 flex gap-6 items-center">
        <span class="font-bold text-lg">🎓 Student Management</span>
        <a href="{{ route('classrooms.index') }}"
           class="hover:underline">Lớp học</a>
        <a href="{{ route('students.index') }}"
           class="hover:underline">Học sinh</a>
        <a href="{{ route('subjects.index') }}"
           class="hover:underline">Môn học</a>
        <a href="{{ route('grades.index') }}"
           class="hover:underline">Điểm số</a>
    </nav>

    <!-- Content -->
    <main class="max-w-6xl mx-auto mt-8 px-4">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
    
</body>
</html>