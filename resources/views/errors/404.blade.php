<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Radouane Lab</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">

    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>


</head>
<body class="font-sans antialiased">
    <div class="h-screen w-screen bg-white flex items-center">
    <div class="container flex flex-col md:flex-row items-center justify-between px-5 text-gray-700">
        <div class="w-full lg:w-1/2 mx-8">
            <div class="text-7xl text-lab-red font-dark font-extrabold mb-8">404</div>
            <p class="text-2xl md:text-3xl font-light leading-normal mb-8">
                La page que vous recherchez semble introuvable
            </p>

            <a href="/" class="px-5 inline py-3 text-sm font-medium leading-5 shadow-2xl text-white transition-all duration-400 border border-transparent rounded-lg focus:outline-none bg-lab-purple active:bg-red-600 hover:bg-lab-red">Retour à la page d'accueil</a>
        </div>
        <div class="w-full lg:flex lg:justify-end lg:w-1/2 mx-5 my-12">
            <img src="/images/errors/404.jpg" class="" alt="Page not found">
        </div>

    </div>
</div>
</body>
</html>
