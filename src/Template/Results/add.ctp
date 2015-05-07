<?php if(isset($manual)): ?>
    <div class="page-title">
        <h2><?php echo __('Saisissez les résultats de l\'évaluation'); ?></h2>
    </div>
    <div class="well">
        Vous êtes sur le point de saisir les résultats de <code><?php echo $pupil->full_name; ?></code> pour l'évaluation <code><?php echo $evaluation->title; ?></code>.<br /><br />
        Saisissez l'ensemble des résultats. Pour chaque item, vous pouvez saisir <code>A</code>, <code>B</code>, <code>C</code>, <code>D</code>, <code>NE</code> ou <code>ABS</code> suivi de la touche tabulation. <br /> A la fin de la saisie, les résultats sont automatiquement sauvegardés.
    </div>
<?php else: ?>
    <div class="page-title">
        <h2><?php echo __('<span class="flash">Flashez</span> les résultats de l\'évaluation'); ?></h2>
    </div>
    <div class="well">
      Vous êtes sur le point de saisir les résultats de <code><?php echo $pupil->full_name; ?></code> pour l'évaluation <code><?php echo $evaluation->title; ?></code>.<br /><br />
      <span class="flash">Flashez</span> l'ensemble des résultats en utilisant la table de codage.<br /> A la fin de la saisie, les résultats sont automatiquement sauvegardés.
    </div>
<?php endif;

echo $this->Form->create($results, array(
    'align' => [
        'md' => [
            'left' => 2,
            'middle' => 3,
            'right' => 7,
        ]
    ]
));


foreach($items as $item){

    if(isset($manual)):
        echo $this->Form->input($item->Items['id'], array(
            'prepend' => '<i class="fa fa-keyboard-o"></i>',
            'help' => $item->Items['title'],
            'class' => 'form-control result',
            'value' => (isset($saved_results[$item->Items['id']])) ? $saved_results[$item->Items['id']] : '',
            'label' => array(
                'text' => 'Résultat item '.($item->EvaluationsItems['position'])
            )
        ));
    else:
        echo $this->Form->input($item->Items['id'], array(
            'prepend' => '<i class="fa fa-barcode"></i>',
            'help' => $item->Items['title'],
            'class' => 'form-control result',
            'value' => (isset($saved_results[$item->Items['id']])) ? $saved_results[$item->Items['id']] : '',
            'label' => array(
                'text' => 'Résultat item '.($item->EvaluationsItems['position'])
            )
        ));
    endif;
}

echo $this->Form->end(); 
