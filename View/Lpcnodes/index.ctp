<div class="page-title">
    <h2><?php echo __('Livret Personnel de Compétences').' <small>Basé sur le socle commun de connaissances et de compétences</small>'; ?></h2>
    <div class="btn-group ontitle">
        <?php echo $this->Html->link('<i class="fa fa-expand"></i> '.__('Déplier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#lpc_nodes').jstree('open_all','',200);")); ?>
        <?php echo $this->Html->link('<i class="fa fa-compress"></i> '.__('Replier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#lpc_nodes').jstree('close_all','',200);")); ?>
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
			'controller' => 'lpcnodes', 
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
	var data = <?php echo $json; ?>;

	function returnContextMenuAdminLpcNodes(node){

		var competence = $('#'+node.id+'>a').text();
		var idCompetence = node.id;

		var items = {
			"createNew" : {
				"label" : "créer un noeud enfant dans \""+competence.trim()+"\"",
				"icon" : "fa text-success fa-plus",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'lpcnodes/add/'+idCompetence;
				}
			},
			"edit" : {
				"label" : "modifier l'intitulé ou le noeud parent de \""+competence.trim()+"\"",
				"icon" : "fa text-warning fa-pencil",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'lpcnodes/edit/'+idCompetence;
				}
			},
			"moveTop" : {
				"label" : "déplacer vers le haut",
				"icon" : "fa text-info fa-arrow-up",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'lpcnodes/moveup/'+idCompetence;
				}
			},
			"moveDown" : {
				"label" : "déplacer vers le bas",
				"icon" : "fa text-info fa-arrow-down",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'lpcnodes/movedown/'+idCompetence;
				}
			}
		};

		if (role !== 'admin') {
			delete items.createNew;
			delete items.moveTop;
			delete items.moveDown;
		}

		return items;
	}

	$("#lpc_nodes").jstree({
		'state' : { 'key' : 'lpc_nodes' },
		'contextmenu' : {
			'items' : returnContextMenuAdminLpcNodes
		},
		'plugins' : [ 'state', 'contextmenu' ],
		'core' : {
			'strings' : {
				'Loading ...' : 'Veuillez patienter ...'
			},
			'data' : data
		}
	});
</script>
<?php
$this->end();
?>

<div id="lpc_nodes" class="jstree-default" style="margin-top:20px;">

</div>