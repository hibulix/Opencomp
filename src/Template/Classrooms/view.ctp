<?php echo $this->element('ClassroomBase'); ?>

<ul class="nav nav-pills">
  <li class="active">
    <?php echo $this->Html->link(__('Élèves'), array('controller' => 'classrooms', 'action' => 'view', $classroom->id)); ?>
  </li>
  <li><?php echo $this->Html->link(__('Évaluations'), array('controller' => 'classrooms', 'action' => 'viewtests', $classroom->id)); ?></li>
  <li><?php echo $this->Html->link(__('Items non évalués'), array('controller' => 'classrooms', 'action' => 'viewunrateditems', $classroom->id)); ?></li>
  <li><?php echo $this->Html->link(__('Bulletins'), array('controller' => 'classrooms', 'action' => 'viewreports', $classroom->id)); ?></li>
</ul>

<div class="page-title">
    <h3><?php echo count($classroomsPupils->toArray()).' '.__('élève(s) associé(s) à cette classe'); ?></h3>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('ajouter un élève'), ['controller' => 'pupils', 'action' => 'add', 'classroom_id' => $classroom->id], array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
    <div class="btn-group ontitle">
        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-arrow-up"></i> Exporter
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <?php echo $this->Html->link('<i class="fa fa-table"></i> '.__('au format OASIS OpenDocument Spreadsheet'), array('controller' => 'ClassroomsPupils', 'action' => 'opendocumentExport', 'classroom_id' => $classroom->id),array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="btn-group ontitle">
        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-arrow-down"></i> Importer
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <?php echo $this->Html->link('<i class="fa fa-file-text-o"></i> '.__('depuis un export .csv BE1D'), array('controller' => 'pupils', 'action' => 'import', 'classroom_id' => $classroom->id),array('escape' => false)); ?>
            </li>
        </ul>
    </div>
</div>
<?php if (count($classroomsPupils->toArray())): ?>
<table class="table table-striped table-condensed">
<tr>
	<th><?php echo __('Prénom'); ?></th>
	<th><?php echo __('Nom'); ?></th>
	<th><?php echo __('Sexe'); ?></th>
	<th><?php echo __('Date de naissance'); ?></th>
	<th><?php echo __('Niveau scolaire'); ?></th>
	<th class="actions"><?php echo __('Actions'); ?></th>
</tr>
<?php
	$i = 0;
	foreach ($classroomsPupils as $pupil): ?>
	<tr>
		<td><?php echo $pupil['Pupils']['first_name']; ?></td>
		<td><?php echo $pupil['Pupils']['name']; ?></td>
		<td><?php if($pupil['Pupils']['sex'] == 'M') echo '<i class="text-info fa fa-mars fa-fw"></i> Masculin'; else echo '<i class="text-danger fa fa-venus fa-fw"></i> Féminin'; ?></td>
		<td><?php echo $this->Time->format($pupil['Pupils']['birthday'],"dd/MM/YYYY"); ?></td>
		<td><?php echo $pupil['Levels']['title']; ?></td>
		<td class="actions">
		<?php
			echo $this->Html->link(
				'<i class="fa fa-pencil"></i> Modifier', 
				array(
					'controller' => 'classroomsPupils', 
					'action' => 'edit',
					$pupil['id']
				), 
				array('escape' => false)
			); 
		?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php 
			echo $this->Form->postLink(
				'<i class="fa fa-trash-o"></i> Supprimer', 
				array(
					'controller' => 'classroomsPupils', 
					'action' => 'unlink',
					$pupil['id']
				),
				array(
                    'escape' => false,
                    'confirm' => __('Êtes vous réellement sûr(e) de vouloir supprimer {0} de cette classe ?', $pupil['Pupils']['first_name'].' '.$pupil['Pupils']['name'])
                )
			); 
		 ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<div class="alert alert-info">
	<i class="fa fa-info-circle"></i> Cette classe ne comporte encore aucun élève associé.<br />Vous pouvez ajouter des élèves manuellement (bouton vert à droite) ou les importer depuis une classe de l'année précédente.
</div>
<?php endif;
