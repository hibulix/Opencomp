<div class="page-title">
    <h2><?php echo __('Modifier une classe'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à la classe'), '/classrooms/view/'.$this->data['Classroom']['id'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php 

echo $this->Form->create('Classroom', array(
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

echo $this->Form->input('title', array(
    'label' => array(
        'text' => 'Nom de la classe'
    )
)); 

echo $this->Form->input('user_id', array(
	'class'=>'chzn-select form-control',
    'label' => array(
        'text' => 'Enseignant titulaire'
    )
)); 

echo $this->Form->hidden('year_id');

echo $this->Form->hidden('establishment_id');

echo $this->Form->input('User', array(
	'class'=>'chzn-select form-control',
	'data-placeholder'=>'Int extérieurs, mis-tps, décharge ...',
    'label' => array(
        'text' => 'Intervenants classe'
    )
)); 

?>

<div class="form-group">
    <?php echo $this->Form->submit('Modifier la classe', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();