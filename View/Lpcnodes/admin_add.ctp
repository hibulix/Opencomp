<div class="page-title">
    <h2><?php echo __('Ajouter un noeud au Livret Personnel de Compétences'); ?></h2>
    
    <?php echo $this->Html->link('<i class="fa fa-check"></i> '.__('J\'ai terminé la saisie'), array('admin'=>false,'action'=>'index'), array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
</div>

<?php
if(isset($idnode)){
	echo $this->Form->create('Lpcnode', array(
		'url' => array(
	    	'controller' => 'lpcnodes',
	    	'action' => 'add',
	    	$idnode
	    ),
		'class' => 'form-horizontal'
	));
}else{
	echo $this->Form->create('Lpcnode', array(
		'url' => array(
	    	'controller' => 'lpcnodes',
	    	'action' => 'add'
	    ),
		'class' => 'form-horizontal'
	));
}


echo $this->Form->input('title', array(
    'label' => array('text' => 'Nom du noeud'), 'class' => 'input-block-level'
)); 

if(isset($idnode)) {
	echo $this->Form->input('parent_id', array(
	    'class'=>'chzn-select',
	    'selected'=>$idnode,
	    'options'=>$cid,
	    'data-placeholder'=>'Sélectionnez une noeud ...',
	    'style'=>'width : 300px;',
	    'label' => array(
	        'text' => 'Noeud parent',
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