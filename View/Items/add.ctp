<div class="page-title">
    <h2><?php echo __('Ajouter un item'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-check"></i> '.__('J\'ai terminé la saisie'), array('controller'=>'competences','action'=>'index'), array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
</div>

<div class="alert alert-info">
  Vous êtes sur le point de créer un nouvel item.<br />
  Le nouvel item sera inséré dans la compétence <code><?php echo $path; ?></code>.

</div>

<?php 

echo $this->Form->create('Item', array(
    'class' => 'form-horizontal',
    'url' => array(
    	'controller' => 'items',
    	'action' => 'add',
    	$competence_id
    ),
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

echo $this->Form->input('title', array(
	'type' => 'textarea',
    'label' => array(
        'text' => 'Libellé de l\'item'
    )
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
        <?php echo $this->Form->hidden('lpcnode_id'); ?>
        <div id="jumelage_lpc" class="jstree-default" style="margin-top:20px;">

        </div>
    </div>
</div>
<?php

echo $this->Form->hidden('competence_id', array('value' => $competence_id));
echo $this->Form->hidden('type', array('value' => 1));
    
?>

<div class="form-group">
    <?php echo $this->Form->submit('Ajouter cet item', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();

$this->start('script');
?>
<script type='text/javascript'>
    var role = '<?php echo AuthComponent::user('role'); ?>';
    var data = <?php echo $json; ?>;

    $("#jumelage_lpc").jstree({
        'state' : { 'key' : 'jumelage_lpc' },
        'core' : {
            'multiple' : false,
            'check_callback' : true,
            'strings' : {
                'Loading ...' : 'Veuillez patienter ...'
            },
            'data' : data
        }
    });

    $("#jumelage_lpc").on("click.jstree-default", function (event) {
        var selected = $('#jumelage_lpc').jstree(true).get_selected()[0];
        if($('#jumelage_lpc').jstree(true).is_leaf(selected) === false){
            $('#jumelage_lpc').jstree(true).deselect_node(selected);
            if($('#jumelage_lpc').jstree(true).is_open(selected))
                $('#jumelage_lpc').jstree(true).close_node(selected);
            else
                $('#jumelage_lpc').jstree(true).open_node(selected);
        }else{
            $('#ItemLpcnodeId').val(selected);
        }
    });
</script>
<?php
$this->end();
