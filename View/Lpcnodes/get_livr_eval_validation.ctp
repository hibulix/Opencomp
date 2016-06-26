<div class="page-title" xmlns="http://www.w3.org/1999/html">
    <h2><?php echo __('Importer les validations d\'items LPC précédentes depuis LivrEval'); ?></h2>
</div>

<ul class="breadcrumb">
    <li class="completed"><a href="#">Connexion à LivrEval</a></li>
    <li class="completed"><a href="#">Correspondance des élèves</a></li>
    <li class="active"><a href="#">Import des items déjà validés</a></li>
</ul>

<?php

if($palier == 2){
    $class_p1 = "btn btn-default";
    $class_p2 = "btn btn-default active";
}else{
    $class_p2 = "btn btn-default";
    $class_p1 = "btn btn-default active";
}

?>

<?php if(!isset($done)): ?>

<div style="margin-bottom: 20px;" class="btn-group first" role="group" aria-label="...">
    <?= $this->Html->link('Palier 1',
        array(
            'controller' => 'lpcnodes',
            'action' => 'getLivrEvalValidation',
            $classroom_id,
            1
        ),
        array(
            'class' => $class_p1
        )
    ); ?>
    <?= $this->Html->link('Palier 2',
        array(
            'controller' => 'lpcnodes',
            'action' => 'getLivrEvalValidation',
            $classroom_id,
            2
        ),
        array(
            'class' => $class_p2
        )
    ); ?>
</div>


<?php echo $this->Form->create('LivrEval', array(
    'inputDefaults' => array(
        'div' => 'form-group',
        'label' => array(
            'class' => 'col col-md-2 control-label'
        ),
        'wrapInput' => 'col col-md-3',
        'class' => 'form-control'
    ),
    'class' => 'form-horizontal'
)); ?>

<div class="form-group first">
        <?php echo $this->Form->submit('Récupérer les items validés', array(
    'div' => 'col col-md-9',
    'class' => 'btn btn-primary submit'
)); ?>
</div>

<div style="display: none;" class="alert alert-info">
    <i class="fa fa-spinner fa-pulse fa-fw fa fa-3x pull-left"></i>
    La récupération des items déjà validés est en cours.<br />
    Veuillez patienter ...
</div>

<?php else: ?>

<div class="alert alert-success">
    <i class="fa fa-check fa fa-3x pull-left"></i>
    <strong>Terminé !</strong><br />
    La récupération des items déjà validés a été réalisée avec succès.
</div>

<?php endif; ?>

<?php $this->start('script'); ?>
<script type="text/javascript" />
$('.submit').click(function(e) {
    $('.first').hide();
    $('.alert-info').show();
});

</script>
<?php $this->end();


?>