<div class="pupils form">
<?php echo $this->Form->create('Pupil'); ?>
	<fieldset>
		<legend><?php echo __('Add Pupil'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('first_name');
		echo $this->Form->input('sex');
		echo $this->Form->input('birthday');
		echo $this->Form->input('state');
		echo $this->Form->input('tutor_id');
		echo $this->Form->input('level_id');
		echo $this->Form->input('ClassroomsPupil');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Pupils'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Tutors'), array('controller' => 'tutors', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tutor'), array('controller' => 'tutors', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Levels'), array('controller' => 'levels', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Level'), array('controller' => 'levels', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Results'), array('controller' => 'results', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Result'), array('controller' => 'results', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Classrooms'), array('controller' => 'classrooms', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Classroom'), array('controller' => 'classrooms', 'action' => 'add')); ?> </li>
	</ul>
</div>
