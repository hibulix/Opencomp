<div class="page-title">
    <h2><?php echo __('Synthèse, votre tableau de bord personnel'); ?></h2>
</div>

<div class="row-fluid">
    <div class="col-md-4">
    	<div class="well" style="height:157px;">
	    	<?php echo $this->Html->image('logo-opencomp.png', array('style' => 'height:100px; float: left;','alt' => 'Logo Opencomp')); ?>
	    	<div style="margin-top:25px; margin-bottom:20px;">Vous trouverez dans cette page l'essentiel pour vous permettre de démarrer rapidement avec Opencomp !</div>
    	</div>
    </div>
    <div class="col-md-2">
    	<div class="well" style="height:157px;">
	    	<?php echo $this->Html->image('logo-opencomp.png', array('style' => 'height:100px; opacity:0.04; margin-left:15px; float: left;','alt' => 'Logo Opencomp')); ?>
    	</div>
    </div>
    <div class="col-md-2">
    	<div class="well" style="height:157px;">
	    	<?php echo $this->Html->image('logo-opencomp.png', array('style' => 'height:100px; opacity:0.06; margin-left:15px; float: left;','alt' => 'Logo Opencomp')); ?>
    	</div>
    </div>
    <div class="col-md-2">
    	<div class="well" style="height:157px;">
	    	<?php echo $this->Html->image('logo-opencomp.png', array('style' => 'height:100px; opacity:0.08; margin-left:15px; float: left;','alt' => 'Logo Opencomp')); ?>
    	</div>
    </div>
    <div class="col-md-2">
    	<div class="well" style="height:157px;">
	    	<?php echo $this->Html->image('logo-opencomp.png', array('style' => 'height:100px; opacity:0.1; margin-left:15px; float: left;','alt' => 'Logo Opencomp')); ?>
    	</div>
    </div>
</div>

<?php if(!empty($classrooms)):
	foreach($classrooms as $classroom): ?>

<div class="page-title">
	<h3>
    	<?php echo $classroom->title ?> à <?php echo $classroom->establishment->name ?>
    </h3>
</div>
<div class="row">
	<div class="col-md-4">
    	<div class="row">
	    	<div class="col-md-6">
	    		<?php echo $this->Html->link('<i class="fa fa-child"></i> '.__('Voir les élèves'),
	    		array(
    				'controller' => 'classrooms',
    				'action' => 'view',
    				$classroom->id
    			),
    			array(
    				'class' => 'btn btn-default btn-large btn-block',
    				'escape' => false
    			)); ?>
    		</div>
	    	<div class="col-md-6">
	    		<?php echo $this->Html->link('<i class="fa fa-file-pdf-o"></i> '.__('Voir les bulletins'),
	    		array(
		    		'controller' => 'classrooms',
		    		'action' => 'viewreports',
		    		$classroom->id
	    		),
	    		array(
		    		'class' => 'btn btn-default btn-large btn-block',
		    		'style'=>'font-weight:normal; margin-bottom:10px;',
		    		'escape' => false
	    		)); ?>
	    	</div>
	    </div>
	    <div class="row">
	    	<div class="col-md-6">
	    		<?php echo $this->Html->link('<i class="fa fa fa-file-text-o"></i> '.__('Voir les évaluations'),
	    		array(
		    		'controller' => 'classrooms',
		    		'action' => 'viewtests',
		    		$classroom->id
	    		),
	    		array(
		    		'class' => 'btn btn-default btn-large btn-block',
		    		'escape' => false
	    		)); ?>
	    	</div>

	    	<div class="col-md-6">
		    	<?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('Nouvelle évaluation'),
		    	array(
			    	'controller' => 'evaluations',
			    	'action' => 'add',
			    	'classroom_id' => $classroom->id
		    	),
		    	array(
			    	'class' => 'btn btn-large btn-block btn-success',
			    	'style'=>'font-weight:normal; margin-bottom:20px;',
			    	'escape' => false
		    	)); ?>
	    	</div>
	    </div>
	</div>

		<?php
		$lines = array();
		foreach ($classroom->evaluations as $evaluation): ?>

		<?php
			$total = count($evaluation->item)*count($evaluation->pupil);
			$results = count($evaluation->result);
			if($total != $results){
				$line = '<li style="line-height:23px;">Saisie des résultats incomplète pour <code>'.$evaluation->title.'</code>';
				$line .= $this->Html->link('<i class="fa fa-magic"></i>Corriger',
	    		array(
		    		'controller' => 'evaluations',
		    		'action' => 'manageresults',
		    		$evaluation->id
	    		),
	    		array(
	    			'style' => 'float:right; ',
	    			'escape' => false
	    		));
				$line .= '</li>';
				$lines[] = $line;
			}
		endforeach;?>
		<?php if(count($lines) > 0): ?>
		<div class="col-md-8">
		<div class="alert-danger alert">
		  <h4 style='margin-bottom:10px;'><i class="fa fa-pushpin"></i><strong> Des éléments nécessitent votre attention !</strong></h4>
		  <ul>
		  	<?php foreach($lines as $line){
			  	echo $line;
		  	}
		  	?>
		  </ul>
		</div>
		<?php else: ?>
		<div class="col-md-8">
		<div class="alert alert-success">
		  <p class="lead" style="margin-bottom:0px;"><i class="fa fa-check"></i> Tous les vérifications automatiques ont réussi ;)</p>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php endforeach;
endif;
