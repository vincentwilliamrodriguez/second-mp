<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <div class="navbar">
        <a href="#" class="home-button">
          <img src="shopstream.png" alt="Home">
        </a>
        <a href="#">Shop</a>
        <a href="#">Support</a>
      </div>

    <div class="content">
        @yield('content')
    </div>

</body>
</html>

