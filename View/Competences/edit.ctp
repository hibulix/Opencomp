<div class="page-title">
    <h2><?php echo __('Modifier une compétence'); ?></h2>
</div>

<?php


echo $this->Form->create('Competence', array(
    'type' => 'post',
    'url' => array(
        'controller' => 'competences',
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
    'label' => array('text' => 'Nom de la compétence'),
));

echo $this->Form->input('parent_id', array(
        'options'=>$cid,
        'class'=>'chzn-select form-control',
        'empty'=>true,
        'data-placeholder'=>'Pas de compétence parent',
        'label' => array(
            'text' => 'Compétence parente'
        ))
);

?>

<div class="form-group">
    <?php echo $this->Form->submit('Enregistrer les modifications', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();
