<div class="page-title">
    <h2><?php echo __('Paramètres de l\'application'); ?></h2>
</div>

<?php 

echo $this->Form->create('Settings', [
    'align' => [
        'md' => [
            'left' => 2,
            'middle' => 3,
            'right' => 7,
        ]]
]);

?> <h3><i class="fa fa-clock-o"></i> Paramètres de temps</h3><hr /> <?php

echo $this->Form->input('Setting.currentYear', array(
	'type' => 'select',
	'class'=>'chzn-select',
    'options' => $years,
    'default' => $currentYear,
    'label' => array(
        'text' => 'Année scolaire courante'
    )
)); 

echo $this->Form->input('Setting.lastYear', array(
	'type' => 'select',
	'class'=>'chzn-select',
    'options' => $years,
    'default' => $lastYear,
    'label' => array(
        'text' => 'Année scolaire précédente'
    )
));

?> <br /><h3><?= $this->Html->image("yubikey.png", array("height"=>30,"alt" => "Yubikey logo")) ?> Paramètres de sécurité</h3><hr /> <?php

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

<div class="form-group">
     <?php echo $this->Form->submit('Enregistrer les paramètres', array(
         'div' => 'col col-md-9 col-md-offset-2',
         'class' => 'btn btn-primary'
     )); ?>
</div>

<?php echo $this->Form->end();
