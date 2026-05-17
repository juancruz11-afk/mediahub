<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediaFactory - Herramientas Multimedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col font-sans text-slate-800">

    <!-- Header / Navbar -->
    <header class="bg-white shadow-sm py-4 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-black tracking-tight text-slate-800 cursor-pointer" onclick="switchView('view-home')">
                Media<span class="text-blue-600">Factory</span>
            </h1>
            <nav>
                <button onclick="switchView('view-home')" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition">
                    Inicio
                </button>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8 flex flex-col items-center">
        <!-- Inyectamos los módulos separados -->
        @include('modules.home')
        @include('modules.video')
        @include('modules.archivos')
    </main>

    <!-- Inyectamos el JavaScript -->
    @include('modules.scripts')

</body>
</html>