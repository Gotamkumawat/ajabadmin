<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>

    <?php if($this->session->flashdata('error')): ?>
        <p style="color:red;"><?= $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

    <?= validation_errors('<p style="color:red;">','</p>'); ?>

    <?= form_open('login'); ?>
        <label>Username:</label><br>
        <input type="text" name="username" value="<?= set_value('username'); ?>"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password"><br><br>

        <button type="submit">Login</button>
    <?= form_close(); ?>
</body>
</html>
