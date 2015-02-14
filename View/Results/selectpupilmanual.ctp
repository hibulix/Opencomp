<div class="page-title">
    <h2><?php echo __('Quel est l\'élève dont vous souhaitez saisir le résultat'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-check"></i> '.__('J\'ai terminé la saisie'), '/evaluations/manageresults/'.$evaluation_id, array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create('Result', array(
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

echo $this->Form->input('pupil_id', array(
        'class'=>'chzn-select form-control send',
        'data-placeholder' => 'Sélectionnez un élève',
        'label' => array(
            'text' => 'Élève'
        )
    )
);

?>

<div class="form-group">
    <?php echo $this->Form->submit('Saisir le résultat', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();
