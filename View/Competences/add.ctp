<div class="page-title">
    <h2><?php echo __('Ajouter une compétence à l\'arbre'); ?></h2>
    
    <?php echo $this->Html->link('<i class="fa fa-check"></i> '.__('J\'ai terminé la saisie'), array('admin'=>false,'action'=>'index'), array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
</div>

<?php

if(isset($idcomp)){
    echo $this->Form->create('Competence', array(
        'url' => array(
            'controller' => 'competences',
            'action' => 'add',
            $idcomp
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
}else{
    echo $this->Form->create('Competence', array(
        'url' => array(
            'controller' => 'competences',
            'action' => 'add'
        ),
        'class' => 'form-horizontal'
    ));
}

echo $this->Form->input('title', array(
    'label' => array('text' => 'Nom de la compétence')
)); 

if(isset($idcomp)) {
	echo $this->Form->input('parent_id', array(
	    'selected'=>$idcomp,
	    'options'=>$cid,
        'class'=>'chzn-select form-control',
        'label' => array(
            'text' => 'Compétence parent'
        ))
	);
}

?>

<div class="form-group">
    <?php echo $this->Form->submit('Ajouter et nouveau', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();
