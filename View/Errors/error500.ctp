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

<div class="alert alert-danger">
    <h4><i class="fa fa-bolt"></i> <?php echo __d('cake', 'Erreur 500 - Erreur interne du serveur !'); ?></h4>
    <br />
    <?php
    if (Configure::read('debug') > 0 ):
    ?>
        <p class="error">
            <strong><?php echo __d('cake_dev', 'Error'); ?>: </strong>
            <?php echo h($error->getMessage()); ?>
            <br>

            <strong><?php echo __d('cake_dev', 'File'); ?>: </strong>
            <?php echo h($error->getFile()); ?>
            <br>

            <strong><?php echo __d('cake_dev', 'Line'); ?>: </strong>
            <?php echo h($error->getLine()); ?>
        </p>
    <?php
        echo $this->element('exception_stack_trace');
    else:
    ?>
        <?php printf(
        __d('cake', 'Le serveur a rencontré une erreur lors du traitement de la requête %s et n\'a pas été en mesure de fournir une réponse.'),
        "<strong>'{$url}'</strong>"
    ); ?><br />
        <p>Il s'agit probablement d'une erreur de programmation dans Opencomp. Bien que nous soyons notifiés automatiquement de ce type d'erreur, n'hésitez pas à <a href="http://projets.opencomp.fr/opencomp/issues/new"><i class="fa fa-bug"></i> ouvrir un ticket d'incident</a></p>
        <br />
        <a class="btn btn-xs btn-default" href="javascript:history.back();"><i class="fa fa-arrow-circle-left"></i> retourner à la page précédente</a>
    <?php
    endif;
    ?>

</div>
