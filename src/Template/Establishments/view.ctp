<div class="page-title">
    <h2><?php echo __('Visualiser un établissement'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('modifier'), 'edit/'.$establishment->id, array('class' => 'ontitle btn btn-primary', 'escape' => false)); ?>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('académie de l\'établissement'), array('controller' => 'academies', 'action' => 'view', $establishment->academy->id), array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="well" style="margin-top:30px;">
        	<dl class="dl-horizontal">
        		<dt><?php echo __('Nom de l\'école'); ?></dt>
        		<dd>
        			<?php echo h($establishment->name); ?>
        			&nbsp;
        		</dd>
        		<dt><?php echo __('Adresse'); ?></dt>
        		<dd>
        			<?php echo h($establishment->address); ?>
        			&nbsp;
        		</dd>
        		<dt><?php echo __('Code postal'); ?></dt>
        		<dd>
        			<?php echo h($establishment->postcode); ?>
        			&nbsp;
        		</dd>
        		<dt><?php echo __('Ville'); ?></dt>
        		<dd>
        			<?php echo h($establishment->town); ?>
        			&nbsp;
        		</dd>
        		<dt><?php echo __('Direction'); ?></dt>
        		<dd>
        			<?php echo $this->Html->link('<i class="fa fa-user"></i> '.$establishment->user->full_name, array('controller' => 'users', 'action' => 'view', $establishment->user->id), array('escape' => false)); ?>
        			&nbsp;
        		</dd>
        		<dt><?php echo __('Académie'); ?></dt>
        		<dd>
        			<?php echo $this->Html->link('<i class="fa fa-link"></i> '.$establishment->academy->name, array('controller' => 'academies', 'action' => 'view', $establishment->academy->id), array('escape' => false)); ?>
        			&nbsp;
        		</dd>
        	</dl>
        </div>
    </div>
    <div class="col-md-6">
        <div class="page-title">
            <h3><?php echo __('Périodes de cet établissement'); ?></h3>
            <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('ajouter une période'), '#addPeriod', array('data-toggle' => 'modal', 'class' => 'ontitle btn btn-success', 'escape' => false)); ?>
        </div>
        
        <?php if (!empty($establishment->periods)): ?>
        <p><?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('Modifier la période courante'), '#defaultPeriod', array('data-toggle' => 'modal', 'escape' => false)); ?></p>
		<table class="table table-condensed table-striped">
		<tr>
			<th><?php echo __('Période'); ?></th>
			<th><?php echo __('Année scolaire'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($establishment->periods as $period):
				$startTableLine = ($period->id == $establishment->period_id) ? '<tr class="warning">' : '<tr>';
			echo $startTableLine; ?> 
				<td><?php echo $period->well_named; ?></td>
				<td><?php echo $period->year->title; ?></td>
				<td class="actions">
					<?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('Modifier'), array('controller' => 'periods', 'action' => 'edit', $period->id, 'establishment_id' => $establishment->id), array('escape' => false)); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
		<?php endif; ?>
    </div>
</div>

<div class="page-title">
    <h3><?php echo __('Classes de cet établissement'); ?></h3>
    <?php   echo $this->Html->link('<i class="fa fa-plus"></i> '.__('ajouter une classe'),
            [
                'controller' => 'Classrooms',
                'action' => 'add',
                'establishment_id' => $establishment->id
            ],
            array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
</div>

<?php if (!empty($establishment->classrooms)): ?>
<table class='table table-striped table-condensed'>
<tr>
    <th><?php echo __('Nom de la classe'); ?></th>
    <th><?php echo __('Enseignant titulaire'); ?></th>
    <th><?php echo __('Année scolaire'); ?></th>
    <th class="actions"><?php echo __('Actions'); ?></th>
</tr>
<?php
    $i = 0;
    foreach ($establishment->classrooms as $classroom): ?>
    <tr>
        <td><?php echo $classroom->title; ?></td>
        <td><?php echo $classroom->user->full_name; ?></td>
        <td><?php echo $classroom->year->title; ?></td>
        <td class="actions">
            <?php echo $this->Html->link('<i class="fa fa fa-eye"></i> '.__('Voir'), array('controller' => 'classrooms', 'action' => 'view', $classroom->id), array('escape'=>false)); ?>&nbsp;&nbsp;&nbsp;
            <?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('Modifier'), array('controller' => 'classrooms', 'action' => 'edit', $classroom->id), array('escape'=>false)); ?>&nbsp;&nbsp;&nbsp;
            <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Supprimer'), array('controller' => 'classrooms', 'action' => 'delete', $classroom->id), array('escape'=>false), __('Are you sure you want to delete # {0}?', $classroom['id'])); ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<!-- Modal addPeriod -->
<div class="modal fade" id="addPeriod" tabindex="-1" role="dialog" aria-labelledby="addPeriodLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            echo $this->Form->create($blank_period, [
                'align' => [
                    'md' => [
                        'left' => 3,
                        'middle' => 6,
                        'right' => 3,
                    ],
                ],
                'url' => ['controller' => 'Periods', 'action' => 'add', 'establishment_id' => $establishment->id]
            ]);
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addPeriodLabel"><?php echo __('Ajouter une période à cet établissement'); ?></h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->input('begin', array(
                    'label' => array(
                        'text' => 'Date de début'
                    )
                ));

                echo $this->Form->input('end', array(
                    'prepend' => array('<i class="fa fa-calendar"></i>'),
                    'label' => array(
                        'text' => 'Date de fin'
                    )
                ));

                echo $this->Form->hidden('year_id', array('value' => $current_year));
                echo $this->Form->hidden('establishment_id', array('value' => $establishment->id));

                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <?php echo $this->Form->button('Ajouter', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </div>
        <?php echo $this->Form->end(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal defaultPeriod -->
<div class="modal fade" id="defaultPeriod" tabindex="-1" role="dialog" aria-labelledby="defaultPeriodLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            echo $this->Form->create($establishment, [
                'align' => [
                    'md' => [
                        'left' => 3,
                        'middle' => 6,
                        'right' => 3,
                    ],
                ],
                'url' => ['controller' => 'Establishments', 'action' => 'setDefaultPeriod']
            ]);
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="defaultPeriodLabel"><?php echo __('Modifier la période courante de l\'établissement'); ?></h4>
            </div>
            <div class="modal-body">
                <?php

                foreach($establishment->periods as $period)
                    $tabperiods[$period['id']] = $period->well_named;

                echo $this->Form->input('period_id', array(
                        'options' => $tabperiods,
                        'value' => $establishment->current_period_id,
                        'label' => array(
                            'text' => 'Période courante'
                        )
                    )
                );

                echo $this->Form->hidden('id', array('value' => $establishment->id));

                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <?php echo $this->Form->button('Modifier', array('type'=>'submit','class' => 'btn btn-primary')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->