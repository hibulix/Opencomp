<div class="page-title">
    <h2><?php echo __('Éditer une académie'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à l\'académie'), '/academies/view/'.$this->data['Academy']['id'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php 

echo $this->Form->create('Academy', array(
    'inputDefaults' => array(
        'div' => 'form-group',
        'label' => array(
            'class' => 'col col-md-2 control-label'
        ),
        'wrapInput' => 'col col-md-4',
        'class' => 'form-control'
    ),
    'class' => 'form-horizontal'
));

echo $this->Form->input('id');

echo $this->Form->input('name', array(
    'label' => array(
        'text' => 'Nom de l\'académie'
    )
)); 

echo $this->Form->input('type', array(
    'type' => 'select',
    'options' => array('0'=>'Académie','1'=>'Sous-rectorat'),
    'label' => array(
        'text' => 'Type d\'académie'
    )
)); 

echo $this->Form->input('User', array(
    'class'=>'chzn-select form-control',
    'data-placeholder'=>'Ajoutez un responsable ...',
    'label' => array(
        'text' => 'Responsable(s) de l\'académie'
        )
    )
);
    
?>

<div class="form-group">
    <div class="col col-md-9 col-md-offset-2">
        <?php echo $this->Form->submit('Enregistrer les modifications', array(
            'div' => false,
            'class' => 'btn btn-primary'
        )); ?>
    </div>
</div>

<?php echo $this->Form->end();