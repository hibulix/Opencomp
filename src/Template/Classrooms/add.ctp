<div class="page-title">
    <h2><?php echo __('Ajouter une classe'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à l\'établissement'), '/establishments/view/'.$establishment_id, array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php 

echo $this->Form->create($classroom, [
    'align' => [
    'md' => [
        'left' => 2,
        'middle' => 3,
        'right' => 7,
    ],
]]);

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

echo $this->Form->input('users._ids', array(
	'class'=>'chzn-select form-control',
	'data-placeholder'=>'Int extérieurs, mis-tps, décharge ...',
    'label' => array(
        'text' => 'Intervenants classe'
    )
)); 

?>

<div class="form-group">
    <?php echo $this->Form->submit('Ajouter la classe', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();