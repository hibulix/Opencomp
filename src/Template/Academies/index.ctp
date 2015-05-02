<div class="academies index">
    <div class="page-title">
        <h2><?php echo __('Académies'); ?></h2>
        <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('Ajouter une académie'), array('action'=>'add'), array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
    </div>

    <div class="row">
        <div class="col-md-6">

        	<table class="table table-striped table-condensed">
            	<tr>
            			<th><?php echo $this->Paginator->sort('name', $this->Utils->sortingSign('name', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Nom de l\'académie'), array('escape' => false)); ?></th>
            			<th><?php echo $this->Paginator->sort('type', $this->Utils->sortingSign('type', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Type d\'académie'), array('escape' => false)); ?></th>
            			<th class="actions"><?php echo __('Actions'); ?></th>
            	</tr>
        	<?php
        	foreach ($academies as $academy): ?>
            	<tr>
            		<td><?php echo h($academy->name); ?></td>
            		<td><?php if ($academy->type == 0) {echo 'Académie';} else {echo 'Sous rectorat';} ?></td>
            		<td class="actions">
            		    <?php echo $this->Html->link('<button class="btn btn-default btn-xs"><i class="fa fa fa-eye"></i> '.__('Voir').'</button>', array('admin'=>false,'action' => 'view', $academy->id), array('escape' => false)); ?>
            		</td>
            	</tr>
        	<?php endforeach; ?>
        	</table>

            <?php echo $this->Paginator->numbers(); ?>

        </div>
    </div>

</div>
