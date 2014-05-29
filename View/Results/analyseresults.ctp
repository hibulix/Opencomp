<div class="page-title">
    <h2><?php echo __('Analyse instantanée des résultats du bulletin'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à la classe'), '/classrooms/viewreports/'.$report['Classroom']['id'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<table class="table table-striped">
	<thead>
	  <tr>
	    <th>Prénom</th>
	    <th>Nom</th>
	    <th>Total items</th>
	    <th>Total A</th>
	    <th>Total B</th>
	    <th>Total C</th>
	    <th>Total D</th>
	    <th style="width:500px;">Répartition</th>
	  </tr>
	</thead>
	<tbody>
	<?php foreach($results as $id_pupils => $result): ?>
		<tr>
			<td><?php echo $result['name']; ?></td>
		    <td><?php echo $result['first_name']; ?></td>
		    <td><?php echo $result['totalresults']; ?></td>
		    <td><?php echo $result['numberA']." (".number_format($result['percentA'],0)." %)"; ?></td>
		    <td><?php echo $result['numberB']." (".number_format($result['percentB'],0)." %)"; ?></td>
		    <td><?php echo $result['numberC']." (".number_format($result['percentC'],0)." %)"; ?></td>
		    <td><?php echo $result['numberD']." (".number_format($result['percentD'],0)." %)"; ?></td>
		    <td>
		    <div class="progress" style="margin-bottom:0px;">
                        <div class="info progress-bar progress-bar-success" rel="tooltip" data-placement="bottom" title="<?php echo number_format($result['percentA'],1) ?>% des items acquis <?php echo $result['numberA'] ?> A sur <?php echo $result['totalresults'] ?> items évalués au total" style="width: <?php echo $result['percentA'] ?>%;"></div>
                        <div class="info progress-bar" rel="tooltip" data-placement="bottom" title="<?php echo number_format($result['percentB'],1) ?>% des items à renforcer <?php echo $result['numberB'] ?> B sur <?php echo $result['totalresults'] ?> items évalués au total" style="width: <?php echo $result['percentB'] ?>%;"></div>
                        <div class="info progress-bar progress-bar-warning" rel="tooltip" data-placement="bottom" title="<?php echo number_format($result['percentC'],1) ?>% des items en cours d'acquisition <?php echo $result['numberC'] ?> C sur <?php echo $result['totalresults'] ?> items évalués au total" style="width: <?php echo $result['percentC'] ?>%;"></div>
                        <div class="info progress-bar progress-bar-danger" rel="tooltip" data-placement="bottom" title="<?php echo number_format($result['percentD'],1) ?>% des items non acquis <?php echo $result['numberD'] ?> D sur <?php echo $result['totalresults'] ?> items évalués au total" style="width: <?php echo $result['percentD'] ?>%;"></div>
                    </div>
		    </th>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>