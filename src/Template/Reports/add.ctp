<div class="page-title">
    <h2><?php echo __('Définir les paramètres de génération d\'un bulletin'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-ban"></i> '.__('annuler création'), '/classrooms/viewreports/'.$classroom_id, array('class' => 'ontitle btn btn-danger', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create('Report', array(
    'inputDefaults' => array(
        'div' => 'form-group',
        'label' => array(
            'class' => 'col col-md-2 control-label'
        ),
        'wrapInput' => 'col col-md-7',
        'class' => 'form-control'
    ),
    'class' => 'form-horizontal'
));

echo $this->Form->input('id');

echo $this->Form->input('title', array(
    'label' => array(
        'text' => 'Titre du bulletin'
    ),
    'afterInput' => '<span style="font-style: italic; margin-top:10px;" class="help-block"><i class="fa fa-lightbulb-o"></i> '.__("Le titre vous permet d'identifier le bulletin mais n'est pas utilisé pour la génération du document").'</span>',
));

echo $this->Form->input('period_id', array(
    'error' => array('multiple' => __('Vous devez sélectionner au moins une période !')),
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
    'afterInput' => '<span style="font-style: italic;" class="help-block"><i class="fa fa-lightbulb-o"></i> Vous pouvez utiliser les marqueurs #NOM# et #PRENOM# pour insérer le nom et le prénom de l\'élève</span>'
));

echo $this->Form->input('footer', array(
    'label' => array(
        'text' => 'Pied de page'
    ),
    'afterInput' => '<span style="font-style: italic;" class="help-block"><i class="fa fa-lightbulb-o"></i> Vous pouvez utiliser les marqueurs #NOM# et #PRENOM# pour insérer le nom et le prénom de l\'élève<br />&nbsp;&nbsp;&nbsp;Le numéro de la page est automatiquement inséré en fin de ligne</span>'
));

echo $this->Form->input('page_break', array(
    'label' => array(
        'text' => 'Insérer saut de page avant'
    ),
    'data-placeholder' => 'Cliquez pour choisir les compétences avant lesquelles il faut insérer un saut de page',
    'options' => $competences,
    'multiple' => "multiple",
    'class' => 'chzn-select form-control',
    'afterInput' => '<span style="font-style: italic; margin-top:10px;" class="help-block"><i class="fa fa-lightbulb-o"></i> '.__("Dans certains cas, il peut être utile d'insérer des sauts de pages avants certaines catégories de compétences pour améliorer la mise en page.").'</span>',
));

echo $this->Form->input('duplex_printing', array(
    'before' => '<label class="col col-md-2 control-label">Prêt pour le recto-verso</label>',
    'label' => false,
    'class' => false,
    'after' => '<span style="font-style: italic; margin-top:30px; padding-left:15px;" class="help-block col-md-offset-2"><i class="fa fa-lightbulb-o"></i> '.__("Si vous cochez la case, des pages blanches seront insérées automatiquement au PDF pour permettre l'impression recto-vero.").'</span>'
));

echo $this->Form->hidden('classroom_id', array('value' => $classroom_id));


?>

<div class="form-group">
    <?php echo $this->Form->submit('Créer le bulletin', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();
