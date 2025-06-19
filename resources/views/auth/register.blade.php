<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register</title>
    <style>
        body {
            background-color: #2E8B57;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 450px;
            text-align: center;
        }
        .logo {
            width: 150px; /* Ubah ukuran logo agar lebih besar */
            display: block;
            margin: 0 auto;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-control {
            width: 95%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #2E8B57;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #256b45;
        }
        .register-link {
            margin-top: 15px;
            display: block;
            color: #2e7d32;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="/images/logo.png" alt="Logo" class="logo">
        <h2>Register</h2>
        <form action="{{route('register')}}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p><a href="{{ route('login') }}" class="register-link">Sudah punya akun? Login</a></p>
    </div>
</body>

</html>
