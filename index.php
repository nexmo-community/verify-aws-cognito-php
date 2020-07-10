<?php
declare(strict_types=1);

session_start();
?>
<html>
    <head>
        <title>Home Page</title>
    </head>
    <body>
        <div>
            <?php if ($_SESSION['verified'] && $_SESSION['access_token']): ?>
                You are logged in. Thank you. <a href="logout.php" title="Logout">Logout</a>
            <?php else: ?>
                Please <a href="login.php" title="Login">Login</a>, or <a href="user_register.php" title="Register">Register</a>
            <?php endif; ?>
        </div>
    </body>
</html>