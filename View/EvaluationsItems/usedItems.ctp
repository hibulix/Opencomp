<?php echo $this->element('ClassroomBase'); ?>

<ul class="nav nav-pills">
    <li><?php echo $this->Html->link(__('Élèves'), array('controller' => 'classrooms', 'action' => 'view', $classroom['Classroom']['id'])); ?></li>
    <li class="active"><?php echo $this->Html->link(__('Évaluations'), array('controller' => 'classrooms', 'action' => 'viewtests', $classroom['Classroom']['id'])); ?></li>
    <li><?php echo $this->Html->link(__('Items non évalués'), array('controller' => 'classrooms', 'action' => 'viewunrateditems', $classroom['Classroom']['id'])); ?></li>
    <li><?php echo $this->Html->link(__('Bulletins'), array('controller' => 'classrooms', 'action' => 'viewreports', $classroom['Classroom']['id'])); ?></li>
</ul>

<div class="page-title">
    <h3>
        Items évalués
    </h3>
    <?php echo $this->Html->link('<i class="fa fa-times"></i>', array('controller' => 'classrooms', 'action' => 'viewtests', $classroom['Classroom']['id']), array('class' => 'btn btn-default ontitle', 'escape' => false)); ?>
    <div class="btn-group ontitle">
        <?php echo $this->Html->link('<i class="fa fa-expand"></i> '.__('Déplier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#used_items').jstree('open_all','',200);")); ?>
        <?php echo $this->Html->link('<i class="fa fa-compress"></i> '.__('Replier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#used_items').jstree('close_all','',200);")); ?>
    </div>
</div>

<div id="used_items" class="jstree-default" style="margin-top:20px;">

</div>

<?php
$this->start('script');
?>
    <script type='text/javascript'>
        var role = '<?php echo AuthComponent::user('role'); ?>';
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

            var items = {
                "choose" : {
                    "label" : "modifier cet item",
                    "icon" : "fa text-info fa-pencil text-warning",
                    "action" : function (obj){
                        window.location.href = $('#base_url').text()+'items/edit/'+idItem+'/<?php echo $classroom['Classroom']['id'];?>';
                    },
                }
            };

            if ((node.data.type == "noeud" || $("#"+node.id+" i").hasClass("text-danger")) && role !== 'admin') {

                delete items.choose;
            }

            return items;
        }

        $("#used_items").jstree({
            'state' : { 'key' : 'used_items' },
            'contextmenu' : {
                //'show_at_node' : false,
                'items' : returnContextMenu
            },
            'plugins' : [ 'state','contextmenu' ],
            'core' : {
                'multiple' : false,
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
