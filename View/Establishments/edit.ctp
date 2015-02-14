<div class="page-title">
    <h2><?php echo __('Modifier un établissement'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à l\'établissement'), '/establishments/view/'.$this->request->data['Establishment']['id'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php 

echo $this->Form->create('Establishment', array(
    'inputDefaults' => array(
        'div' => 'form-group',
        'label' => array(
            'class' => 'col col-md-2 control-label'
        ),
        'wrapInput' => 'col col-md-3',
        'class' => 'form-control'
    ),
    'class' => 'form-horizontal'
));

echo $this->Form->input('id');

echo $this->Form->input('name', array(
    'label' => array(
        'text' => 'Nom de l\'établissement'
    )
)); 

echo $this->Form->input('address', array(
    'label' => array(
        'text' => 'Adresse'
    )
)); 

echo $this->Form->input('postcode', array(
    'label' => array(
        'text' => 'Code postal'
    )
)); 

echo $this->Form->input('town', array(
    'label' => array(
        'text' => 'Ville'
    )
)); 

echo $this->Form->input('user_id', array(
    'class'=>'chzn-select form-control',
    'label' => array(
        'text' => 'Direction assurée par'
        )
    )
);

echo $this->Form->input('academy_id', array(
    'class'=>'chzn-select form-control',
    'label' => array(
        'text' => 'Académie',
        )
    )
);

?>

<div class="form-group">
    <?php echo $this->Form->submit('Modifier l\'établissement', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();
