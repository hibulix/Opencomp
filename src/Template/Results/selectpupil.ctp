<audio autoplay>
    <source src="<?php echo $this->Url->build('/img/success.mp3', true); ?>" type="audio/mpeg">
</audio>

<div class="page-title">
    <h2><?php echo __('<span class="flash">Flashez</span> l\'élève dont vous souhaitez saisir le résultat'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-check"></i> '.__('J\'ai terminé la saisie'), '/evaluations/manageresults/'.$evaluation->id, array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create(null, array(
    'align' => [
        'md' => [
            'left' => 2,
            'middle' => 3,
            'right' => 7,
        ]
    ]
));

echo $this->Form->input('pupil_id', array(
	'class' => 'form-control send',
	'type' => 'text',
    'label' => array(
        'text' => 'Code barre élève'
    )
));

echo $this->Form->end(); 
