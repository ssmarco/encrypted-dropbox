<?php

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Environment;
use League\OAuth2\Client\Provider\Google;

class GoogleController extends Controller
{
    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * [
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * ];
     * </code>
     *
     * @var array
     */
    private static $allowed_actions = [
        'google',
        'callback'
    ];

    protected function init()
    {
        parent::init();
        // You can include any CSS or JS required by your project here.
        // See: https://docs.silverstripe.org/en/developer_guides/templates/requirements/
    }

    public function callback(HTTPRequest $request)
    {
        $provider = new Google([
            'clientId'     => Environment::getEnv('CLIENT_ID'),
            'clientSecret' => Environment::getEnv('CLIENT_SECRET'),
            'redirectUri'  => Environment::getEnv('SS_BASE_URL') . '/api/callback',
            'hostedDomain' => Environment::getEnv('SS_BASE_URL'),
        ]);

        // Try to get an access token (using the authorization code grant)
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // Optional: Now you have a token you can look up a users profile data
        try {

            // We got an access token, let's now get the owner details
            $ownerDetails = $provider->getResourceOwner($token);

            // Use these details to create a new profile
            // printf('Hello %s!', $ownerDetails->getFirstName());
            var_dump($ownerDetails->toArray());
            exit;

        } catch (Exception $e) {

            // Failed to get user details
            exit('Something went wrong: ' . $e->getMessage());

        }

        // Use this to interact with an API on the users behalf
        echo $token->getToken();

        // Use this to get a new access token if the old one expires
        echo $token->getRefreshToken();

        // Number of seconds until the access token will expire, and need refreshing
        echo $token->getExpires();
    }

    public function google(HTTPRequest $request)
    {
        $provider = new Google([
            'clientId'     => Environment::getEnv('CLIENT_ID'),
            'clientSecret' => Environment::getEnv('CLIENT_SECRET'),
            'redirectUri'  => Environment::getEnv('SS_BASE_URL') . '/api/callback',
            'hostedDomain' => Environment::getEnv('SS_BASE_URL'),
        ]);

        if (!empty($_GET['error'])) {

            // Got an error, probably user denied access
            exit('Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));

        } elseif (empty($_GET['code'])) {

            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: ' . $authUrl);
            exit;

        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

            // State is invalid, possible CSRF attack in progress
            unset($_SESSION['oauth2state']);
            exit('Invalid state');

        }
    }
}
