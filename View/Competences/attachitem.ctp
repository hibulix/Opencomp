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

  Pour ajouter un item, dépliez les branches de l'arbre jusqu'à atteindre l'item souhaité, puis, effectuez un clic droit dessus et sélectionnez "ajouter cet item à l'évaluation".<br />
  Si l'item évalué n'est pas encore présent dans l'arbre dépliez les branches jusqu'à atteindre la compétence souhaitée, puis, effectuez un clic droit dessus et sélectionnez "créer un nouvel item dans xx".
</div>

<div class="well">
	<ul class="list-unstyled">
		<li><i class="fa fa-chevron-right"></i> <strong>Légende :</strong></li>
		<ul class="fa-ul" style="margin-left:35px; margin-top:10px;">
			<li><i class="fa-li fa fa-lg fa-cube text-danger"></i> Signale un item extrait des instructions officielles de l'<em>éducation nationale</em> (programmes 2008, progressions 2012 et EMC 2015).</li>
			<li><i class="fa-li fa fa-lg fa-cube text-info"></i> Signale un item commun à l'ensemble des enseignants de l'<em>établissement</em>.</li>
			<li><i class="fa-li fa fa-lg fa-cube text-success"></i> Signale un item <em>personnel</em> que vous avez ajouté.</li>
		</ul>
	</ul>
</div>

<div class="col-md-3 pull-right">
    <input type="text" id="search" class="form-control" placeholder="🔍 chercher dans le référentiel" />
</div>

<div id="tree_attach_item" class="jstree-default" style="width:1140px; overflow: hidden;">

</div>

<?php
$this->start('script');
?>
<script type='text/javascript'>
	var data = <?php echo $json; ?>;

	function returnContextMenu(node){
		if(node.data.type == "feuille"){
			var idItem = node.data.id;
			var competence = $('#'+node.parent+'>a').text();
			var idCompetence = $('#'+node.parent).attr('data-id');
		}
		else if(node.data.type == "noeud"){
			var competence = $('#'+node.id+'>a').text();
			var idCompetence = node.id;
		}
		var selected = $("#tree_attach_item").jstree("get_selected");
		var arrayLength = selected.length;
		for (var i = 0; i < arrayLength; i++) {
			var re = /item-/gi;
			selected[i] = selected[i].replace(re, "");
		}
		var items = {
			"add-selection" : {
				"label" : "ajouter ma sélection à l'évaluation (<strong>"+arrayLength+" item(s)</strong> sélectionné(s))",
				"icon" : "fa text-info fa-check-square-o",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'evaluationsItems/attachitem/evaluation_id:'+$('#id_evaluation').text()+'/item_id:'+selected.join();
				},
			},
			"createNew" : {
				"label" : "créer un nouvel item dans \""+competence.trim()+"\"",
				"separator_before" : true,
				"icon" : "fa text-success fa-plus",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'evaluationsItems/additem/evaluation_id:'+$('#id_evaluation').text()+'/competence_id:'+idCompetence;
				}
			}
		};
		if (node.data.type == "noeud") {
			delete items.choose;
		}
		return items;
	}
	$("#tree_attach_item").jstree({
		'state' : {
			'key' : 'tree_attach_item' ,
			'events' : 'open_node.jstree close_node.jstree'
		},
		'contextmenu' : {
			'show_at_node' : false,
			'items' : returnContextMenu
		},
		'checkbox' : {
			'three_state' : false
		},
		'conditionalselect' : function (node) {
			return node.data.type == "noeud" ? false : true;
		},
		'plugins' : [ 'state', 'contextmenu', 'search', 'checkbox', 'conditionalselect' ],
		'core' : {
			'check_callback' : true,
			'strings' : {
				'Loading ...' : 'Veuillez patienter ...'
			},
			'data' : data
		}
	});

	var to = false;
	$('#search').keyup(function () {
		if(to) { clearTimeout(to); }
		to = setTimeout(function () {
			var v = $('#search').val();
			$('#tree_attach_item').jstree(true).search(v);
		}, 250);
	});

</script>
<?php
$this->end();
