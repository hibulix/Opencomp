<div class="page-title">
    <h2><?php echo __('Livret Personnel de Compétences').' <small>Basé sur le socle commun de connaissances et de compétences</small>'; ?></h2>
    <div class="btn-group ontitle">
        <?php echo $this->Html->link('<i class="fa fa-expand"></i> '.__('Déplier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#demo1').jstree('open_all', -1, true);")); ?>
        <?php echo $this->Html->link('<i class="fa fa-compress"></i> '.__('Replier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#demo1').jstree('close_all', -1, true);")); ?>
    </div>
</div>

<?php if(AuthComponent::user('role') !== 'admin'){ ?>
	<div class="alert alert-info">
	<i class="fa fa-info-circle fa fa-3x pull-left"></i> 
	  Dans Opencomp, les référentiels sont utilisés de façon à hiérarchiser les items lors de l'impression de documents papier de synthèse (Bulletin, LPC).<br />
	  Seul l'administrateur de l'application a la possibilité de modifier les référentiels. Vous pouvez néanmoins consulter l'arborescence de ce référentiel.
	</div>
<?php }else{
	echo $this->Html->link(
		' <i class="fa fa-plus"></i> '.__('créer un nouveau noeud à la racine de l\'arbre'), 
		array(
            'admin'=>true,
			'controller' => 'lpcnodes', 
			'action' => 'add'
		), 
		array(
			'escape' => false,
			'style' => 'color:green;'
		)
	);
}
?>

<div id="demo1" class="jstree-default" style="margin-top:20px;">
<?php echo $this->Tree->generate($stuff, array('element' => 'lpcnodes_items'));  ?>
</div>