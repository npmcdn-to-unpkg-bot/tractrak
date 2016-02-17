<?php
use Mailgun\Mailgun;

require('_bootstrap.php');

$I = new AcceptanceTester($scenario);
$I->wantTo('register an account');
$I->amOnPage('/');
$I->seeLink('Register');
$I->click('Register');

$I->fillField('Name', $testUser['name']);
$I->fillField('E-mail', $testUser['email']);
$I->fillField('Password', $testUser['password']);
$I->fillField('Password Confirmation', $testUser['password']);

$I->seeLink('Register');
$I->click('Register');
$I->waitForElement(5);
$I->see('Your account was successfully created. We have sent you an e-mail to confirm your account.');

// Get the email
# Instantiate the client.
$mg = new Mailgun(env('MAILGUN_SECRET'), 'api.mailgun.net', 'v3');
$results = $mg->get("$domain/events", ['event' => 'stored']);
dd($results);
$events = json_decode();
var_dump($events);
$email = json_decode($events[0]);
var_dump($email);
$mg->delete($email['message-url']);