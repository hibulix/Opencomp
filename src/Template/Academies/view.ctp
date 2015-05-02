<div class="academies view">
    <div class="page-title">
        <h2><?php echo __('Visualiser une académie'); ?></h2>
        <?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('modifier'), '/academies/edit/'.$academy['Academy']['id'], array('class' => 'ontitle btn btn-primary', 'escape' => false)); ?>
        <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('lister les académies'), '/admin/academies/index', array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
    </div>
    
	<dl class="dl-horizontal">
		<dt><?php echo __('Nom de l\'académie'); ?></dt>
		<dd>
			<?php echo h($academy['Academy']['name']); ?>
		</dd>
		<dt><?php echo __('Type d\'académie'); ?></dt>
		<dd>
			<?php if ($academy['Academy']['type'] == 0) {echo 'Académie';} else {echo 'Sous rectorat';} ?>
		</dd>
	</dl>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="page-title">
            <h3><?php echo __('Gestionnaire(s) de cette académie'); ?></h3>
        </div>    
        
        <?php if (!empty($academy['User'])): ?>
        	<table class="table table-striped table-condensed">
        	<tr>
        		<th><?php echo __('Identifiant'); ?></th>
        		<th><?php echo __('Prénom'); ?></th>
        		<th><?php echo __('Nom'); ?></th>
        		<th class="actions"><?php echo __('Action'); ?></th>
        	</tr>
        	<?php
        		$i = 0;
        		foreach ($academy['User'] as $user): ?>
        		<tr>
        			<td><?php echo $user['username']; ?></td>
        			<td><?php echo $user['first_name']; ?></td>
        			<td><?php echo $user['name']; ?></td>
        			<td class="actions">
        				<?php echo $this->Html->link('<i class="fa fa fa-eye"></i> '.__('Voir'), array('controller' => 'users', 'action' => 'view', $user['id']), array('escape' => false)); ?>
        			</td>
        		</tr>
        	<?php endforeach; ?>
        	</table>
        <?php endif; ?> 
        
        <div class="alert alert-info">
            Vous pouvez associer un utilisateur existant à cette académie en la <?php echo $this->Html->link(__('modifiant'), 'edit/'.$academy['Academy']['id']); ?>.
        </div>   
    </div>
    <div class="col-md-6">
        <div class="page-title">
            <h3><?php echo __('Établissement(s) de cette académie'); ?></h3>
            <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('ajouter un établissement'), '/establishments/add/academy_id:'.$academy['Academy']['id'], array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
        </div>
        
        <?php if (!empty($academy['Establishment'])): ?>
        	<table class="table table-striped table-condensed">
        	<tr>
        		<th><?php echo __('Nom de l\'établissement'); ?></th>
        		<th><?php echo __('Code postal'); ?></th>
        		<th><?php echo __('Ville'); ?></th>
        		<th class="actions"><?php echo __('Actions'); ?></th>
        	</tr>
        	<?php
        		$i = 0;
        		foreach ($academy['Establishment'] as $establishment): ?>
        		<tr>
        			<td><?php echo $establishment['name']; ?></td>
        			<td><?php echo $establishment['postcode']; ?></td>
        			<td><?php echo $establishment['town']; ?></td>
        			<td class="actions">
        			     <?php echo $this->Html->link('<i class="fa fa fa-eye"></i> '.__('Voir'), array('controller' => 'establishments', 'action' => 'view', 'admin'=>false, $establishment['id']), array('escape' => false)); ?> &nbsp;
        			     <?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('Éditer'), array('controller' => 'establishments', 'action' => 'edit', 'admin'=>false, $establishment['id']), array('escape' => false)); ?>
        			</td>
        		</tr>
        	<?php endforeach; ?>
        	</table>
        <?php endif; ?>
    </div>
  </div>