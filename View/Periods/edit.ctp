<div class="periods form">
<?php echo $this->Form->create('Period'); ?>
	<fieldset>
		<legend><?php echo __('Edit Period'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('begin', array('dateFormat' => 'DMY'));
		echo $this->Form->input('end', array('dateFormat' => 'DMY'));
		echo $this->Form->input('year_id');
		echo $this->Form->input('establishment_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Period.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Period.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Periods'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Years'), array('controller' => 'years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Year'), array('controller' => 'years', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Establishments'), array('controller' => 'establishments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Establishment'), array('controller' => 'establishments', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Evaluations'), array('controller' => 'evaluations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Evaluation'), array('controller' => 'evaluations', 'action' => 'add')); ?> </li>
	</ul>
</div>
