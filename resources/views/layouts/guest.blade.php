<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WarungKu') }} – Login</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
      rel="stylesheet"
    />
    <link rel="icon" type="image/png" href="{{ asset('images/WarungKu_Logo.png') }}">


    <style>
      html, body {
        height: 100%;
        overflow: hidden;
        background: #f4f6fa;
        font-family: "Inter", sans-serif;
      }

      .material-symbols-outlined {
        font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
      }
    </style>

    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: "#6A5AF9",
              primary2: "#4A90E2",
              textDark: "#1A1A1A",
              textMuted: "#6c6c6c",
              softBg: "#F4F6FA",
              cardBg: "#ffffff",
            },
            borderRadius: {
              xl: "1rem",
            },
          },
        },
      };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body>
    <div class="flex h-full w-full items-center justify-center p-4">
      <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-6">
        
        <!-- Logo -->
        <div class="flex items-center justify-center mb-8">
          <div class="flex items-center gap-3">
            <img src="{{ asset('images/WarungKu_Logo.png') }}" 
             alt="WarungKu" 
             class="h-10" />
            <span class="text-2xl font-bold text-primary">WarungKu</span>
          </div>
        </div>

        <!-- Main Form Content (slot Breeze) -->
        <div class="flex flex-col gap-5">
          {{ $slot }}
        </div>

      </div>
    </div>
  </body>
</html>
