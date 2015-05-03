<div class="page-title">
    <h2><?php echo __('Ajouter une académie'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour aux académies'), 'index', array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php 

echo $this->Form->create($academy, ['novalidate',
    'align' => [
        'md' => [
            'left' => 2,
            'middle' => 3,
            'right' => 7,
        ],
    ]
]);

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
    'class'=>'chzn-select form-control',
    'data-placeholder'=>'Ajoutez un responsable ...',
    'label' => array(
        'text' => 'Responsable(s) de l\'académie'
        )
    )
);
    
?>

<div class="form-group">
    <?php echo $this->Form->submit('Enregistrer les modifications', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();