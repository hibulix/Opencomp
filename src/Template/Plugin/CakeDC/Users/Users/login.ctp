<?php
/**
 * Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
use Cake\Core\Configure;

$this->assign('header', "Connexion");
?>


<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-fw fa-key"></i> Merci de vous identifier</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->create() ?>
                        <?= $this->Form->input('username', ['required' => true]) ?>
                        <?= $this->Form->input('password', ['required' => true]) ?>
                        <?php
                        if (Configure::read('Users.reCaptcha.login')) {
                            echo $this->User->addReCaptcha();
                        }
                        if (Configure::check('Users.RememberMe.active')) {
                            echo $this->Form->input(Configure::read('Users.Key.Data.rememberMe'), [
                                'type' => 'checkbox',
                                'label' => __d('CakeDC/Users', 'Remember me'),
                                'checked' => 'checked'
                            ]);
                        }
                        ?>
                        <?= implode(' ', $this->User->socialLoginList()); ?>
                        <?= $this->Form->button(__d('CakeDC/Users', 'Login'), ['class' => 'btn btn-lg btn-success']); ?>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-6 col-xs-6">
                        <div class="description-block border-right">
                            <h5 class="description-header">Pas encore parmis nous ?</h5>
                            <span class="description-text"><?= $this->Html->link(__d('CakeDC/Users', 'Register'), ['action' => 'register']) ?></span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <div class="description-block">
                            <h5 class="description-header">Mot de passe oublié ?</h5>
                            <span class="description-text"><?= $this->Html->link(__d('CakeDC/Users', 'Reset Password'), ['action' => 'requestResetPassword']) ?></span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </div>
    </div>
</div>
