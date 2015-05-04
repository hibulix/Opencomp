<div class="page-title">
    <h2><?php  echo __('Importer des élèves'); ?></h2>
</div>

<div class="alert alert-info">
    Assurez vous d'avoir un fichier .csv provenant bien de BE1D.</br>
    Pour plus d'informations sur les imports .csv, consultez la base de connaissances à l'adresse <a target="_blank" href="http://kb.opencomp.fr/index.php?sid=117968">http://kb.opencomp.fr/index.php?sid=117968</a>
</div>

<?php
$to = $this->Url->build(array(
    "controller" => "pupils",
    "action" => "import",
    "classroom_id" => $classroom->id,
    "step" => "muf"
));

echo $this->Form->create(null, array(
    'type' => 'file',
    'classroom_id' => $classroom->id,
    'align' => [
        'md' => [
            'left' => 2,
            'middle' => 5,
            'right' => 5,
        ]
    ]
));

echo $this->Form->input('exportBe1d', array(
    'type' => 'file',
    'data-buttonText' => '&nbsp;Parcourir...',
    'class' => 'filestyle',
    'label' => array(
        'text' => 'Fichier d\'export .csv BE1D'
    )
)); ?>

<div class="form-group">
    <?php echo $this->Form->submit('Importer le fichier', array(
    'div' => 'col col-md-9 col-md-offset-2',
    'class' => 'btn btn-primary'
)); ?>
</div>

<?php echo $this->Form->end();