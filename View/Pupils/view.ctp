<div class="pupils view">
<h2><?php  echo __('Pupil'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($pupil['Pupil']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($pupil['Pupil']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('First Name'); ?></dt>
		<dd>
			<?php echo h($pupil['Pupil']['first_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sex'); ?></dt>
		<dd>
			<?php echo h($pupil['Pupil']['sex']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Birthday'); ?></dt>
		<dd>
			<?php echo h($pupil['Pupil']['birthday']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($pupil['Pupil']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tutor'); ?></dt>
		<dd>
			<?php echo $this->Html->link($pupil['Tutor']['name'], array('controller' => 'tutors', 'action' => 'view', $pupil['Tutor']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Level'); ?></dt>
		<dd>
			<?php echo $this->Html->link($pupil['Level']['title'], array('controller' => 'levels', 'action' => 'view', $pupil['Level']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($pupil['Pupil']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Pupil'), array('action' => 'edit', $pupil['Pupil']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Pupil'), array('action' => 'delete', $pupil['Pupil']['id']), null, __('Are you sure you want to delete # %s?', $pupil['Pupil']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Pupils'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pupil'), array('action' => 'add')); ?> </li>
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
<div class="related">
	<h3><?php echo __('Related Results'); ?></h3>
	<?php if (!empty($pupil['Result'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Evaluation Id'); ?></th>
		<th><?php echo __('Pupil Id'); ?></th>
		<th><?php echo __('Item Id'); ?></th>
		<th><?php echo __('Result'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($pupil['Result'] as $result): ?>
		<tr>
			<td><?php echo $result['id']; ?></td>
			<td><?php echo $result['evaluation_id']; ?></td>
			<td><?php echo $result['pupil_id']; ?></td>
			<td><?php echo $result['item_id']; ?></td>
			<td><?php echo $result['result']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'results', 'action' => 'view', $result['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'results', 'action' => 'edit', $result['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'results', 'action' => 'delete', $result['id']), null, __('Are you sure you want to delete # %s?', $result['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Result'), array('controller' => 'results', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Classrooms'); ?></h3>
	<?php if (!empty($pupil['Classroom'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Year Id'); ?></th>
		<th><?php echo __('Establishment Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($pupil['Classroom'] as $classroom): ?>
		<tr>
			<td><?php echo $classroom['id']; ?></td>
			<td><?php echo $classroom['title']; ?></td>
			<td><?php echo $classroom['user_id']; ?></td>
			<td><?php echo $classroom['year_id']; ?></td>
			<td><?php echo $classroom['establishment_id']; ?></td>
			<td><?php echo $classroom['created']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'classrooms', 'action' => 'view', $classroom['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'classrooms', 'action' => 'edit', $classroom['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'classrooms', 'action' => 'delete', $classroom['id']), null, __('Are you sure you want to delete # %s?', $classroom['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Classroom'), array('controller' => 'classrooms', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
