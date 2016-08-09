<div class="page-title">
    <h2><?php echo __('Modifier un item'); ?></h2>
</div>

<?php


echo $this->Form->create('Item', array(
    'type' => 'post',
    'inputDefaults' => array(
        'div' => 'form-group',
        'label' => array(
            'class' => 'col col-md-2 control-label'
        ),
        'wrapInput' => 'col col-md-3',
        'class' => 'form-control'
    ),
    'class' => 'form-horizontal'
));

echo $this->Form->input('id');

echo $this->Form->input('title', array(
    'label' => array('text' => 'Nom de l\'item'),
));

echo $this->Form->input('Level', array(
        'class'=>'chzn-select form-control',
        'data-placeholder' => 'Sélectionnez un/des niveau(x) ...',
        'style'=>'width : 220px;',
        'label' => array(
            'text' => 'Niveau de l\'item'
        )
    )
);

?>

<div class="form-group">
    <label class="col col-md-2 control-label">Jumelage LPC</label>
    <div class="col col-md-10">
        <button style="margin-top: 7px;" class="btn btn-default btn-xs" id="no-lpc"><i class="fa fa-remove"></i> Supprimer l'association</button>
        <?php echo $this->Form->hidden('lpcnode_id',['value'=>$this->request->data['Item']['lpcnode_id']]); ?>
        <div id="lpcnode_id" class="jstree-default" style="margin-top:20px;">

        </div>
    </div>
</div>

<div class="form-group">
    <label class="col col-md-2 control-label">Compétence associée</label>
    <div class="col col-md-10">
        <?php echo $this->Form->hidden('competence_id'); ?>
        <div id="competence_id" class="jstree-default" style="margin-top:20px;">

        </div>
    </div>
</div>

<div class="form-group">
    <?php echo $this->Form->submit('Enregistrer les modifications', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();

$this->start('script');
?>
<script type='text/javascript'>
    var role = '<?php /** @noinspection PhpUndefinedClassInspection */echo AuthComponent::user('role'); ?>';
    var lpcnode_id = '<?php echo $this->request->data['Item']['lpcnode_id']; ?>';
    var competence_id = '<?php echo $this->request->data['Item']['competence_id']; ?>';
    var lpcnode_id_data = <?php echo $json; ?>;
    var competence_id_data = <?php echo $competence_id; ?>;
    
    var lpctree = lpctree;
    var competencetree = $("#competence_id");

    $('#no-lpc').click(function(e){
        e.preventDefault();
        $('#ItemLpcnodeId').val('');
        lpctree.jstree(true).deselect_all();
        lpctree.jstree(true).close_all();
    });

    competencetree.jstree({
        'core' : {
            'multiple' : false,
            'check_callback' : true,
            'strings' : {
                'Loading ...' : 'Veuillez patienter ...'
            },
            'data' : competence_id_data
        }
    });

    competencetree.bind("ready.jstree", function () {
        if(competence_id !== '')
            lpctree.jstree(true).select_node(competence_id);
    });

    competencetree.on("click.jstree-default", function (event) {
        var selected = competencetree.jstree(true).get_selected()[0];
        $('#ItemCompetenceId').val(selected);
    });

    lpctree.jstree({
        'core' : {
            'multiple' : false,
            'check_callback' : true,
            'strings' : {
                'Loading ...' : 'Veuillez patienter ...'
            },
            'data' : lpcnode_id_data
        }
    });

    lpctree.bind("ready.jstree", function () {
        if(lpcnode_id !== '')
            lpctree.jstree(true).select_node(lpcnode_id);
    });

    lpctree.on("click.jstree-default", function (event) {
        var selected = lpctree.jstree(true).get_selected()[0];
        if(lpctree.jstree(true).is_leaf(selected) === false){
            lpctree.jstree(true).deselect_node(selected);
            if(lpctree.jstree(true).is_open(selected))
                lpctree.jstree(true).close_node(selected);
            else
                lpctree.jstree(true).open_node(selected);
        }else{
            $('#ItemLpcnodeId').val(selected);
        }
    });
</script>
<?php
$this->end();
