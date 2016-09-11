<?php
$this->assign('header', $classroom->title);
$this->assign('description', $this->Html->link($classroom->establishment->name,'/establishments/view/'.$classroom->establishment->id));
?>

<?= $this->cell('Classroom::stats', [$classroom->id]); ?>

<div class="row">
	<div class="col-md-12">
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title"><?= __('Évaluations'); ?> <small><?= $this->Paginator->counter('{{count}} résultat(s) correspondant(s), {{current}} résultat(s) affiché(s), page {{page}} sur {{pages}}') ?></small></h3>
				<div class="box-tools">
					<ul class="pagination pagination-sm no-margin pull-left">
						<?= $this->Paginator->first('<<') ?>
						<?= $this->Paginator->prev('<',['disabledTitle'=>false]) ?>
						<?= $this->Paginator->numbers(['before' => '', 'after' => '']) ?>
						<?= $this->Paginator->next('>',['disabledTitle'=>false]) ?>
						<?= $this->Paginator->last('>>') ?>
					</ul>&nbsp;
					<div class="btn-group">
						<a class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="fa fa-filter"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>&nbsp;&nbsp;
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<?php foreach ($periods as $period): ?>
							<li><?php echo $this->Html->link('<i class="fa fa-reorder"></i> du '.$period->period->begin . " au " . $period->period->end, '/classrooms/tests/'.$classroom->id.'?period_id='.$period->period_id, array('escape' => false)); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php echo $this->Html->link('<i class="fa fa-fw fa-plus"></i> '.__('ajouter une évaluation'), '/evaluations/add/'.$classroom->id, array('class' => 'btn btn-sm btn-success', 'escape' => false)); ?>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body no-padding">

				<?php if (!empty($evaluations)): ?>
					<table class="table table-striped table-condensed">
						<thead>
						<tr>
							<th><?php echo __('#'); ?></th>
							<th colspan="2">Métriques</th>
							<th><?php echo __('Titre de l\'évaluation'); ?></th>
							<th class="actions"><?php echo __('Progression de la saisie'); ?></th>
							<th class="actions"><?php echo __('Actions'); ?></th>
							<th><?php echo __('Accessible à'); ?></th>
						</tr>
						</thead>
						<tbody>
						<?php
						$i = 0;
						foreach ($evaluations as $evaluation): ?>
							<?php
							$total = count($evaluation->competences)*count($evaluation->pupils);
							if($total != 0){
								$progress = (count($evaluation->results)*100)/$total;
							}else{
								$progress = 0;
							}
							?>
							<tr>
								<td><?php echo '#'.$evaluation->id; ?></td>
								<td><?= count($evaluation->pupils) ?> <i class="fa fa-child"></i> <?= $this->Utils->getLevels($evaluation); ?></td>
								<td><?= count($evaluation->competences) ?>  <i class="fa fa-list"></i></td>
								<td><?php echo $this->Html->link($evaluation->title, array('controller' => 'evaluations', 'action' => 'competences', $evaluation->id)); ?></td>
								<td class="actions" style="padding-right:40px">
									<div class="progress-group">
										<span class="progress-text" style="color:transparent;">-</span>
										<span class="progress-number"><b><?= intval($progress) ?></b> %</span>

										<div class="progress xxs" style="margin-top: 1px;">
											<div class="progress-bar progress-bar-striped progress-bar-success" style="width: <?= intval($progress) ?>%"></div>
										</div>
									</div>
								</td>
								<td class="actions">
									<div class="btn-group">
										<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><?php echo $this->Html->link('<i class="fa fa-list"></i> '.__('Associer des competences'), array('controller' => 'evaluations', 'action' => 'competences', $evaluation->id), array('escape' => false)); ?></li>
											<?php if($progress > 0 && count($evaluation->competences) > 0): ?>
												<li><?php echo $this->Html->link('<i class="fa fa-bar-chart-o"></i> '.__('Poursuivre la saisie des résultats'), array('controller' => 'evaluations', 'action' => 'results', $evaluation->id), array('escape' => false)); ?></li>
											<?php elseif($progress == 0 && count($evaluation->competences) > 0): ?>
												<li><?php echo $this->Html->link('<i class="fa fa-bar-chart-o"></i> '.__('Commencer la saisie des résultats'), array('controller' => 'evaluations', 'action' => 'results', $evaluation->id), array('escape' => false)); ?></li>
											<?php endif; ?>
											<li class="divider"></li>
											<li><?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('Modifier'), array('controller' => 'evaluations', 'action' => 'edit', $evaluation->id), array('escape' => false)); ?></li>
											<li><?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Supprimer'), array('controller' => 'evaluations', 'action' => 'delete', $evaluation->id), array('escape' => false), __('Are you sure you want to delete # {0}?', $evaluation->id)); ?></li>

										</ul>
									</div>
								</td>
								<td>
									<?php foreach ($evaluation->users as $user): ?>
										<?= $user->full_name; ?> (<em><?= $user->_joinData->ownership; ?></em>)
									<?php endforeach; ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				<?php else: ?>
					<div class="alert alert-info">
						<i class="fa fa-info-circle fa fa-3x pull-left"></i>
						Actuellement, aucune évaluation n'a été associée à cette classe (<?php echo $scope ?>).<br />Vous pouvez ajouter une évaluation en cliquant sur le bouton vert ci-dessus.
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>


