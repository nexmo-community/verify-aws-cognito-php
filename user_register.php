<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/vendor/autoload.php';

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

Dotenv\Dotenv::createImmutable(__DIR__)->load();

$myNotice = "Sign up!";

if ($_POST) {

    $cognitoIdentityProviderClient = new CognitoIdentityProviderClient([
        'profile' => $_ENV['AWS_PROFILE'],
        'region' => $_ENV['AWS_REGION'],
        'version' => $_ENV['AWS_VERSION']
    ]);

    // create user
    try {
        $result = $cognitoIdentityProviderClient->adminCreateUser([
            "DesiredDeliveryMediums" => ["EMAIL", "SMS"],
//            "MessageAction" => "SUPPRESS",
            "UserAttributes" => [
                [
                    "Name" => "phone_number",
                    "Value" => $_POST['phone_number']
                ],
                [
                    "Name" => "phone_number_verified",
                    "Value" => "true"
                ],
                [
                    "Name" => "email",
                    "Value" => $_POST['email']
                ],
                [
                    "Name" => "email_verified",
                    "Value" => "true"
                ],
            ],
            "Username" => 'adamculp@uws.net',
            "UserPoolId" => $_ENV['AWS_USERPOOL_ID'],
//            "ValidationData" => [ // lambda trigger to validate phone
//                "Name" => "string",
//                "Value" => "string"
//            ]
        ]);

        $_SESSION['phone_number'] = $_POST['phone_number'];

        header("Location: /login_reset.php?email=" . $_POST['email']);

    } catch (Exception $e) {
        return $e->getMessage();
    }
}

?>
<html lang="en">
    <head>
        <title>User Register Form</title>
    </head>
    <body>
        <header>
            <?php echo $myNotice; ?>
        </header>
        <main>
            <div id="user-create-form">
                <form action="user_register.php" method="post">

                    <label for="email">Email (Username)</label>
                    <input type="text" name="email" id="email" /><br>

                    <label for="phone_number">Phone (mobile)</label>
                    <input type="text" name="phone_number" id="phone_number" /><br>
                    <span>Format: +15555555555</span><br><br>

                    <input type="submit">
                </form>
            </div>
        </main>
    </body>
</html>
