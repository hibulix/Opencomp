<div class="page-title">
    <h2><?php echo __('Modifier les paramètres de génération d\'un bulletin'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-ban"></i> '.__('annuler modification'), '/classrooms/viewreports/'.$classroom_id, array('class' => 'ontitle btn btn-danger', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create($report, array(
    'align' => [
        'md' => [
            'left' => 2,
            'middle' => 5,
            'right' => 5,
        ]
    ]
));

echo $this->Form->input('id');

echo $this->Form->input('title', array(
    'label' => array(
        'text' => 'Titre du bulletin'
    ),
    'help' => '<i class="fa fa-lightbulb-o"></i> '.__("Le titre vous permet d'identifier le bulletin mais n'est pas utilisé pour la génération du document"),
));

echo $this->Form->input('period_id', array(
    'type' => 'select',
    'class' => false,
    'data-placeholder' => 'Cliquez pour choisir les périodes à générer',
    'multiple' => 'multiple',
    'class' => 'chzn-select form-control',
    'options' => $periods,
    'label' => array(
        'text' => 'Période(s) à générer'
    )
));

echo $this->Form->input('header', array(
    'label' => array(
        'text' => 'En-tête de première page'
    ),
    'help' => '<i class="fa fa-lightbulb-o"></i> Vous pouvez utiliser les marqueurs #NOM# et #PRENOM# pour insérer le nom et le prénom de l\'élève'
));

echo $this->Form->input('footer', array(
    'label' => array(
        'text' => 'Pied de page'
    ),
    'help' => '<i class="fa fa-lightbulb-o"></i> Vous pouvez utiliser les marqueurs #NOM# et #PRENOM# pour insérer le nom et le prénom de l\'élève<br />&nbsp;&nbsp;&nbsp;Le numéro de la page est automatiquement inséré en fin de ligne'
));

echo $this->Form->input('page_break', array(
    'label' => array(
        'text' => 'Insérer saut de page avant'
    ),
    'data-placeholder' => 'Cliquez pour choisir les compétences avant lesquelles il faut insérer un saut de page',
    'options' => $competences,
    'multiple' => "multiple",
    'class' => 'chzn-select form-control',
    'help' => '<i class="fa fa-lightbulb-o"></i> '.__("Dans certains cas, il peut être utile d'insérer des sauts de pages avants certaines catégories de compétences pour améliorer la mise en page."),
));

?> <div class="form-group"> <?php

echo $this->Form->label('duplex_printing', 'Prêt pour le recto-verso');
?> <div class="col-md-3"> <?php
    echo $this->Form->checkbox('duplex_printing');
    ?> </div></div> <?php

echo $this->Form->hidden('classroom_id', array('value' => $classroom_id));


?>

<div class="form-group">
    <?php echo $this->Form->submit('Modifier le bulletin', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();
