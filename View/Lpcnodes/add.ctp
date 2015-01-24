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
	echo $this->Form->create('Lpcnode', array(
		'url' => array(
	    	'controller' => 'lpcnodes',
	    	'action' => 'add'
	    ),
		'class' => 'form-horizontal'
	));
}

echo $this->Form->input('title', array(
    'label' => array('text' => 'Nom du noeud'),
)); 

if(isset($idnode)) {
	echo $this->Form->input('parent_id', array(
	    'selected'=>$idnode,
	    'options'=>$cid,
		'class'=>'chzn-select form-control',
		'label' => array(
			'text' => 'Noeud parent'
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

<?php echo $this->Form->end(); ?>