<div class="users index">
	<div class="page-title">
        <h2><?php echo __('Utilisateurs'); ?></h2>
        <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('Ajouter un utilisateur'), ['action'=>'add'], array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
    </div>
	<table class="table table-striped">
	<tr>
			<th><?php echo $this->Paginator->sort('id', $this->Utils->sortingSign('id', $this->Paginator->sortKey(), $this->Paginator->sortDir()).'#', array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('username', $this->Utils->sortingSign('username', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Nom d\'utilisateur'), array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('first_name', $this->Utils->sortingSign('first_name', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Prénom'), array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('name', $this->Utils->sortingSign('name', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Nom'), array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('email', $this->Utils->sortingSign('email', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Email'), array('escape' => false)); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user->id); ?>&nbsp;</td>
		<td><?php echo h($user->username); ?>&nbsp;</td>
		<td><?php echo h($user->first_name); ?>&nbsp;</td>
		<td><?php echo h($user->name); ?>&nbsp;</td>
		<td><?php echo h($user->email); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link('<button class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> '.__('Modifier').'</button>', array('action' => 'edit', $user->id), array('escape' => false)); ?>
			<?= $this->Form->postLink('<button class="btn btn-danger btn-xs"><i class="fa fa fa-trash"></i></button>', ['action' => 'delete', $user->id], ['escape' => false, 'confirm' => __('Êtes vous sûr(e) de vouloir supprimer l\'utilisateur {0} {1} ?', $user->first_name, $user->name)]); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>

	<?php echo $this->Paginator->numbers(); ?>
</div>
