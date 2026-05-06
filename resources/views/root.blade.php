<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="bg-secondary" x-data="{ activeOverlay: null }">
    <div class="flex justify-space-between gap-[2rem]">
        <x-card.card title="Test.pdf" fileType=".pdf"/>
        <x-card.card title="IT-woche1.txt" fileType=".txt" :tags="['mathe', 'informatik']"/>
        <x-card.card title="Business-IT.txt" fileType=".txt" :tags="['wirtschaft', 'informatik']"/>
    </div>

    </body>
</html>
