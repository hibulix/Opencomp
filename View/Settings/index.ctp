<div class="page-title">
    <h2><?php echo __('Paramètres de l\'application'); ?></h2>
</div>

<?php 

echo $this->Form->create('Settings', array(
    'class' => 'form-horizontal',
));

echo $this->Form->input('Setting.currentYear', array(
	'type' => 'select',
	'class'=>'chzn-select',
    'options' => $years,
    'selected' => $currentYear,
    'label' => array(
        'text' => 'Année scolaire courante'
    )
)); 

echo $this->Form->input('Setting.lastYear', array(
	'type' => 'select',
	'class'=>'chzn-select',
    'options' => $years,
    'selected' => $lastYear,
    'label' => array(
        'text' => 'Année scolaire précédente'
    )
)); 

echo $this->Form->input('Setting.saveOnExit', array(
	'type' => 'select',
    'options' => array(1 => 'Oui', 0 => 'Non'),
    'selected' => $saveOnExit,
    'label' => array(
        'text' => 'Copie de sauvegarde lors de la déconnexion'
    )
)); 

echo $this->Form->input('Setting.pathMysqldump', array(
    'label' => array(
        'text' => 'Chemin exécutable mysqldump',
    ),
    'class' => 'input-xxlarge',
    'value' => $pathMysqldump
)); 

echo $this->Form->input('Setting.pathBackup', array(
    'label' => array(
        'text' => 'Répertoire de sauvegarde',
    ),
    'class' => 'input-xxlarge',
    'value' => $pathBackup
)); 

echo $this->Form->input('Setting.yubikeyClientId', array(
    'label' => array(
        'text' => 'Yubikey ClientID',
    ),
    'class' => 'input-small',
    'helpBlock' => __('Vous pouvez obtenir une clé d\'API Yubikey sur le site ').'<a target="blank" href="https://upgrade.yubico.com/getapikey/">https://upgrade.yubico.com/getapikey/</a>',
    'value' => $yubikeyClientId
)); 

echo $this->Form->input('Setting.yubikeySecretKey', array(
    'label' => array(
        'text' => 'Yubikey Secret Key',
    ),
    'class' => 'input',
    'value' => $yubikeySecretKey
)); 

?>

<div class="form-actions">
     <?php echo $this->Form->button('Enregistrer les paramètres', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>        
</div>

<?php echo $this->Form->end(); ?>