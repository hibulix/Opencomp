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
    	<div class="well" style="height:157px; padding:10px;">
    		<p class="text-center text-warning"><span class="numbers-kickstart">1 </span><i class="fa fa-5x fa-pencil-square-o"></i></p>
	    	<p><?php echo $this->Html->link('<i class="fa fa-question-circle"></i> Comment conçevoir les évaluations de mes élèves ? <i class="fa fa-external-link"></i>', 'https://opencomp.freshdesk.com/support/solutions/folders/1000218480', array('escape'=>false, 'target'=>'_blank')); ?></p>
    	</div>
    </div>
    <div class="col-md-2">
    	<div class="well" style="height:157px; padding:10px;">
    		<p class="text-center text-primary"><span class="numbers-kickstart">2 </span><i class="fa fa-5x fa-file-text-o"></i></p>
	    	<p><?php echo $this->Html->link('<i class="fa fa-question-circle"></i> Comment saisir les évaluations et définir les items évalués ? <i class="fa fa-external-link"></i>', 'https://opencomp.freshdesk.com/support/solutions/folders/1000218495', array('escape'=>false, 'target'=>'_blank')); ?></p>
    	</div>
    </div>
    <div class="col-md-2">
    	<div class="well" style="height:157px; padding:10px;">
    		<p class="text-center text-success"><span class="numbers-kickstart">3 </span><i class="fa fa-5x fa-check-square-o"></i></p>
	    	<p><?php echo $this->Html->link('<i class="fa fa-question-circle"></i> Comment saisir les résultats d\'une évaluation ? <i class="fa fa-external-link"></i>', 'https://opencomp.freshdesk.com/support/solutions/folders/1000218481', array('escape'=>false, 'target'=>'_blank')); ?></p>
    	</div>
    </div>
    <div class="col-md-2">
    	<div class="well" style="height:157px; padding:10px;">
    		<p class="text-center text-danger"><span class="numbers-kickstart">4 </span><i class="fa fa-5x fa-file-pdf-o"></i></p>
	    	<p><?php echo $this->Html->link('<i class="fa fa-question-circle"></i> Comment générer les bulletins élèves en fin de période ? <i class="fa fa-external-link"></i>', 'https://opencomp.freshdesk.com/support/solutions/folders/1000218496', array('escape'=>false, 'target'=>'_blank')); ?></p>
    	</div>
    </div>
</div>
	
<?php if(!empty($classrooms)):
	foreach($classrooms as $classroom): ?>

<div class="page-title">
	<h3>
    	<?php echo $classroom['Classroom']['title'] ?> à <?php echo $classroom['Establishment']['name'] ?>
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
    				$classroom['Classroom']['id']
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
		    		$classroom['Classroom']['id']
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
		    		$classroom['Classroom']['id']
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
			    	'classroom_id' => $classroom['Classroom']['id']
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
		foreach ($classroom['Evaluation'] as $evaluation): ?>
		
		<?php 
			$total = count($evaluation['Item'])*count($evaluation['Pupil']);
			$results = count($evaluation['Result']);
			if($total != $results){
				$line = '<li style="line-height:23px;">Saisie des résultats incomplète pour <code>'.$evaluation['title'].'</code>';
				$line .= $this->Html->link('<i class="fa fa-magic"></i>Corriger', 
	    		array(
		    		'controller' => 'evaluations', 
		    		'action' => 'manageresults', 
		    		$evaluation['id']
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
		  <p class="lead" style="margin-bottom:0px;"><i class="fa fa-check"></i> Toutes les vérifications automatiques ont réussi ;)</p>
		</div>
		<?php endif; ?>
	</div>
</div>	

<?php endforeach;
endif;
