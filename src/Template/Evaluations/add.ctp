<?php
$this->assign('header', $classroom->title);
$this->assign('description', $classroom->establishment->name);
$link = $this->Html->link(
    '<i class="fa fa-fw fa-cogs"></i> Gérer les périodes, système de notation et partage',
    '',
    [
        'escape' => false,
        'class' => 'btn btn-xs  btn-default'
    ]
);
$this->assign('right', $link);
?>

<?= $this->cell('Classroom::stats', [$classroom->id]); ?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Ajouter une évaluation</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <?php

                echo $this->Form->create($evaluation, ['align' => [
                    'md' => [
                        'left' => 2,
                        'middle' => 5,
                        'right' => 6,
                    ],
                ]]);

                echo $this->Form->input('title', [
                    'label' => [
                        'text' => 'Titre de l\'évaluation'
                    ]
                ]);

                echo $this->Form->hidden('classroom_id', ['value' => $params['pass'][0]]);

                echo $this->Form->input(
                    'period_id',
                    [
                        'class' => 'chzn-select form-control',
                        'empty' => 'Sélectionnez une période',
                        'label' => [
                            'text' => 'Période associée'
                        ]
                    ]
                );

                echo $this->Form->input(
                    'pupils._ids',
                    [
                        'class' => 'chzn-select form-control',
                        'id' => 'PupilPupil',
                        'data-placeholder' => 'Cliquez ici ou sur les boutons de niveaux pour ajouter des élèves.',
                        'help' => '<div class="help-block btn-toolbar" id="levels"></div>',
                        'label' => [
                            'text' => 'Élèves ayant passé l\'évaluation'
                        ]
                    ]
                );

                ?>

                <div class="form-group">
                    <?php echo $this->Form->submit('Créer cette évaluation', array(
                        'div' => 'col col-md-9 col-md-offset-2',
                        'class' => 'btn btn-primary'
                    )); ?>
                </div>

                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>



<?php $this->append('script'); ?>
<script type="text/javascript">

    var currentPupils = <?= $pupils; ?>;

    function sprintf( format )
    {
        for( var i=1; i < arguments.length; i++ ) {
            format = format.replace( /%s/, arguments[i] );
        }
        return format;
    }


    $.get( "/classrooms/pupils/<?= $classroom->id ?>.json?format=select2", function( data ) {
        var pupils = $("#PupilPupil");
        pupils.select2({
            data: data,
            language: "fr"
        });

        pupils.val(currentPupils).trigger("change");

        pupils.find("optgroup").each(function( index ) {
            var levelId = $(this).val();
            var levelLabel = $(this).attr('label');
            var buttonLevel = sprintf('<button class="btn btn-xs btn-default level" id="%s">Tous les %s</button>',levelId, levelLabel);
            var buttonUndo = '<button class="btn btn-xs btn-default cancel"><i class="fa fa-times-circle"></i></button>';
            $('#levels').append(sprintf('<div class="btn-group">%s %s</div>', buttonLevel, buttonUndo));
            console.log( index + ": " + $( this ).val() + $( this ).attr('label') );
        });

        $('.level').bind('click',function(e){
            e.preventDefault();
            var levelId = $( this ).attr('id');
            $("#PupilPupil").find("optgroup").each(function() {
                if($(this).val() === levelId){
                    $(this).children().prop("selected",true);
                    $("#PupilPupil").trigger('change.select2');
                }
            });
        });

        $('.cancel').bind('click',function(e){
            e.preventDefault();
            var levelId = $( this ).prev().attr('id');
            $("#PupilPupil").find("optgroup").each(function() {
                if($(this).val() === levelId){
                    $(this).children().prop("selected",false);
                    $("#PupilPupil").trigger('change.select2');
                }

            });
        });
    });


</script>
<?php $this->end(); ?>