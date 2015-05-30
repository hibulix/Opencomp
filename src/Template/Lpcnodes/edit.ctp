<div class="page-title">
    <h2><?php echo __('Modifier un noeud du Livret Personnel de Compétences'); ?></h2>
</div>

<?php

echo $this->Form->create($lpcnode, ['align' => [
    'md' => [
        'left' => 2,
        'middle' => 3,
        'right' => 7,
    ],
]]);

echo $this->Form->input('title', array(
    'label' => array('text' => 'Nom du noeud'),
));

echo $this->Form->input('parent_id', array(
        'options'=>$cid,
        'class'=>'chzn-select form-control',
        'label' => array(
            'text' => 'Noeud parent'
        ))
);

?>

<div class="form-group">
    <?php echo $this->Form->submit('Enregistrer les modifications', array(
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();
