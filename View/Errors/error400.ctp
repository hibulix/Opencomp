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

<div class="alert alert-warning">
  <h4><i class="fa fa-bolt"></i> <?php echo __d('cake', 'Erreur 404 - La page que vous avez demandé n\'existe pas !'); ?></h4>
<br />
	<?php printf(
		__d('cake', 'Le chemin demandé %s n\'a pas été trouvé sur le serveur.'),
		"<strong>'{$url}'</strong>"
	); ?><br />
    <p>S'il ne s'agit pas d'une fausse manipulation, n'hésitez pas à <a href="http://projets.opencomp.fr/opencomp/issues/new"><i class="fa fa-bug"></i> ouvrir un ticket d'incident</a></p>
    <br />
    <a class="btn btn-xs btn-default" href="javascript:history.back();"><i class="fa fa-arrow-circle-left"></i> retourner à la page précédente</a>
<?php
if (Configure::read('debug') > 0 ):
	echo $this->element('exception_stack_trace');
endif;
?>

</div>
