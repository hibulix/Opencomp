<audio autoplay>
    <source src="/img/success.mp3" type="audio/mpeg">
</audio>

<div class="page-title">
    <h2><?php echo __('<span class="flash">Flashez</span> l\'élève dont vous souhaitez saisir le résultat'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-check"></i> '.__('J\'ai terminé la saisie'), '/evaluations/manageresults/'.$evaluation_id, array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create('Result', array(
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

echo $this->Form->input('pupil_id', array(
    'beforeInput' => '<div class="input-group"><span class="input-group-addon"><i class="fa fa-barcode"></i></span>',
    'afterInput' => '</div>',
	'class' => 'form-control send',
    'wrapInput' => 'col col-md-2',
	'type' => 'text',
    'label' => array(
        'text' => 'Code barre élève'
    )
));

echo $this->Form->end(); 

?>