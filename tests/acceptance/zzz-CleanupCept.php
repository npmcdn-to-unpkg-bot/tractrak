<?php
use Mailgun\Mailgun;

require('_bootstrap.php');

$I = new AcceptanceTester($scenario);
$I->wantTo('cleanup all the testing mess');

// Delete all emails
# Instantiate the client.
$mg = new Mailgun(env('MAILGUN_SECRET'), 'api.mailgun.net', 'v3');
//$results = $mg->get("$domain/messages");
//$mg->delete($results->http_response_body['paging']['last']);
//$email = json_decode();
//var_dump($email);
//$mg->delete($email['message-url']);

// Delete the user we generated
//$testUser = User::where('email', $testUser['email']);
//$testUser->delete();
//$testUser->save();