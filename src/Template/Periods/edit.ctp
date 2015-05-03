<div class="page-title">
    <h2><?php echo __('Modifier une période'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à l\'établissement'), '/establishments/view/'.$this->request->query['establishment_id'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create($period, ['align' => [
    'md' => [
    'left' => 2,
    'middle' => 5,
    'right' => 6,
    ],
]]);

    echo $this->Form->input('id');
    echo $this->Form->input('begin', ['label' => 'Début de la période']);
    echo $this->Form->input('end', ['label' => 'Fin de la période']);
?>

<div class="form-group">
    <?php echo $this->Form->submit('Enregistrer cette période', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();
