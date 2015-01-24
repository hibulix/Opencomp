<div class="page-title">
    <h2><?php echo __('Instructions officielles').' <small>Programmes 2008 & 2012</small>'; ?></h2>
    <div class="btn-group ontitle">
        <?php echo $this->Html->link('<i class="fa fa-expand"></i> '.__('Déplier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#competences').jstree('open_all','',200);")); ?>
        <?php echo $this->Html->link('<i class="fa fa-compress"></i> '.__('Replier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#competences').jstree('close_all','',200);")); ?>
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
		' <i class="fa fa-plus"></i> '.__('créer une nouvelle compétence à la racine de l\'arbre'),
		array(
            'admin'=> true,
			'controller' => 'competences',
			'action' => 'add'
		),
		array(
			'escape' => false,
			'style' => 'color:green;'
		)
	);
}

$this->start('script');
?>
<script type='text/javascript'>
	var role = '<?php echo AuthComponent::user('role'); ?>';
</script>
<?php
$this->end();

?>

<div id="competences" class="jstree-default" style="margin-top:20px;">

</div>
