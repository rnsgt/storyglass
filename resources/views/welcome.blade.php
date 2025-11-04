<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StoryGlass</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #c4dfdf;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .logo {
            width: 200px;
            margin-bottom: 25px;
        }
        .title {
            font-size: 2rem;
            font-weight: 600;
            color: #2a3a3a;
        }
        .btn-custom {
            background-color: #84a9ac;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
            border: none;
        }
        .btn-custom:hover {
            background-color: #558b8b;
        }
    </style>
</head>
<body>
    <img src="{{ asset('logo.png') }}" alt="StoryGlass Logo" class="logo">
    <h1 class="title">Selamat Datang di StoryGlass</h1>
    <p>Temukan kacamata dengan cerita unik di balik setiap desain.</p>
    <a href="{{ route('login') }}" class="btn btn-custom">Masuk</a>
</body>
</html>
