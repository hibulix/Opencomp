<span id="period_id" hidden><?php echo $period_id; ?></span>
<span id="classroom_id" hidden><?php echo $classroom_id; ?></span>

<div class="page-title">
    <h2><?php echo __('Associer un item travaillé mais non évalué à une période'); ?></h2>
    <div class="btn-group ontitle">
        <?php echo $this->Html->link('<i class="fa fa-expand"></i> '.__('Déplier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#tree_attach_unrated_item').jstree('open_all','',200);")); ?>
        <?php echo $this->Html->link('<i class="fa fa-compress"></i> '.__('Replier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#tree_attach_unrated_item').jstree('close_all','',200);")); ?>
    </div>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour aux items non évalués'), '/classrooms/viewunrateditems/'.$this->request->data['Classroom']['classroom_id'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<div class="alert alert-info">
  Vous êtes sur le point d'ajouter un item travaillé mais non évalué à une période.<br /><br />

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



<div id="tree_attach_unrated_item" class="jstree-default" style="width:1140px; overflow: hidden;">

</div>

<?php
$this->start('script');
?>
<script type='text/javascript'>
    var data = <?php echo $json; ?>;

    function returnContextMenuUnratedItems(node){
        var competence, idCompetence;
        if(node.data.type == "feuille"){
            var idItem = node.data.id;
            competence = $('#'+node.parent+'>a').text();
            idCompetence = $('#'+node.parent).attr('data-id');
        }
        else if(node.data.type == "noeud"){
            var competence = $('#'+node.id+'>a').text();
            var idCompetence = node.id;
        }

        var items = {
            "choose" : {
                "label" : "choisir cet item",
                "icon" : "fa text-info fa-check",
                "action" : function (obj){
                    window.location.href = $('#base_url').text()+'evaluationsItems/attachunrateditem/period_id:'+$('#period_id').text()+'/item_id:'+idItem+'/classroom_id:'+$('#classroom_id').text();
                }
            },
            "createNew" : {
                "label" : "créer un nouvel item dans \""+competence.trim()+"\"",
                "separator_before" : true,
                "icon" : "fa text-success fa-plus",
                "action" : function (obj){
                    window.location.href = $('#base_url').text()+'evaluationsItems/addunrateditem/period_id:'+$('#period_id').text()+'/competence_id:'+idCompetence+'/classroom_id:'+$('#classroom_id').text();
                }
            }
        };

        if (node.data.type == "noeud") {
            delete items.choose;
        }

        return items;
    }

    $("#tree_attach_unrated_item").jstree({
        'state' : { 'key' : 'tree_attach_unrated_item' },
        'contextmenu' : {
            'items' : returnContextMenuUnratedItems
        },
        'plugins' : [ 'state', 'contextmenu' ],
        'core' : {
            'check_callback' : true,
            'strings' : {
                'Loading ...' : 'Veuillez patienter ...'
            },
            'data' : data
        }
    });

    $("#tree_attach_unrated_item").on("dblclick.jstree-default", function (event) {
        var node = $(event.target).closest("li");
        if($(node[0]).attr("data-type") == "feuille"){
            var idItem = $(node[0]).attr("data-id");
            window.location.href = $('#base_url').text()+'evaluationsItems/attachunrateditem/period_id:'+$('#period_id').text()+'/item_id:'+idItem+'/classroom_id:'+$('#classroom_id').text();
        }else if($(node[0]).attr("data-type") == "noeud"){
            var idCompetence = $(node[0]).attr("data-id");
            window.location.href = $('#base_url').text()+'evaluationsItems/addunrateditem/period_id:'+$('#period_id').text()+'/competence_id:'+idCompetence+'/classroom_id:'+$('#classroom_id').text();
        }
    });

</script>
<?php
$this->end();
