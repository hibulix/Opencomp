<div class="pupils index">
    <div class="page-title">
        <h2><?php echo __('Élèves'); ?></h2>
        <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('Ajouter un élève'), 'add', array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
    </div>
	
	<?php
	if($this->Paginator->sortKey()){
	   echo'<div class="alert alert-info fade in">';
          echo'<a class="close" data-dismiss="alert" href="#">&times;</a>';
          echo 'Un tri '.$this->Paginator->sortDir().' est actuellement appliqué sur la colonne '.$this->Paginator->sortKey();
       echo'</div>';
	}
	?>
	
	<table class="table table-striped">
	<tr>
			<th><?php echo $this->Paginator->sort('id', $this->Utils->sorting_sign('id', $this->Paginator->sortKey(), $this->Paginator->sortDir()).'#', array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('name', $this->Utils->sorting_sign('name', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Prénom'), array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('first_name', $this->Utils->sorting_sign('first_name', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Nom'), array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('sex', $this->Utils->sorting_sign('sex', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Sexe'), array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('birthday', $this->Utils->sorting_sign('birthday', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Date de naissance'), array('escape' => false)); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($pupils as $pupil): ?>
	<tr>
		<td><?php echo h($pupil['Pupil']['id']); ?>&nbsp;</td>
		<td><?php echo h($pupil['Pupil']['name']); ?>&nbsp;</td>
		<td><?php echo h($pupil['Pupil']['first_name']); ?>&nbsp;</td>
		<td><?php echo h($pupil['Pupil']['sex']); ?>&nbsp;</td>
		<td><?php echo h($pupil['Pupil']['birthday']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link('<button class="btn btn-mini"><i class="fa fa fa-eye"></i> '.__('Voir').'</button>', array('action' => 'view', $pupil['Pupil']['id']), array('escape' => false)); ?>
			<?php echo $this->Html->link('<button class="btn btn-mini"><i class="fa fa-pencil"></i> '.__('Modifier').'</button>', array('action' => 'edit', $pupil['Pupil']['id']), array('escape' => false)); ?>
			<?php echo $this->Form->postLink('<button class="btn btn-danger btn-mini"><i class="fa fa-trash-o"></i> '.__('Supprimer').'</button>', array('action' => 'delete', $pupil['Pupil']['id']), array('escape' => false), __('Êtes vous sûr de vouloir supprimer # %s?', $pupil['Pupil']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
<?php echo $this->element('pagination'); ?>
</div>

