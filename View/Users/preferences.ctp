<div class="page-title">
    <h2>Préférences de l'utilisateur</h2>
</div>

<?php
echo $this->Form->create('User', array(
    'inputDefaults' => array(
        'div' => 'form-group',
        'label' => array(
            'class' => 'col col-md-3 control-label'
        ),
        'wrapInput' => 'col col-md-3',
        'class' => 'form-control'
    ),
    'class' => 'form-horizontal'
));

echo $this->Form->input('levels', array(
    'class'=>'chzn-select form-control',
    'data-placeholder' => 'Afficher les items de tous les niveaux',
    'default' => $preferences['levels'],
    'multiple' => true,
    'label' => array(
        'text' => 'N\'afficher que les items correspondant à ces niveaux dans les référentiels'
    )
));

?>

<div class="form-group">
    <?php echo $this->Form->submit('Enregistrer mes préférences', array(
    'div' => 'col col-md-9 col-md-offset-3',
    'class' => 'btn btn-primary'
)); ?>
</div>

<?php echo $this->Form->end();