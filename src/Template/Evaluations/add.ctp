<div class="page-title">
    <h2><?php echo __('Ajouter une évaluation'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à la classe'), '/classrooms/viewtests/'.$classroom_id, array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create('Evaluation', ['align' => [
    'md' => [
        'left' => 2,
        'middle' => 5,
        'right' => 6,
    ],
]]);

echo $this->Form->input('title', array(
    'label' => array(
        'text' => 'Titre de l\'évaluation'
    )
));

echo $this->Form->hidden('classroom_id', array('value' => $classroom_id));

echo $this->Form->input('user_id', array(
    'class'=>'chzn-select form-control',
    'label' => array(
        'text' => 'Évalué par'
        )
    )
);

echo $this->Form->input('period_id', array(
    'class'=>'chzn-select form-control',
    'default'=>$current_period,
    'help' => '<span style="font-style: italic; margin-top:10px; margin-bottom:-10px;" class="help-block"><i class="fa fa-lightbulb-o"></i> '.__("La période courante de l'établissement a été automatiquement sélectionnée.").'</span>',
    'label' => array(
        'text' => 'Période associée'
        )
    )
);

foreach($pupils as $class => $list){
	$btn_nvx[$class] = '<div class="btn-group">';
	$btn_nvx[$class] .= $this->Form->button('Tous les '.$class, array('class'=> 'selectPupils btn btn-xs btn-default', 'value'=>$class, 'escape'=>false));
	$btn_nvx[$class] .= $this->Form->button('<i class="fa fa-ban"></i>', array('class'=> 'unselectPupils btn btn-xs btn-default', 'value'=>$class, 'escape'=>false));
	$btn_nvx[$class] .= '</div>';
}

$btn_nvx_string = '';

foreach($btn_nvx as $btn)
	$btn_nvx_string .= $btn;


echo $this->Form->input('pupils._ids', array(
    'class'=>'chzn-select form-control',
    'id'=>'PupilPupil',
    'data-placeholder' => 'Cliquez ici ou sur les boutons de niveaux pour ajouter des élèves.',
    'help' => '<div class="help-block btn-toolbar">'.$btn_nvx_string.'</div>',
    'label' => array(
        'text' => 'Élèves ayant passé l\'évaluation'
        )
    )
);



?>

<div class="form-group">
    <?php echo $this->Form->submit('Créer cette évaluation', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();
