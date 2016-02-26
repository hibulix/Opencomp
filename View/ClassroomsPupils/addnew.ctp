<div class="page-title">
    <h2><?php echo __('Ajouter un nouvel élève à la classe'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à la classe'), '/classrooms/view/'.$classroom_id, array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
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

echo $this->Form->input('Pupil.first_name', array(
    'label' => array(
        'text' => 'Prénom de l\'élève'
    )
)); 

echo $this->Form->input('Pupil.name', array(
    'label' => array(
        'text' => 'Nom de l\'élève'
    )
)); 

echo $this->Form->input('Pupil.sex', array(
	'type' => 'select',
    'options' => array('M'=>'Masculin','F'=>'Féminin'),
    'label' => array(
        'text' => 'Sexe de l\'élève'
    )
)); 

echo $this->Form->input('Pupil.birthday', array(
        'type' => 'text',
        'readonly' => 'readonly',
        'beforeInput' => '<div class="input-group">',
        'afterInput' => '<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>',
        'class' => 'form-control startdate',
        'label' => array(
            'text' => 'Date de naissance'
        )
)); 

echo $this->Form->hidden('ClassroomsPupil.classroom_id', array('value' => $classroom_id));

echo $this->Form->input('ClassroomsPupil.level_id', array(
	'after' => '<p class="help-block">'.__("Opencomp vous demande de spécifier un niveau pour chaque élève car certaines classes peuvent comporter des élèves de niveaux différents.").'</p>',
    'label' => array(
        'text' => 'Niveau scolaire'
    )
));

?>

<div class="form-group">
    <?php echo $this->Form->submit('Ajouter l\'élève', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();
