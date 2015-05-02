<div class="page-title">
    <h2><?php echo __('Ajouter un élève existant à la classe'); ?></h2>
</div>

<?php 

echo $this->Form->create('ClassroomsPupil', array('class' => 'form-horizontal'));

?>

<div class="alert alert-info">
	<i class="fa fa-info-circle"></i> Opencomp étant un logiciel multi-académies et multi-établissements, commencez par rechercher si l'élève que vous souhaitez ajouter n'existe pas déjà.
</div>

<?php

echo $this->Form->input('Pupil.id', array(
	'type' => 'select',
	'class'=>'chzn-select',
    'options' => $existing_pupils,
    'label' => array(
        'text' => 'Élève',
        'class' => 'control-label'
    )
)); 

?>

<div class="form-actions">
     <?php echo $this->Form->button('Vérifier et ajouter à cette classe', array('type' => 'submit', 'class' => 'btn btn-success')); ?> 
     <?php echo $this->Html->link('<button class="btn">'.__('L\'élève que je souhaite ajouter n\'est pas listé').'</button>', array('action' => 'addnew', 'classroom_id'=>$classroom_id), array('escape' => false)); ?>       
</div>

<?php echo $this->Form->end();
