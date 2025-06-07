<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Hotel Luna Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap');

    .font-playfair {
      font-family: 'Playfair Display', serif;
    }
  </style>
</head>

<body class="min-h-screen flex">
  <!-- Left side with logo -->
  <div class="hidden md:flex w-1/2 bg-[#0f1a1a] justify-center items-center">
    <img alt="Logo" height="600" src="/hotelluna/public/img/hotellunalogo.png"
      width="600" />
  </div>
  <!-- Right side with form -->
  <div class="flex flex-1 bg-[#0a0a0a] justify-center items-center px-6 md:px-20">
    <form class="w-full max-w-md space-y-6" action="procesar_login.php" method="POST">
      <?php if (isset($_GET['error'])): ?>
        <div class="text-red-400 text-center text-sm mb-2">Usuario o contraseña incorrectos</div>
      <?php endif; ?>
      <input
        class="w-full rounded-md bg-[#121212] border border-[#222222] px-4 py-3 text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#3a3a3a]"
        placeholder="Usuario" name="username" required type="text" autocomplete="username" />
      <input
        class="w-full rounded-md bg-[#121212] border border-[#222222] px-4 py-3 text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#3a3a3a]"
        placeholder="Contraseña" name="password" required type="password" autocomplete="current-password" />
      <div class="text-right">
        <a class="text-gray-400 text-sm hover:underline" href="#">
          ¿Olvidaste tu contraseña?
        </a>
      </div>
      <button
        class="w-full bg-[#2f3a3e] text-gray-400 font-semibold py-3 rounded-md hover:bg-[#3a4a4e] transition-colors"
        type="submit">
        Ingresar
      </button>
    </form>
  </div>
</body>

</html>