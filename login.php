<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="controller/loginController.php" method="POST">
        <input type="text" id="username" name="username" placeholder="NISN / NUPTK" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p><a href="register.php">register</a></p>
</body>
</html>