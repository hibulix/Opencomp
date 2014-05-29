<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<div class="alert alert-block alert-error">
  <h2 class="alert-heading"><i class="fa fa-bolt"></i> <?php echo __d('cake', 'Ooops - Une erreur interne est survenue.'); ?></h4>
<br />
    <h3><?php echo $name; ?></h3><br />
	<?php printf(
		__d('cake', 'L\'adresse demandée %s n\'a pas été trouvée sur le serveur.'),
		"<strong>'{$url}'</strong>"
	); ?>
<br /><br />
<?php
if (Configure::read('debug') > 0 ):
	echo $this->element('exception_stack_trace');
endif;
?>

</div>
