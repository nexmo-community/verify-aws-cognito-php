<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/vendor/autoload.php';

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

Dotenv\Dotenv::createImmutable(__DIR__)->load();

$myNotice = 'Check your email for the temporary password, then use the form below to change it!';

// If this is a form post, try authenticating with Cognito
if ($_POST) {

    $cognitoIdentityProviderClient = new CognitoIdentityProviderClient([
        'profile' => $_ENV['AWS_PROFILE'],
        'region' => $_ENV['AWS_REGION'],
        'version' => $_ENV['AWS_VERSION']
    ]);

    $result = $cognitoIdentityProviderClient->changePassword([
        "AccessToken" => $_SESSION['access_token'],
        "PreviousPassword" => $_POST['old_password'],
        "ProposedPassword" => $_POST['new_password']
    ]);

    // redirect to login
    header("Location: /login.php");

}
?>
<html lang="en">
    <head>
        <title>Login Page</title>
    </head>
    <body>
        <header>
            <?php echo $myNotice; ?>
        </header>
        <main>
            <div id="login-form">
                <form action="login.php" method="post">
                    <input type="hidden" name="email" id="email" value="<?php echo $_POST['email']; ?>">

                    <label for="old_password">Old Password</label>
                    <input type="password" name="old_password" id="old_password" /><br>

                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" id="new_password" /><br><br>

                    <input type="submit">
                </form>
            </div>
        </main>
    </body>
</html>