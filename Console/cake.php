#!/usr/bin/php -q
<?php
/**
 * Command-line code generation utility to automate programmer chores.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Console
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$ds = DIRECTORY_SEPARATOR;
$dispatcher = dirname(__DIR__) . $ds . 'Vendor' . $ds . 'cakephp' . $ds . 'cakephp' . $ds . 'lib' . $ds . 'Cake' . $ds . 'Console' . $ds . 'ShellDispatcher.php';

if (!include ($dispatcher)) {
	trigger_error('Could not locate CakePHP core files.', E_USER_ERROR);
}
unset($paths, $path, $dispatcher, $root, $ds);

return ShellDispatcher::run($argv);
