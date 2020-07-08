<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/vendor/autoload.php';

use Nexmo\Client\Credentials\Basic;
use Nexmo\Client;
use Nexmo\Verify\Verification;

Dotenv\Dotenv::createImmutable(__DIR__)->load();

// call to nexmo and kick off verify
if ($_POST) {
    $credentials = new Basic($_ENV['NEXMO_API_KEY'], $_ENV['NEXMO_API_SECRET']);
    $client = new Client($credentials);

    $verification = new Verification($_SESSION['verification_id']);
    $result = $client->verify()->check($verification, $_POST['code']);

    $_SESSION['verified'] = true;

    // redirect to app
    header("Location: /index.php");
}

?>
<html lang="en">
    <head>
        <title>2FA Verify</title>
    </head>
    <body>
        <header>
            Please enter the code received at <?php echo preg_replace('/(.+?)/', '*', substr($_SESSION['phone_number'], 0, -4)); ?><?php echo substr($_SESSION['phone_number'], -4, strlen($_SESSION['phone_number'])-4); ?>
        </header>
        <main>
            <div id="verify-form">
                <form action="login_verify.php" method="post">
                    <label for="code">Verification Code</label>
                    <input type="text" name="code" id="code" />
                    <input type="submit">
                </form>
            </div>
        </main>
    </body>
</html>