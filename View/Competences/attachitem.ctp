<span id="id_evaluation" hidden><?php echo $evaluation_id; ?></span>

<div class="page-title">
    <h2><?php echo __('Associer un item à une évaluation'); ?></h2>
		<div class="btn-group ontitle">
			<?php echo $this->Html->link('<i class="fa fa-expand"></i> '.__('Déplier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#tree_attach_item').jstree('open_all','',200);")); ?>
			<?php echo $this->Html->link('<i class="fa fa-compress"></i> '.__('Replier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#tree_attach_item').jstree('close_all','',200);")); ?>
		</div>
		<?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à l\'évaluation'), '/evaluations/attacheditems/'.$eval['Evaluation']['id'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<div class="alert alert-info">
  Vous êtes sur le point d'ajouter un item évalué à l'évaluation “<strong><?php echo h($eval['Evaluation']['title']) ?></strong>”.<br /><br />

  Pour ajouter un item, dépliez les branches de l'arbre jusqu'à atteindre l'item souhaité, puis, double-cliquez dessus.<br />
  Si l'item évalué n'est pas encore présent dans l'arbre dépliez les branches jusqu'à atteindre la compétence souhaitée, puis, double-cliquez dessus pour créer un nouvel item.
</div>

<div class="well">
	<ul class="list-unstyled">
		<li><i class="fa fa-chevron-right"></i> <strong>Légende :</strong></li>
		<ul class="fa-ul" style="margin-left:35px; margin-top:10px;">
			<li><i class="fa-li fa fa-lg fa-cube text-danger"></i> Signale un item extrait des instructions officielles de l'<em>éducation nationale</em> (programmes 2008).</li>
			<li><i class="fa-li fa fa-lg fa-cube text-info"></i> Signale un item commun à l'ensemble des enseignants de l'<em>établissement</em>.</li>
			<li><i class="fa-li fa fa-lg fa-cube text-success"></i> Signale un item <em>personnel</em> que vous avez ajouté.</li>
		</ul>
	</ul>
</div>

<div id="tree_attach_item" class="jstree-default" style="width:1140px; overflow: hidden;">

</div>