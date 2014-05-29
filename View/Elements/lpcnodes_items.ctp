<?php
    $id = $data['Lpcnode']['id'];
?>

<a href="#" id="<?php echo $id; ?>" class="jstree-toggle" style="color:grey;"><?php echo($data['Lpcnode']['title']); ?> </a>

<?php if(AuthComponent::user('role') === 'admin'){
	echo $this->Html->link(
		'<i class="fa fa-arrow-up"></i>', 
		array(
            'admin' => true,
			'controller' => 'lpcnodes', 
			'action' => 'moveup',
			$id
		), 
		array(
			'escape' => false,
			'style' => 'color:grey;'
		)
	);
	echo $this->Html->link(
		' <i class="fa fa-arrow-down"></i>', 
		array(
            'admin' => true,
			'controller' => 'lpcnodes', 
			'action' => 'movedown',
			$id
		), 
		array(
			'escape' => false,
			'style' => 'color:grey;'
		)
	);
}
?>

<?php if(AuthComponent::user('role') === 'admin'){
	echo '<ul><li>';
	echo $this->Html->link(
		' <i class="fa fa-plus"></i> '.__('créer une nouvelle compétence dans ').$data['Lpcnode']['title'], 
		array(
            'admin' => true,
			'controller' => 'lpcnodes', 
			'action' => 'add',
			$id
		), 
		array(
			'escape' => false,
			'style' => 'color:green;'
		)
	);
	echo '</li></ul>';
} ?>