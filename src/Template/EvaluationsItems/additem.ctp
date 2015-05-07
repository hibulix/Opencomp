<div class="page-title">
    <h2><?php echo __('Ajouter un item'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à l\'arbre de compétences'), array('controller' => 'competences', 'action' => 'attachitem', 'evaluation_id' => $evaluation->id), array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<div class="alert alert-info">
  Vous êtes sur le point de créer un nouvel item.<br />
  Le nouvel item sera inséré dans la compétence <code><?php echo $path; ?></code>.

</div>

<?php 

echo $this->Form->create($item, array(
    'align' => [
        'md' => [
            'left' => 2,
            'middle' => 5,
            'right' => 5,
        ],
    ],
    'url' => array(
    	'controller' => 'evaluationsItems',
    	'action' => 'additem',
    	'evaluation_id' => $evaluation->id,
    	'competence_id' => $competence->id
    ),
));

echo $this->Form->input('title', array(
	'type' => 'textarea',
    'label' => array(
        'text' => 'Libellé de l\'item'
    )
)); 

echo $this->Form->input('levels._ids', array(
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
        <?php echo $this->Form->hidden('lpcnode_id'); ?>
        <div id="jumelage_lpc" class="jstree-default" style="margin-top:20px;">

        </div>
    </div>
</div>
<?php

echo $this->Form->hidden('competence_id', array('value' => $competence->id));
echo $this->Form->hidden('classroom_id', array('value' => $evaluation->classroom_id));
echo $this->Form->hidden('user_id', array('value' => $this->request->session()->read('Auth.User.id')));
echo $this->Form->hidden('type', array('value' => 3));
    
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
    var role = '<?php echo $this->request->session()->read('Auth.User.role'); ?>';
    var data = <?php echo $json; ?>;

    $("#jumelage_lpc").jstree({
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
