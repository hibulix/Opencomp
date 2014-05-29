<div class="page-title">
    <h2><?php echo __('Associer un item à une évaluation'); ?></h2>
		<div class="btn-group ontitle">
			<?php echo $this->Html->link('<i class="fa fa-expand"></i> '.__('Déplier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#demo1').jstree('open_all', -1, true);")); ?>
			<?php echo $this->Html->link('<i class="fa fa-compress"></i> '.__('Replier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#demo1').jstree('close_all', -1, true);")); ?>
		</div>
		<?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à l\'évaluation'), '/evaluations/attacheditems/'.$eval['Evaluation']['id'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<div class="alert alert-info">
  Vous êtes sur le point d'ajouter un item évalué à l'évaluation “<strong><?php echo h($eval['Evaluation']['title']) ?></strong>”.<br /><br />

  Pour ajouter un item, dépliez les branches de l'arbre jusqu'à atteindre l'item souhaité et cliquez sur <span style="color:blue;"><i class="fa fa-check"></i> ajouter à l'évaluation.</span><br />
  Si l'item que vous avez évalué n'est pas encore présent dans l'arbre dépliez les branches jusqu'à atteindre la compétence souhaitée et cliquez sur <span style="color:green;"><i class="fa fa-plus"></i> nouvel item</span>.
</div>

<div class="well">
	<ul class="list-unstyled">
		<li><i class="fa fa-chevron-right"></i> <strong>Légende :</strong></li>
		<ul class="unstyled" style="margin-left:35px; margin-top:10px;">
			<li><span class="label label-danger">EN</span> Signale un item extrait des instructions officielles de l'<em>éducation nationale</em> (programmes 2008).</li>
			<li><span class="label label-info">ET</span> Signale un item commun à l'ensemble des enseignants de l'<em>établissement</em>.</li>
			<li><span class="label label-success">PE</span> Signale un item <em>personnel</em> que vous avez ajouté.</li>
		</ul>
	</ul>
</div>

<div id="demo1" class="jstree-default">
<?php echo $this->Tree->generate($stuff, array('element' => 'competences_attachitem'));  ?>
</div>