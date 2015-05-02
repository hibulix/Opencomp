<div class="page-title">
    <h2><?php  echo __('Importer des élèves'); ?></h2>
</div>

<?php
$to = $this->Html->url(array(
    "controller" => "pupils",
    "action" => "import",
    "classroom_id" => $classroom_id,
    "step" => "muf"
));

echo $this->Form->create('Pupil', array(
    'type' => 'file',
    'classroom_id' => $classroom_id,
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

echo $this->Form->input('Pupil.exportBe1d', array(
    'type' => 'file',
    'data-buttonText' => '&nbsp;Parcourir...',
    'class' => 'filestyle',
    'label' => array(
        'text' => 'Fichier d\'export .csv BE1D'
    )
));

echo $this->Form->submit('Importer le fichier', array(
    'div' => 'col col-md-9 col-md-offset-2',
    'class' => 'btn btn-primary'
));