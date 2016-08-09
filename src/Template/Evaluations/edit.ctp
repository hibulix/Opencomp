<div class="page-title">
    <h2><?php echo __('Modifier une évaluation'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à la classe'), '/classrooms/viewtests/'.$evaluation->classroom_id, array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create($evaluation, ['align' => [
    'md' => [
        'left' => 2,
        'middle' => 5,
        'right' => 5,
    ],
]]);

echo $this->Form->input('title', array(
    'label' => array(
        'text' => 'Titre de l\'évaluation'
    )
));

echo $this->Form->input('user_id', array(
        'class'=>'chzn-select form-control',
        'label' => array(
            'text' => 'Évalué par'
        )
    )
);

echo $this->Form->input('period_id', array(
        'class'=>'chzn-select form-control',
        'help' => '<span style="font-style: italic; margin-top:10px; margin-bottom:-10px;" class="help-block"><i class="fa fa-lightbulb-o"></i> '.__("La période courante de l'établissement a été automatiquement sélectionnée.").'</span>',
        'label' => array(
            'text' => 'Période associée'
        )
    )
);

echo $this->Form->input('pupils._ids', array(
        'class'=>'chzn-select form-control',
        'id'=>'PupilPupil',
        'data-placeholder' => 'Cliquez ici ou sur les boutons de niveaux pour ajouter des élèves.',
        'help' => '<span style="font-style: italic; margin-top:10px; margin-bottom:-10px;" class="text-danger"><i class="fa fa-warning"></i> Si vous supprimez des élèves dont les résultats ont déjà été saisis, ces derniers seront également supprimés !</span><div class="help-block btn-toolbar">'.$btn_nvx_string.'</div>',
        'label' => array(
            'text' => 'Élèves ayant passé l\'évaluation'
        )
    )
);

?>

<div class="form-group">
    <?php echo $this->Form->submit('Modifier cette évaluation', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();
