<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            width: 100%;
            margin-top: 15px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
        }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="login-box">
    <h3>Admin Login</h3>

    <?php if($this->session->flashdata('error')): ?>
        <p class="error"><?= $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

    <form action="<?= base_url('authenticate') ?>" method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
