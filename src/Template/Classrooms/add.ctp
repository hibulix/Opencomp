<?php
$this->assign('header', 'Créer une nouvelle classe');
?>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Créer une classe</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php

                    echo $this->Form->create($classroom, [
                        'align' => [
                            'md' => [
                                'left' => 2,
                                'middle' => 3,
                                'right' => 7,
                            ],
                        ]]);

                    echo $this->Form->input('title', [
                        'label' => [
                            'text' => 'Nom de la classe'
                        ]
                    ]);

                    ?>

                    <div class="form-group">
                        <?php echo $this->Form->submit('Ajouter la classe', [
                            'div' => 'col col-md-9 col-md-offset-2',
                            'class' => 'btn btn-primary'
                         ]); ?>
                    </div>

                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>