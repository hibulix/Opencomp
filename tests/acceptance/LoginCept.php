<?php

$I = new AcceptanceTester($scenario);

$I->wantTo('login to application');
$I->login();
