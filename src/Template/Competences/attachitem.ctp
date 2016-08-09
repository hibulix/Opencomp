<?php
$returnLink = $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à l\'évaluation'), '/evaluations/items/'.$eval->id, array('class' => 'pull-right btn btn-default', 'escape' => false));
$this->assign('header', 'Associer un item à une évaluation'.$returnLink);
?>

<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<i class="fa fa-info"></i>
				<h3 class="box-title">Aide</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				Vous êtes sur le point d'ajouter un item évalué à l'évaluation “<strong><?php echo h($eval->title) ?></strong>”.<br /><br />

				Pour ajouter un item, dépliez les branches de l'arbre jusqu'à atteindre l'item souhaité, puis, double-cliquez dessus.<br />
				Si l'item évalué n'est pas encore présent dans l'arbre dépliez les branches jusqu'à atteindre la compétence souhaitée, puis, double-cliquez dessus pour créer un nouvel item.
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-6 col-xs-12">
		<div class="box box-default">
			<div class="box-header with-border">
				<i class="fa fa-map"></i>
				<h3 class="box-title">Légende</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<ul class="fa-ul" style="margin-left:35px; margin-top:10px;">
					<li><i class="fa-li fa fa-lg fa-cube text-danger"></i> Signale un item extrait des instructions officielles de l'<em>éducation nationale</em> (programmes 2008).</li>
					<li><i class="fa-li fa fa-lg fa-cube text-info"></i> Signale un item commun à l'ensemble des enseignants de l'<em>établissement</em>.</li>
					<li><i class="fa-li fa fa-lg fa-cube text-success"></i> Signale un item <em>personnel</em> que vous avez ajouté.</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-6 col-xs-12">
		<div class="box box-warning">
			<div class="box-header with-border">
				<i class="fa fa-check-square-o"></i>
				<h3 class="box-title">Choisissez un ou plusieurs items</h3>
				<div class="box-tools pull-right">
					<div class="btn-group">
						<?php echo $this->Html->link('<i class="fa fa-expand"></i> '.__('Déplier l\'arbre'), '#', array('class' => 'btn btn-sm btn-default', 'escape' => false, 'onclick' => "$('#tree_attach_item').jstree('open_all','',200);")); ?>
						<?php echo $this->Html->link('<i class="fa fa-compress"></i> '.__('Replier l\'arbre'), '#', array('class' => 'btn btn-sm btn-default', 'escape' => false, 'onclick' => "$('#tree_attach_item').jstree('close_all','',200);")); ?>
					</div>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div id="tree_attach_item" class="jstree-default" style="width:1140px; overflow: hidden;">

				</div>
			</div>
		</div>
	</div>
</div>

<span id="id_evaluation" hidden><?php echo $eval->id; ?></span>


<?php
$this->start('script');
echo $this->Html->script(array(
    '../components/jstree/dist/jstree.min',
));
echo $this->Html->css(array(
    '../components/jstree/dist/themes/default/style.min',
    'opencomp.app'
));
?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.1/jstree.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.1/themes/default-dark/style.min.css">
<style type="text/css">
	[data-type="noeud"] > a > i.jstree-checkbox{
		display:none;
	}
</style>
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
					window.location.href = $('#base_url').text()+'EvaluationsCompetences/attachitem?evaluation_id='+$('#id_evaluation').text()+'&item_id='+selected.join();
				},
			},
			"createNew" : {
				"label" : "créer un nouvel item dans \""+competence.trim()+"\"",
				"separator_before" : true,
				"icon" : "fa text-success fa-plus",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'EvaluationsCompetences/additem?evaluation_id='+$('#id_evaluation').text()+'&competence_id='+idCompetence;
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
			'key' : 'tree_attach_item_evaluation_<?= $eval->id ?>' ,
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
			return node.data.type != "noeud";
		},
		'plugins' : [ 'state', 'contextmenu', 'checkbox', 'conditionalselect' ],
		'core' : {
			'check_callback' : true,
			'strings' : {
				'Loading ...' : 'Veuillez patienter ...'
			},
			'data' : data
		}
	});

</script>
<?php
$this->end();
