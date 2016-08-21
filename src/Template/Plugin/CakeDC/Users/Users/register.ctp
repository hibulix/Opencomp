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

$this->assign('header', "Rejoindre les utilisateurs Opencomp");
?>

<?= $this->Form->create($user); ?>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-fw fa-user"></i> Qui êtes vous ?</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <?= $this->Form->input('first_name', ['label' => 'Votre prénom']); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->input('last_name', ['label' => 'Votre nom']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <?= $this->Form->input('email', ['label' => 'Votre adresse email', 'help' => 'Merci d\'indiquer une adresse valide, nous vous enverrons un email de validation.']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-fw fa-key"></i> Vos informations de connexion</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <?= $this->Form->input('username', ['label' => 'Nom d\'utilisateur', 'help' => 'Choisissez un nom d\'utilisateur']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?= $this->Form->input('password', ['label' => 'Mot de passe']); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->input('password_confirm', ['label' => 'Confirmer le mot de passe', 'type' => 'password']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-fw fa-legal"></i> Licence</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (Configure::read('Users.Tos.required')) {
                            echo $this->Form->input('tos', ['type' => 'checkbox', 'label' => 'J\'accepte les termes de la licence GNU Affero General Public License sous laquelle est placée le logiciel.', 'required' => true, 'escape' => false]);
                            echo '<p>' . $this->Html->link('<i class="fa fa-fw fa-book"></i> Lire la <strong>GNU Affero General Public License</strong>', 'https://www.gnu.org/licenses/agpl-3.0.fr.html', ['escape' => false, 'target' => '_blank']);
                        }
                        if (Configure::read('Users.reCaptcha.registration')) {
                            echo $this->User->addReCaptcha();
                        } ?>
                        <?= $this->Form->button('<i class="fa fa-fw fa-sign-in"></i> ' . __d('CakeDC/Users', 'Register'), ['class' => 'btn btn-lg btn-success', 'style' => 'margin-top: 20px;', 'escape' => false]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>