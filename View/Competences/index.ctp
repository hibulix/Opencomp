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
	var data = <?php echo $json; ?>;

	function returnContextMenuAdminCompetence(node){
		console.log(node);
		if(node.data.type == "feuille"){
			var idItem = node.id;
			var competence = $('#'+node.parent+'>a').text();
			var idCompetence = $('#'+node.parent).attr('data-id');
		}
		else if(node.data.type == "noeud"){
			var competence = $('#'+node.id+'>a').text();
			var idCompetence = node.id;
		}

		var items = {
			"createNew" : {
				"label" : "créer une compétence enfant dans \""+competence.trim()+"\"",
				"icon" : "fa text-success fa-plus",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'competences/add/'+idCompetence;
				}
			},
			"edit" : {
				"label" : "modifier l'intitulé ou la compétence parente de \""+competence.trim()+"\"",
				"icon" : "fa text-warning fa-pencil",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'competences/edit/'+idCompetence;
				}
			},
			"moveTop" : {
				"label" : "déplacer vers le haut",
				"icon" : "fa text-info fa-arrow-up",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'competences/moveup/'+idCompetence;
				}
			},
			"moveDown" : {
				"label" : "déplacer vers le bas",
				"icon" : "fa text-info fa-arrow-down",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'competences/movedown/'+idCompetence;
				}
			}
		};

		if (node.data.type == "feuille" || role !== 'admin') {
			delete items.createNew;
			delete items.edit;
			delete items.moveTop;
			delete items.moveDown;
		}

		return items;
	}

	$("#competences").jstree({
		'contextmenu' : {
			'items' : returnContextMenuAdminCompetence
		},
		'plugins' : [ 'contextmenu' ],
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

<div id="competences" class="jstree-default" style="margin-top:20px;">

</div>
