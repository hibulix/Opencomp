<div class="page-title">
    <h2><?php echo __('Visualiser une classe'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('modifier'), '/classrooms/edit/'.$classroom->id, array('class' => 'ontitle btn btn-primary', 'escape' => false)); ?>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('établissement de la classe'), '/establishments/view/'.$classroom->establishment->id, array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="well">
        	<dl class="dl-horizontal">
        		<dt><?php echo __('Nom de la classe'); ?></dt>
        		<dd>
        			<?php echo h($classroom->title); ?>
        			&nbsp;
        		</dd>
        		<dt><?php echo __('Enseignant titulaire'); ?></dt>
        		<dd>
        			<i class="fa fa-user"></i> <?php echo $classroom->user->full_name ?>
        			&nbsp;
        		</dd>
        		<dt><?php echo __('Établissement'); ?></dt>
        		<dd>
        			<?php echo $this->Html->link('<i class="fa fa-home"></i> '.$classroom->establishment->name, array('controller' => 'establishments', 'action' => 'view', $classroom->establishment->id), array('escape' => false)); ?>
        			&nbsp;
        		</dd>
        		<dt><?php echo __('Année scolaire'); ?></dt>
        		<dd>
        			<?php echo h($classroom->year->title); ?>
        			&nbsp;
        		</dd>
           	</dl>
        </div>
    </div>
    <div class="col-md-6">
        <div class="page-title">
            <h3><?php echo __('Intervenants de cette classe'); ?></h3>
        </div>

        <?php if (count($classroom->users)): ?>
		<table class="table table-striped table-condensed">
		<tr>
			<th><?php echo __('Identifiant'); ?></th>
			<th><?php echo __('Prénom'); ?></th>
			<th><?php echo __('Nom'); ?></th>
		</tr>
		<?php
			foreach ($classroom->users as $user):
			if($user instanceof App\Model\Entity\User): ?>
				<tr>
					<td><?php echo $user->username; ?></td>
					<td><?php echo $user->first_name; ?></td>
					<td><?php echo $user->name; ?></td>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>
		</table>
		<?php else: ?>
		<div class="alert alert-info">
	    	<i class="fa fa-info-circle"></i> Vous pouvez associer un utilisateur existant à cette classe en la <a href="/Opencomp/classrooms/edit/<?php echo $classroom['Classroom']['id']; ?>">modifiant</a>.
	    </div>
		<?php endif; ?>

    </div>
</div>