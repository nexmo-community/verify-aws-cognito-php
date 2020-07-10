# verify-aws-cognito-php
Example in PHP for using Vonage Verify for 2FA with Amazon Cognito

## Setup Instructions

Clone this repo [nexmo-community/verify-aws-cognito-php](https://github.com/nexmo-community/verify-aws-cognito-php) and navigate into the newly created directory to proceed.

### Install Dependencies

This example requires the use of Composer to install dependencies and set up the autoloader.

Assuming you have Composer [installed globally](https://getcomposer.org/doc/00-intro.md#globally), run:

```
composer install
```

### AWS Setup

This example uses Amazon Cognito User Pools to hold users. I set up a User Pool as follows:

1. Navigate to the [Amazon Cognito Dashboard](https://console.aws.amazon.com/cognito/home) in the AWS Console.
1. Select `Manage User Pools`.
1. Create a new user pool.
    * Give the pool a name, and click `Step through settings`
    * Select `Email address or phone number` and pick `Allow email addresses`
    * Click `Next step`
    * Set the minimum password length, and desired complexity settings
    * Make sure to `Allow users to sign themselves up`
    * Click `Next step`
    * Leave the next step as-is, for this example. We will use Vonage for 2FA
    * Click `Next step`
    * Select a `FROM email address ARN` from the dropdown. This assumes you've already created an idenity in [Amazon Simple Email Service(SES)](https://console.aws.amazon.com/ses/home#verified-senders-email:)
    * Add a `FROM email address` as desired.
    * Leave the rest of this page unchanged. However, I do recommend you remove the trailing periods from the email messages. This prevents the recipient from mistakenly using the period as part of the temporary password.
    * Click `Next step`
    * Skip adding tags by clicking `Next step`
    * Skip devices by clicking `Next step`
    * Click the link to `Add an app client`
    * Give the app client a name
    * Uncheck the box to `Generate client secret`
    * Check the rest of the boxes
    * Click `Create app client`
    * Click `Next step`
    * Skip triggers by clicking `Next step`
    * Click `Create pool`

### Update Environment

Rename the provided `.env.default` file to `.env` and update the values as needed:

```env
AWS_PROFILE=default
AWS_ACCESS_KEY_ID=<aws-access-key-id>
AWS_SECRET_ACCESS_KEY=<aws-secret-access-key>
AWS_VERSION=latest
AWS_REGION=us-east-1
AWS_CLIENT_ID=<aws-client-id>
AWS_USERPOOL_ID=<aws-userpool-id>
NEXMO_API_KEY=<nexmo-api-key>
NEXMO_API_SECRET=<nexmo-api-secret>
```

_*NOTE:* All placeholders noted by `<>` need to be updated. Update the others as needed._

## Launch or Deploy

Test the app by running it locally with the PHP built-in webserver with the command:

```bash
php -S localhost:8080
```

View the main landing page by going to `http://localhost:8080` in a web browser.

> IMPORTANT: Though this app functions, `as-is` it is intended for educational purposes and is not ready for production/public use.

## Functionality

The app flow is as follows:

* From the main page, click either `Login` or `Register`.
    * After registration (`user_register.php`) the user is redirected to a mandatory password change page. (`login_reset.php`) Here they should utilize the temporary password emailed to them.
    * After updating the temporary password the user is redirected to the login page. (`login.php`)
    * The login is where a new user, or an existing user can login.
    * After successful login, the user is redirected to the 2FA verification page. (`login_verify.php`) Here they enter the 6 digit code sent to their mobile number.
    * Upon successful 2FA verification, the user is then redirected back to the main page (`index.php`) where they see they are now logged in with an option to logout. (`logout.php`)

## Contributing

We love questions, comments, issues - and especially pull requests. Either open an issue to talk to us, or reach us on twitter: <https://twitter.com/VonageDev>.
