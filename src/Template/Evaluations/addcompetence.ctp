<?php
$this->assign('header', 'Ajouter une compétence personnelle à l\'évaluation');
?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Ajouter une compétence personnelle à l'évaluation</h3>
    </div>
    <div class="box-body">
        <?php

        echo $this->Form->create($competence, ['align' => [
            'md' => [
                'left' => 2,
                'middle' => 5,
                'right' => 6,
            ],
        ]]);

        echo $this->Form->input('title', [
            'label' => [
                'text' => 'Libellé de la compétence'
            ]
        ]);

        echo $this->Form->hidden('repository_id', [
            'value' => $repositoryId
        ]);

        echo $this->Form->hidden('classroom_id', [
            'value' => $evaluation->classroom_id
        ]);

        echo $this->Form->hidden('type', [
            'value' => 1
        ]);

        echo $this->Form->hidden('user_id', [
            'value' => $this->request->session()->read('Auth.User.id')
        ]);

        echo $this->Form->hidden('parent_id', [
            'value' => $parentCompetenceId
        ]);

        echo $this->Form->submit();
        echo $this->Form->end();

        ?>
    </div>
</div>