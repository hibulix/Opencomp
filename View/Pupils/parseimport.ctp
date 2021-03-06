<div class="page-title">
    <h2><i class="fa fa-arrows-h"></i> <?php  echo __('Faire correspondre les colonnes'); ?></h2>
</div>

<?php
echo $this->Form->create('Pupil', array(
    'action' => 'previewimport/classroom_id:'.$classroom_id,
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
echo $this->Form->input('first_name', array(
    'type' => 'select',
    'options' => $preview[0],
    'label' => array(
        'text' => 'Prénom'
    )
));
echo $this->Form->input('name', array(
    'type' => 'select',
    'options' => $preview[0],
    'label' => array(
        'text' => 'Nom'
    )
));
echo $this->Form->input('birthday', array(
    'type' => 'select',
    'options' => $preview[0],
    'label' => array(
        'text' => 'Date de naissance'
    )
));
echo $this->Form->input('level', array(
    'type' => 'select',
    'options' => $preview[0],
    'label' => array(
        'text' => 'Niveau'
    )
));
echo $this->Form->input('sex', array(
    'type' => 'select',
    'options' => $preview[0],
    'label' => array(
        'text' => 'Sexe'
    )
));

echo $this->Form->submit('Prévisualiser l\'import', array(
    'div' => 'col col-md-9 col-md-offset-2',
    'class' => 'btn btn-primary'
));
