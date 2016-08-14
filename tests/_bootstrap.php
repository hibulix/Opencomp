<?php

/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */
use Cake\DataSource\ConnectionManager;

require dirname(__DIR__) . '/config/bootstrap.php';

// When tests are running, we're using opencomp_test database
// instead of standard opencomp database.
ConnectionManager::alias('test', 'default');
