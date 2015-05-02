<div class="page-title">
    <h2><?php echo __('Éditer une académie'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à l\'académie'), '/academies/view/'.$academy->id, array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create($academy, ['align' => [
    'md' => [
        'left' => 2,
        'middle' => 3,
        'right' => 7,
    ],
]]);


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

echo $this->Form->input('users._ids', array(
    'class'=>'chzn-select',
    'data-placeholder'=>'Ajoutez un responsable ...',
    'label' => array(
        'text' => 'Responsable(s) de l\'académie'
        )
    )
);

?>


<?php echo $this->Form->submit('Enregistrer les modifications', array(
    'class' => 'btn btn-primary'
)); ?>


<?php echo $this->Form->end(); ?>
