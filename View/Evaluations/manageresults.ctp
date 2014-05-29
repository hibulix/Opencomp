<?php echo $this->element('EvaluationBase'); ?>

<ul class="nav nav-pills">
  <li><?php echo $this->Html->link(__('1. Définir les items évalués'), array('controller' => 'evaluations', 'action' => 'attacheditems', $evaluation['Evaluation']['id'])); ?></li>
  <li class="active"><?php echo $this->Html->link(__('2. Saisir les résultats'), array('controller' => 'evaluations', 'action' => 'manageresults', $evaluation['Evaluation']['id'])); ?></li>
  <li class="info" rel="tooltip" data-placement="bottom" data-original-title="Bientôt ..."><?php echo $this->Html->link(__('3. Analyser les résultats'), '#'); ?></li>
</ul>

<?php if (!empty($evaluation['Item'])): ?>
	<div class="page-title">
	    <h3><?php echo __('Résultats de cette évaluation'); ?></h3>
        <div class="btn-group ontitle">
	    <?php echo $this->Html->link('<i class="fa fa-keyboard-o"></i> '.__('saisie manuelle'), '/results/selectpupilmanual/evaluation_id:'.$evaluation['Evaluation']['id'], array('class' => 'btn btn-default', 'escape' => false)); ?>
        <?php echo $this->Html->link('<i class="fa fa-magic"></i> '.__('saisie automagique'), '/results/selectpupil/evaluation_id:'.$evaluation['Evaluation']['id'], array('class' => 'btn btn-primary', 'escape' => false)); ?>
        </div>
	</div>
	<table class="table table-stripped table-condensed">
	<tr>
		<th style="width:20%;"><?php echo __('Prénom'); ?></th>
		<th style="width:20%;"><?php echo __('Nom'); ?></th>
		<th style="width:20%;"><?php echo __('Progression de la saisie'); ?></th>
		<th style="width:20%;"><?php echo __('Action'); ?></th>
	</tr>
	<?php
		$total = count($evaluation['Item']);
		foreach ($evaluation['Pupil'] as $pupil): 
		$pupilres = count($pupil['Result']);
		$progress = $pupilres*100/$total; ?>
		<tr>
			<td><?php echo $pupil['first_name']; ?></td>
			<td><?php echo $pupil['name']; ?></td>
			<td style="padding-right:5%;"><div style="height:10px; margin-top:4px; margin-bottom:0px;" class="progress active"><div class="progress-bar" style="font-size: 10px; vertical-align:top; width: <?php echo $progress; ?>%;"><span style="position:relative; top: -5px;"><?php if(intval($progress) > 10) echo intval($progress).'%'; ?></span></div></div></td>
			<td class="actions">
				<?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('Compléter ou modifier la saisie'), array('controller' => 'results', 'action' => 'add', 'pupil_id' => $pupil['id'], 'evaluation_id' => $evaluation['Evaluation']['id'], 'manual' => 'true'), array('escape' => false)); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php else: ?>	
<div class="alert alert-info">
    <i class="fa fa-info-circle"></i> Vous ne pouvez pas saisir les résultats de cette évaluation car vous ne lui avez pas encore associé d'items.<br />
    Commencez par <?php echo $this->Html->link(__('associer des items'), array('controller' => 'evaluations', 'action' => 'attacheditems', $evaluation['Evaluation']['id'])); ?> à cette évaluation.
</div>
<?php endif; ?>