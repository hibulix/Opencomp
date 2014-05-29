<?php
    $id = $data['Competence']['id'];
?>

<a href="#" id="<?php echo $id; ?>" class="jstree-toggle" style="color:grey;"><?php echo($data['Competence']['title']); ?> </a>

<?php 

echo $this->Html->link(
	' <i class="fa fa-plus"></i> '.__('nouvel item'), 
	array(
		'controller' => 'evaluationsItems',
		'action' => 'additem',
		'evaluation_id' => $evaluation_id,
		'competence_id' => $id
	), 
	array(
		'escape' => false,
		'style' => 'color:green;'
	)
);

if(empty($data['ChildCompetence'])){
	if(!empty($data['Item'])){
		echo '<ul>';
		foreach($data['Item'] as $item){			
			echo '<li>';
			if($item['type'] == 1) 
				echo '<span class="label label-danger" style="margin-right:5px;">EN</span>';
			elseif($item['type'] == 2)
				echo '<span class="label label-info" style="margin-right:5px;">ET</span>';
			elseif($item['type'] == 3)
				echo '<span class="label label-success" style="margin-right:5px;">PE</span>';
			foreach($item['Level'] as $level){
				echo '<span class="label label-default" style="margin-right:5px;">'.$level['title'].'</span>';
			}
			echo $this->Html->link($item['title'], '#');
			echo $this->Html->link(
				' <i class="fa fa-check"></i> '.__('ajouter à l\'évaluation'), 
				array(
					'controller' => 'evaluationsItems',
					'action' => 'attachitem',
					'evaluation_id' => $evaluation_id,
					'item_id' => $item['id']
				), 
				array(
					'escape' => false,
					'style' => 'color:steelblue;'
				)
			);
			echo '</li>';
		}	
		echo '</ul>';	
	}	
}
?>
