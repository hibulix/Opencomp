<div class="page-title">
    <h2><?php echo __('Analyse instantanée des résultats du bulletin'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à la classe'), '/classrooms/viewreports/'.$report['Classroom']['id'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Prénom</th>
			<th>Nom</th>
			<th class="text-center">A</th>
			<th class="text-center">B</th>
			<th class="text-center">C</th>
			<th class="text-center">D</th>
			<th class="text-center">Total</th>
			<th style="width:500px;">Répartition</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($results as $result): ?>
		<?php $total_items = $result['Result']['sum_grade_a'] + $result['Result']['sum_grade_b'] + $result['Result']['sum_grade_c'] + $result['Result']['sum_grade_d']; ?>
		<tr>
			<td><?php echo $result['Pupil']['first_name']; ?></td>
		    <td><?php echo $result['Pupil']['name']; ?></td>
		    <td class="text-center"><?php echo $result['Result']['sum_grade_a']; ?></td>
		    <td class="text-center"><?php echo $result['Result']['sum_grade_b']; ?></td>
		    <td class="text-center"><?php echo $result['Result']['sum_grade_c']; ?></td>
		    <td class="text-center"><?php echo $result['Result']['sum_grade_d']; ?></td>
			<td class="text-center"><?php echo $total_items ?></td>
		    <td>
		    <div class="progress" style="margin-bottom:0px;">
				<div class="info progress-bar progress-bar-success" rel="tooltip" data-placement="bottom" title="<?php echo $this->Utils->getPercentValue($result['Result']['sum_grade_a'], $total_items); ?>% des items acquis <?php echo $result['Result']['sum_grade_a'] ?> A sur <?php echo $total_items ?> items évalués au total" style="width: <?php echo $this->Utils->getPercentValue($result['Result']['sum_grade_a'], $total_items); ?>%;"></div>
				<div class="info progress-bar" rel="tooltip" data-placement="bottom" title="<?php echo $this->Utils->getPercentValue($result['Result']['sum_grade_b'], $total_items); ?>% des items à renforcer <?php echo $result['Result']['sum_grade_b'] ?> B sur <?php echo $total_items ?> items évalués au total" style="width: <?php echo $this->Utils->getPercentValue($result['Result']['sum_grade_b'], $total_items); ?>%;"></div>
				<div class="info progress-bar progress-bar-warning" rel="tooltip" data-placement="bottom" title="<?php echo $this->Utils->getPercentValue($result['Result']['sum_grade_c'], $total_items); ?>% des items en cours d'acquisition <?php echo $result['Result']['sum_grade_c'] ?> C sur <?php echo $total_items ?> items évalués au total" style="width: <?php echo $this->Utils->getPercentValue($result['Result']['sum_grade_c'], $total_items); ?>%;"></div>
				<div class="info progress-bar progress-bar-danger" rel="tooltip" data-placement="bottom" title="<?php echo $this->Utils->getPercentValue($result['Result']['sum_grade_d'], $total_items); ?>% des items non acquis <?php echo $result['Result']['sum_grade_d'] ?> D sur <?php echo $total_items ?> items évalués au total" style="width: <?php echo $this->Utils->getPercentValue($result['Result']['sum_grade_d'], $total_items); ?>%;"></div>
			</div>

		</tr>
	<?php endforeach; ?>
	</tbody>
</table>