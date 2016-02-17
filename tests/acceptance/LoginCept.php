<?php

require('_bootstrap.php');

$I = new AcceptanceTester($scenario);
$I->wantTo('login');
$I->amOnPage('/');
$I->seeLink('Login');
$I->click('Login');

$I->fillField('E-mail', $testUser['email']);
$I->fillField('Password', $testUser['password']);
$I->click('Login');

$I->see('Dashboard');
$I->see('test@tractrak.com');
$I->see('Meet Management');
$I->see('Your Meets');