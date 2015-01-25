<div class="page-title">
    <h2><?php echo __('Modifier un noeud du Livret Personnel de Compétences'); ?></h2>
</div>

<?php


echo $this->Form->create('Lpcnode', array(
    'type' => 'post',
    'url' => array(
        'controller' => 'lpcnodes',
        'action' => 'edit',
        $this->params['pass'][0]
    ),
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
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end(); ?>