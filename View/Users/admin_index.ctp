<div class="users index">
	<div class="page-title">
        <h2><?php echo __('Utilisateurs'); ?></h2>
        <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('Ajouter un utilisateur'), 'add', array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
    </div>
	<table class="table table-striped">
	<tr>
			<th><?php echo $this->Paginator->sort('id', $this->Utils->sorting_sign('id', $this->Paginator->sortKey(), $this->Paginator->sortDir()).'#', array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('username', $this->Utils->sorting_sign('username', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Nom d\'utilisateur'), array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('first_name', $this->Utils->sorting_sign('first_name', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Prénom'), array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('name', $this->Utils->sorting_sign('name', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Nom'), array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('email', $this->Utils->sorting_sign('email', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Email'), array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('role', $this->Utils->sorting_sign('role', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Rôle'), array('escape' => false)); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['first_name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['role']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link('<button class="btn btn-mini"><i class="fa fa fa-eye"></i> '.__('Voir').'</button>', array('action' => 'view', $user['User']['id']), array('escape' => false)); ?>
			<?php echo $this->Html->link('<button class="btn btn-mini"><i class="fa fa-pencil"></i> '.__('Modifier').'</button>', array('action' => 'edit', $user['User']['id']), array('escape' => false)); ?>
			<?php echo $this->Form->postLink('<button class="btn btn-danger btn-mini"><i class="fa fa-trash-o"></i> '.__('Supprimer').'</button>', array('action' => 'delete', $user['User']['id']), array('escape' => false), __('Êtes vous sûr de vouloir supprimer # %s?', $user['User']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	
	<?php echo $this->element('pagination'); ?>
</div>
