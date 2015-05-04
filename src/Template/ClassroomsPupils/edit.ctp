<div class="page-title">
    <h2><?php echo __('Modifier un élève de la classe '.$classroomPupil->classroom->title); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à la classe'), '/classrooms/view/'.$classroomPupil->classroom->id, array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create($classroomPupil, [
    'align' => [
        'md' => [
            'left' => 2,
            'middle' => 3,
            'right' => 7,
        ]]
]);

echo $this->Form->input('id');

echo $this->Form->input('pupil.first_name', array(
    'label' => array(
        'text' => 'Prénom'
    )
));

echo $this->Form->input('pupil.name', array(
    'label' => array(
        'text' => 'Nom'
    )
));

?> <div class="form-group required"> <?php

    echo $this->Form->label('pupil.sex', 'Sexe');
    ?> <div class="col-md-3"> <?php
        echo $this->Form->radio('sex',
            ['M' => 'Masculin', 'F' => 'Féminin'],
            ['value' => $classroomPupil->pupil->sex]
        );
        ?> </div></div> <?php

echo $this->Form->input('pupil.birthday', array(
    'minYear' => '1990',
    'label' => array(
        'text' => 'Date de naissance'
    )
));

echo $this->Form->input('level_id', array(
    'label' => array(
        'text' => 'Niveau scolaire'
    )
));

?>

<div class="form-group">
    <?php echo $this->Form->submit('Modifier cet élève', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();