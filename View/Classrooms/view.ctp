<?php echo $this->element('ClassroomBase'); ?>

<ul class="nav nav-pills">
  <li class="active">
    <?php echo $this->Html->link(__('Élèves'), array('controller' => 'classrooms', 'action' => 'view', $classroom['Classroom']['id'])); ?>
  </li>
  <li><?php echo $this->Html->link(__('Évaluations'), array('controller' => 'classrooms', 'action' => 'viewtests', $classroom['Classroom']['id'])); ?></li>
  <li><?php echo $this->Html->link(__('Items non évalués'), array('controller' => 'classrooms', 'action' => 'viewunrateditems', $classroom['Classroom']['id'])); ?></li>
  <li><?php echo $this->Html->link(__('Bulletins'), array('controller' => 'classrooms', 'action' => 'viewreports', $classroom['Classroom']['id'])); ?></li>
</ul>

<div class="page-title">
    <h3><?php echo count($ClassroomsPupil).' '.__('élève(s) associé(s) à cette classe'); ?></h3>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('ajouter un élève'), '/classroomspupils/addnew/classroom_id:'.$classroom['Classroom']['id'], array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
    <div class="btn-group ontitle">
        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-arrow-up"></i> Exporter
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <?php echo $this->Html->link('<i class="fa fa-file-excel-o"></i> '.__('vers Excel pour publipostage'), array('controller' => 'ClassroomsPupils', 'action' => 'excelExport', 'classroom_id' => $classroom['Classroom']['id']),array('escape' => false)); ?>
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
                <?php echo $this->Html->link('<i class="fa fa-file-text-o"></i> '.__('depuis un export .csv BE1D'), array('controller' => 'pupils', 'action' => 'import', 'classroom_id' => $classroom['Classroom']['id']),array('escape' => false)); ?>
            </li>
        </ul>
    </div>
</div>
<?php if (!empty($ClassroomsPupil)): ?>
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
	foreach ($ClassroomsPupil as $pupil): ?>
	<tr>
		<td><?php echo $pupil['Pupil']['first_name']; ?></td>
		<td><?php echo $pupil['Pupil']['name']; ?></td>
		<td><?php if($pupil['Pupil']['sex'] == 'M') echo 'Masculin'; else echo 'Féminin'; ?></td>
		<td><?php echo $this->Time->format("d/m/Y",$pupil['Pupil']['birthday']); ?></td>
		<td><?php echo $pupil['Level']['title']; ?></td>
		<td class="actions">
		<?php 
			echo $this->Html->link(
				'<i class="fa fa-pencil"></i> Modifier', 
				array(
					'controller' => 'classroomsPupils', 
					'action' => 'edit', 
					'classroom_id' => $classroom['Classroom']['id'], 
					$pupil['Pupil']['id']
				), 
				array('escape' => false)
			); 
		?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php 
			echo $this->Html->link(
				'<i class="fa fa-trash-o"></i> Supprimer', 
				array(
					'controller' => 'classroomsPupils', 
					'action' => 'unlink', 
					'classroom_id' => $classroom['Classroom']['id'], 
					$pupil['Pupil']['id']
				), 
				array('escape' => false),
				__('Êtes vous réellement sûr(e) de vouloir supprimer %s de cette classe ?', $pupil['Pupil']['first_name'].' '.$pupil['Pupil']['name'])
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
<?php endif; ?>