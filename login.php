<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/vendor/autoload.php';

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Nexmo\Client\Credentials\Basic;
use Nexmo\Client;

Dotenv\Dotenv::createImmutable(__DIR__)->load();

$myNotice = 'Please Login!';

// If this is a form post, authenticate with Cognito
if ($_POST) {

    $cognitoIdentityProviderClient = new CognitoIdentityProviderClient([
        'profile' => $_ENV['AWS_PROFILE'],
        'region' => $_ENV['AWS_REGION'],
        'version' => $_ENV['AWS_VERSION']
    ]);

    // perform login
    $result = $cognitoIdentityProviderClient->adminInitiateAuth([
        'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
        'ClientId' => $_ENV['AWS_CLIENT_ID'],
        'UserPoolId' => $_ENV['AWS_USERPOOL_ID'],
        'AuthParameters' => [
            'USERNAME' => $_POST['username'],
            'PASSWORD' => $_POST['password'],
        ]
    ]);

    // get token from login, set in session or return issue
    if ($result->get('AuthenticationResult')) {
        $_SESSION['access_token'] = $result->get('AuthenticationResult')['AccessToken'];

        // on success kick off 2FA code
        $credentials = new Basic($_ENV['NEXMO_API_KEY'], $_ENV['NEXMO_API_SECRET']);
        $client = new Client($credentials);

        $verification = $client->verify()->start([
            'number' => $_SESSION['phone_number'],
            'brand' => 'Vonage',
            'code_length' => '6'
        ]);

        $_SESSION['verification_id'] = $verification->getRequestId();

        // redirect for 2FA verify
        header("Location: /login_verify.php");

    } else {
        // redirect to change password for new users
        if ($result->get('ChallengeName') == 'NEW_PASSWORD_REQUIRED') {

            $_SESSION['USER_ID_FOR_SRP'] = $result->get('ChallengeParameters')['USER_ID_FOR_SRP'];
            $_SESSION['CogSession'] = $result->get('Session');
            header("Location: /login_newpwrqd.php");
        }

        $myNotice = $result->get('ChallengeName');
    }
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

            <?php if (!$_SESSION['access_token']): ?>
            <div id="login-form">
                <form action="login.php" method="post">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" /><br>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" /><br>
                    <input type="submit">
                </form>
            </div>
            <?php else: ?>
            <div>You are already logged in. <a href="logout.php" title="Login as different user" >Login as a different user.</a></div>
            <?php endif; ?>
        </main>
    </body>
</html>