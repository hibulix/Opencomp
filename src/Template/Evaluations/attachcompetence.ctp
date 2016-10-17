<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<style type="text/css">
    li:not(.jstree-leaf) > a > i.jstree-checkbox {
        display:none;
    }
</style>
<?php

$this->assign('header', "Ajouter une compétence à l'évaluation");

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
                Vous êtes sur le point d'ajouter un item évalué à l'évaluation “<strong><?php echo h($evaluation->title) ?></strong>”.<br /><br />

                Pour ajouter un item, dépliez les branches de l'arbre jusqu'à atteindre l'item souhaité, puis, cochez la case.<br />
                Si l'item évalué n'est pas encore présent dans l'arbre dépliez les branches jusqu'à atteindre la compétence souhaitée, puis, effectuez un clic droit et sélectionnez "+ créer un nouvel item".
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

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Sélectionnez une ou plusieurs compétences pour votre évaluation</h3>
    </div>
    <div class="box-body">
        <div id="tree_attach_item"></div>
    </div>
</div>

<?php
$this->start('script');
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

    <script>
        var data = <?= $listjson; ?>;
        var evaluationId = '<?= $evaluation->id ?>';

        function returnContextMenu(node){
            console.log(node);
            if(node.type != 0){
                var competence = $('#'+node.parent+'>a').text();
                var idCompetence = $('#'+node.parent).attr('data-id');
            }
            else if(node.type == 0){
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
                "addselection" : {
                    "label" : "ajouter ma sélection à l'évaluation (<strong>"+arrayLength+" item(s)</strong> sélectionné(s))",
                    "icon" : "fa text-info fa-check-square-o",
                    "separator_after" : true,
                    "action" : function (){
                        $.post( window.location, {
                            competenceIds: selected
                        }).done(function() {
                            window.location.href = $('#base_url').text()+'evaluations/competences/'+evaluationId;
                        });
                    }
                },
                "createNew" : {
                    "label" : "créer une nouvelle compétence dans \""+competence.trim()+"\"",
                    "icon" : "fa text-success fa-plus",
                    "action" : function (){
                        window.location.href = $('#base_url').text()+'evaluations/addcompetence/'+evaluationId+'/'+node.id;
                    }
                }
            };

            if (arrayLength == 0) {
                delete items.addselection;
            }

            return items;
        };

        $('#tree_attach_item').jstree({
            'contextmenu' : {
                'show_at_node' : false,
                'items' : returnContextMenu
            },
            'checkbox' : {
                'three_state' : false,
                'keep_selected_style' : false
            },
            'conditionalselect' : function (node) {
                return node.type != 0;
            },
            'plugins' : ['contextmenu', 'checkbox', 'conditionalselect', 'types'],
            'core' : {
                'check_callback' : true,
                'strings' : {
                    'Loading ...' : 'Veuillez patienter ...'
                },
                'data' : data
            },
            "types" : {
                "0" : {
                    "icon" : "fa fa-cubes"
                },
                "1" : {
                    "icon" : "fa fa-cube text-danger"
                },
                "cycle" : {
                    "icon" : "fa fa-certificate"
                }
            }
        });
    </script>
<?php $this->end();

