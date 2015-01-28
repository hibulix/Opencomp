<?php echo $this->element('ClassroomBase'); ?>

<ul class="nav nav-pills">
  <li><?php echo $this->Html->link(__('Élèves'), array('controller' => 'classrooms', 'action' => 'view', $classroom['Classroom']['id'])); ?></li>
  <li class="active"><?php echo $this->Html->link(__('Évaluations'), array('controller' => 'classrooms', 'action' => 'viewtests', $classroom['Classroom']['id'])); ?></li>
  <li><?php echo $this->Html->link(__('Items non évalués'), array('controller' => 'classrooms', 'action' => 'viewunrateditems', $classroom['Classroom']['id'])); ?></li>
  <li><?php echo $this->Html->link(__('Bulletins'), array('controller' => 'classrooms', 'action' => 'viewreports', $classroom['Classroom']['id'])); ?></li>
</ul>

<div class="page-title">
    <h3>
        <?php
            $nbevals = count($classroom['Evaluation']);

            if($nbevals > 1)
                $title = __('évaluations associées à cette classe');
            else
                $title = __('évaluation associée à cette classe');

            if(isset($all))
                $scope = __('toutes les périodes');
            else
                $scope = __('période courante uniquement');
            echo "$nbevals $title ($scope)";
        ?>
    </h3>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('ajouter une évaluation'), '/evaluations/add/classroom_id:'.$classroom['Classroom']['id'], array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
	<?php echo $this->Html->link('<i class="fa fa-binoculars"></i> '.__('items évalués'), '/evaluationsItems/useditems/'.$classroom['Classroom']['id'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
    <div class="btn-group ontitle">
	  <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
	    <i class="fa fa-filter"></i>
	    <span class="caret"></span>
	  </a>
	  <ul class="dropdown-menu">
	    <li><?php echo $this->Html->link('<i class="fa fa-reorder"></i> '.__('afficher toutes les périodes'), '/classrooms/viewtests/'.$classroom['Classroom']['id'].'/periods:all', array('escape' => false)); ?></li>
	    <li><?php echo $this->Html->link('<i class="fa fa-reorder"></i> '.__('afficher la période courante'), '/classrooms/viewtests/'.$classroom['Classroom']['id'], array('escape' => false)); ?></li>
	  </ul>
	</div>
</div>

<?php if (!empty($classroom['Evaluation'])): ?>
<div class="alert alert-info">
	<i class="fa fa-lightbulb-o"></i> &nbsp; Seules les évaluations de la période courante sont listées, pour visualiser la totalité des évaluations, vous pouvez modifier le filtre.
</div>
<table class="table table-striped table-condensed">
<tr>
	<th><?php echo __('Identifiant'); ?></th>
	<th><?php echo __('Titre de l\'évaluation'); ?></th>
	<th><?php echo __('Évalué par'); ?></th>
	<th class="actions"><?php echo __('Progression de la saisie'); ?></th>
	<th class="actions"><?php echo __('Actions'); ?></th>
</tr>
<?php
	$i = 0;
	foreach ($classroom['Evaluation'] as $evaluation): ?>
	<tr>
		<td><?php echo '#'.$evaluation['id']; ?></td>
		<td><?php echo $this->Html->link($evaluation['title'], array('controller' => 'evaluations', 'action' => 'attacheditems', $evaluation['id'])); ?></td>
		<td><?php echo $evaluation['User']['first_name'].' '.$evaluation['User']['name']; ?></td>
		<td class="actions" style="padding-right:40px">
			<?php 
				$total = count($evaluation['Item'])*count($evaluation['Pupil']);
				if($total != 0){
					$progress = (count($evaluation['Result'])*100)/$total; 
				}else{
					$progress = 0; 
				}
			?>
			<div style="height:10px; margin-top:4px; margin-bottom:0px;" class="progress active"><div class="progress-bar" style="font-size: 10px; vertical-align:top; width: <?php echo $progress; ?>%;"><span style="position:relative; top: -5px;"><?php if(intval($progress) > 10) echo intval($progress).'%'; ?></span></div></div>
		</td>
		<td class="actions">
			<div class="btn-group">
	          <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> Actions <span class="caret"></span></button>
	          <ul class="dropdown-menu">
	            <li><li><?php echo $this->Html->link('<i class="fa fa-list"></i> '.__('Associer des items'), array('controller' => 'evaluations', 'action' => 'attacheditems', $evaluation['id']), array('escape' => false)); ?></li></li>
	            <?php if($progress > 0 && count($evaluation['Item']) > 0): ?>
	            <li><li><?php echo $this->Html->link('<i class="fa fa-bar-chart-o"></i> '.__('Poursuivre la saisie des résultats'), array('controller' => 'evaluations', 'action' => 'manageresults', $evaluation['id']), array('escape' => false)); ?></li></li>
	            <?php elseif($progress == 0 && count($evaluation['Item']) > 0): ?>
	            <li><li><?php echo $this->Html->link('<i class="fa fa-bar-chart-o"></i> '.__('Commencer la saisie des résultats'), array('controller' => 'evaluations', 'action' => 'manageresults', $evaluation['id']), array('escape' => false)); ?></li></li>
	            <?php endif; ?> 
	            <li class="divider"></li>
	            <li><?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('Modifier'), array('controller' => 'evaluations', 'action' => 'edit', $evaluation['id']), array('escape' => false)); ?></li>
	            <li><?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Supprimer'), array('controller' => 'evaluations', 'action' => 'delete', $evaluation['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $evaluation['id'])); ?></li>

	          </ul>
	        </div>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle fa fa-3x pull-left"></i>
        Actuellement, aucune évaluation n'a été associée à cette classe (<?php echo $scope ?>).<br />Vous pouvez ajouter une évaluation en cliquant sur le bouton vert ci-dessus.
    </div>
<?php endif; ?>