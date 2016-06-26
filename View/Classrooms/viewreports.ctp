<?php echo $this->element('ClassroomBase'); ?>

<ul class="nav nav-pills">
  <li><?php echo $this->Html->link(__('Élèves'), array('controller' => 'classrooms', 'action' => 'view', $classroom['Classroom']['id'])); ?></li>
  <li><?php echo $this->Html->link(__('Évaluations'), array('controller' => 'classrooms', 'action' => 'viewtests', $classroom['Classroom']['id'])); ?></li>
  <li><?php echo $this->Html->link(__('Items non évalués'), array('controller' => 'classrooms', 'action' => 'viewunrateditems', $classroom['Classroom']['id'])); ?></li>
  <li class="active"><?php echo $this->Html->link(__('Bulletins'), array('controller' => 'classrooms', 'action' => 'viewreports', $classroom['Classroom']['id'])); ?></li>
	<li><?php echo $this->Html->link(__('LPC'), array('controller' => 'classrooms', 'action' => 'viewlpc', $classroom['Classroom']['id'])); ?></li>
</ul>

<div class="page-title">
    <h3><?php echo __('Bulletins de la classe'); ?></h3>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('créer un bulletin'), array('controller'=>'reports','action'=>'add','classroom_id'=>$classroom['Classroom']['id']), array(
    'class' => 'ontitle btn btn-success', 
    'data-toggle' => 'modal',
    'escape' => false
    )); ?>
</div>


<?php if (!empty($classroom['Report'])): ?>
<table class="table table-striped table-condensed">
<tr>
	<th><?php echo __('Titre'); ?></th>
	<th><?php echo __('Période(s) associée(s)'); ?></th>
	<th style="width:500px;" class="actions"><?php echo __('Actions'); ?></th>
</tr>
<?php
	$i = 0;
	foreach ($classroom['Report'] as $report):?>
	
	<tr>
		<td><?php echo h($report['title']); ?></td>
		<td>
		<?php 
		foreach($report['period_id'] as $period)
		echo h($periods[$period])."<br />"; 			
		?>		
		</td>		
		<td class="actions">
			<?php echo $this->Html->link('<i class="fa fa-magic"></i> '.__('Générer'), array('controller' => 'reports', 'action' => 'requestGeneration', $report['id']), array('escape' => false)); ?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $this->Html->link('<i class="fa fa-bar-chart-o"></i> '.__('Analyser les résultats'), array('controller' => 'results', 'action' => 'analyseresults', $report['id']), array('escape' => false)); ?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('Modifier'), array('controller' => 'reports', 'action' => 'edit', $report['id']), array('escape' => false)); ?>
			&nbsp;&nbsp;&nbsp;&nbsp;
            <?php
            echo $this->Form->postLink(
                '<i class="fa fa-trash-o"></i> Supprimer',
                array(
                    'controller' => 'reports',
                    'action' => 'delete',
                    $report['id']
                ),
                array('escape' => false),
                __('Êtes vous réellement sûr(e) de vouloir supprimer %s ?', $report['title'])
            );
            ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php endif;
