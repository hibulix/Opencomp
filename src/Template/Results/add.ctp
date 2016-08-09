<?php
/** @var \App\Model\Entity\Evaluation $evaluation */
$this->assign('description', $this->Html->link($evaluation->title,'/evaluations/results/'.$evaluation->id));
$this->assign('header', '<kbd style="font-size: xx-large; display:none;">↹</kbd> Saisie des résultats de l\'évaluation')
?>

    <!--suppress ALL -->
<div class="row">
    <div class="col-md-6">
        <div id="pupil-input" class="box box-default">
            <div class="box-header with-border">
                <i class="fa fa-child"></i>

                <h3 class="box-title">Sélectionner un élève</h3>
                <div class="box-tools pull-right">
                    <?= $this->Html->link('<i class="fa fa-fw fa-check"></i> j\'ai terminé la saisie','/evaluations/insights/'.$evaluation->id,['class'=>'btn btn-sm btn-success','escape'=>false]); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php

                echo $this->Form->create(null, array(
                    'align' => [
                        'md' => [
                            'left' => 3,
                            'middle' => 3,
                            'right' => 6,
                        ]
                    ]
                ));

                echo $this->Form->input('pupil_id', array(
                    'class' => 'form-control send',
                    'autocomplete' => 'off',
                    'type' => 'text',
                    'label' => array(
                        'text' => 'Code barre élève'
                    )
                ));

                echo $this->Form->end();
                ?>
            </div>
            <!-- /.box-body -->
        </div>
        <div id="results-input" class="box box-default" style="display: none;">
            <div class="box-header with-border">
                <i class="fa fa-pie-chart"></i>

                <h3 class="box-title"><?php echo __('<span class="flash">Flashez</span> les résultats de '); ?><strong><span id="pupil-name"></span></strong></h3>
                <div class="box-tools pull-right">
                    <?= $this->Html->link('<i class="fa fa-fw fa-ban"></i> annuler la saisie de cet élève','#cancel',['class'=>'btn btn-sm btn-danger','id'=>'cancel','escape'=>false]); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                echo $this->Form->create("#", array(
                    'align' => [
                        'md' => [
                            'left' => 3,
                            'middle' => 8   ,
                            'right' =>1,
                        ]
                    ]
                ));


                /** @var \Cake\ORM\ResultSet $competences */
                foreach($competences as $competence){

                    if(isset($manual)):
                        echo $this->Form->input($competence->Competences['id'], array(
                            'prepend' => '<i class="fa fa-keyboard-o"></i>',
                            'help' => $competence->Competences['title'],
                            'autocomplete' => 'off',
                            'class' => 'form-control result',
                            'value' => (isset($saved_results[$competence->Competences['id']])) ? $saved_results[$competence->Competences['id']] : '',
                            'label' => array(
                                'text' => 'Résultat competence '.($competence->EvaluationsCompetences['position'])
                            )
                        ));
                    else:
                        echo $this->Form->input($competence->Competences['id'], array(
                            'prepend' => '<i class="fa fa-barcode"></i>',
                            'help' => $competence->Competences['title'],
                            'autocomplete' => 'off',
                            'class' => 'form-control result',
                            'value' => (isset($saved_results[$competence->Competences['id']])) ? $saved_results[$competence->Competences['id']] : '',
                            'label' => array(
                                'text' => 'Résultat competence '.($competence->EvaluationsCompetences['position'])
                            )
                        ));
                    endif;
                }

                echo $this->Form->end();
                ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-6" >
        <div class="box box-default" >
            <div class="box-header with-border">
                <i class="fa fa-dashboard"></i>

                <h3 class="box-title">Etat de la saisie</h3>
                <div class="box-tools pull-right" style="margin-top: 4px;">
                    <span style="margin-right: 10px;"><i class="fa fa-fw fa-circle text-aqua"></i> saisie en cours</span>
                    <span><i class="fa fa-fw fa-circle text-green"></i> dernière saisie</span>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">


                            <?php echo $this->element('TestResults', [
                                compact('competences','levels_pupils')
                            ]); ?>


            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>

<?php $this->start('script'); ?>
<script src="/components/jquery-emulatetab/src/emulatetab.joelpurra.js"></script>
<script src="/js/opencomp.results.add.min.js"></script>
<script type="text/javascript">
    var baseURL =  $('#base_url').text();
    evaluation = <?= json_encode($evaluation); ?>;
    $.get(baseURL + "results/evaluation/" + <?= $evaluation->id ?> + ".json" , function( data ) {
        loadResults(data)
    });
</script>
<?php $this->end(); ?>
