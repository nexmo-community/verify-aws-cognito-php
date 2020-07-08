<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/vendor/autoload.php';

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

Dotenv\Dotenv::createImmutable(__DIR__)->load();

$myNotice = 'You must change your password. Please enter a new one below.';

// If this is a form post, try authenticating with Cognito
if ($_POST) {

    $cognitoIdentityProviderClient = new CognitoIdentityProviderClient([
        'profile' => $_ENV['AWS_PROFILE'],
        'region' => $_ENV['AWS_REGION'],
        'version' => $_ENV['AWS_VERSION']
    ]);

    $result = $cognitoIdentityProviderClient->adminRespondToAuthChallenge([
        "ChallengeName" => "NEW_PASSWORD_REQUIRED",
        "ChallengeResponses" => [
            "USERNAME" => $_SESSION['USER_ID_FOR_SRP'],
            "NEW_PASSWORD" => $_POST['new_password'],
        ],
        "ClientId" => $_ENV['AWS_CLIENT_ID'],
        "Session" => $_SESSION['CogSession'],
        "UserPoolId" => $_ENV['AWS_USERPOOL_ID']
    ]);

    // redirect to login
    header("Location: /login.php");

}
?>
<html lang="en">
    <head>
        <title>Password Reset</title>
    </head>
    <body>
        <header>
            <?php echo $myNotice; ?>
        </header>
        <main>
            <div id="login-form">
                <form action="login_newpwrqd.php" method="post">

                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" id="new_password" /><br><br>

                    <input type="submit">
                </form>
            </div>
        </main>
    </body>
</html>