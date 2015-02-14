<div class="page-title">
    <h2><?php echo __('Modifier une évaluation'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-ban"></i> '.__('annuler modification'), '/evaluations/attacheditems/'.$evaluation_id, array('class' => 'ontitle btn btn-danger', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create('Evaluation', array(
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

echo $this->Form->hidden('classroom_id', array('value' => $classroom_id));

echo $this->Form->input('period_id', array(
    'class'=>'chzn-select form-control',
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


echo $this->Form->input('Pupil', array(
    'class'=>'chzn-select form-control',
    'wrapInput' => 'col col-md-7',
    'data-placeholder' => 'Cliquez ici ou sur les boutons de niveaux pour ajouter des élèves.',
    'afterInput' => '<div class="help-block btn-toolbar">'.$btn_nvx_string.'</div>',
    'label' => array(
        'text' => 'Élèves ayant réalisé l\'évaluation'
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
