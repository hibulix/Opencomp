<?php echo $this->element('ClassroomBase'); ?>

<ul class="nav nav-pills">
  <li><?php echo $this->Html->link(__('Élèves'), array('controller' => 'classrooms', 'action' => 'view', $classroom['Classroom']['id'])); ?></li>
  <li><?php echo $this->Html->link(__('Évaluations'), array('controller' => 'classrooms', 'action' => 'viewtests', $classroom['Classroom']['id'])); ?></li>
  <li><?php echo $this->Html->link(__('Items non évalués'), array('controller' => 'classrooms', 'action' => 'viewunrateditems', $classroom['Classroom']['id'])); ?></li>
  <li class="active"><?php echo $this->Html->link(__('Bulletins'), array('controller' => 'classrooms', 'action' => 'viewreports', $classroom['Classroom']['id'])); ?></li>
</ul>

<div class="page-title">
    <h3><?php echo __('Génération de').' "'.$report['Report']['title'].'" '.__('en cours ...'); ?></h3>
</div>

<script type="text/javascript">
	var tab = $.parseJSON(<?php echo $pupils; ?>);
	
	function ajaxcall(i){
		$("#progress").removeClass('bar-success bar-danger');
    	if (i < tab.pupils.length) {
    		$("#progress").css({"width":tab.pourcent[i]+"%"});
    		$("#progress").html(tab.pourcent[i]+"%");
    		$.ajax({ 
			    type: "GET",
			    url: "<?php echo $this->Html->url(array('controller' => 'results', 'action' => 'bul'), true); ?>/output_type:pdf/output_engine:dompdf/pupil_id:"+tab.pupils[i]+"/report_id:"+tab.report_id, 
			    error: function() { 
			      $("#progress").addClass('bar-danger');
			      $("#progress").css('width','100%');
			      $("#progress").html("<p style='color:white;'>Ooops ! Une erreur est survenue lors de la génération :'(</p>");
			    }, 
			    success: function() { 
			      ajaxcall(i + 1);	
			    } 
			}); 
		}else{
			$("#progress-bar").addClass('progress-striped active');
			$.ajax({ 
			    type: "POST",
			    url: "<?php echo $this->Html->url(array('controller' => 'results', 'action' => 'concatenateReports'), true); ?>",
			    data: tab,
			    complete: function(){
			    	$("#progress-bar").removeClass('progress-striped active');
			    	$("#progress").addClass('bar-success');
                    $("#progress").html("<a style='color:white;' href='javascript:location.reload(true);'>Besoin de télécharger à nouveau ? Cliquez-ici ...</a>");

                    window.location.href = "../download/filename:"+tab.classroom_id+"_"+tab.period_id.replace(",","")+".pdf";
			    } 
			});
		}    	
	}
	
	$(document).ready(function(){
	   ajaxcall(0);
	});
	    	
</script>

<h3 style="text-align:center; margin-bottom:20px; font-family: 'Titillium Web', sans-serif;">Laissez la magie <i class="fa fa-magic"></i> opérer et allez boire un café <i class="fa fa-coffee"></i></h3>

<div class="row">
	<div class="span12">
		<div id="progress-bar" class="progress">
		  <div id="progress" class="progress-bar" style="width: 0%;"></div>
		</div>
	</div>
</div>