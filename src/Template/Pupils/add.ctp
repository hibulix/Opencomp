<div class="page-title">
    <h2><?php echo __('Ajouter un élève à la classe '.$classroom->title); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à la classe'), '/classrooms/view/'.$classroom->id, array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create($pupil, [
    'align' => [
        'md' => [
            'left' => 2,
            'middle' => 3,
            'right' => 7,
        ]]
]);

echo $this->Form->input('id');

echo $this->Form->input('first_name', array(
    'label' => array(
        'text' => 'Prénom'
    )
));

echo $this->Form->input('name', array(
    'label' => array(
        'text' => 'Nom'
    )
));

?> <div class="form-group required"> <?php

echo $this->Form->label('sex', 'Sexe');
?> <div class="col-md-3"> <?php
echo $this->Form->radio('sex',
    ['M'=>'Masculin', 'F'=>'Féminin']
);
?> </div></div> <?php

echo $this->Form->input('birthday', array(
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
    <?php echo $this->Form->submit('Ajouter cet élève', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();