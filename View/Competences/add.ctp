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
	    'class'=>'chzn-select',
	    'selected'=>$idcomp,
	    'options'=>$cid,
	    'data-placeholder'=>'Sélectionnez une compétence ...',
	    'style'=>'width : 300px;',
	    'label' => array(
	        'text' => 'Compétence parente',
	        'class' => 'control-label'
	        )
	    )
	);
}

?>

<div class="form-actions">
     <?php echo $this->Form->button('Ajouter et nouveau', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
</div>

<?php echo $this->Form->end(); ?>