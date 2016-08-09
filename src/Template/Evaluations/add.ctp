<div class="page-title">
    <h2><?php echo __('Ajouter une évaluation'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à la classe'), '/classrooms/viewtests/'.$classroom->id, array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php

echo $this->Form->create($evaluation, ['align' => [
    'md' => [
        'left' => 2,
        'middle' => 5,
        'right' => 6,
    ],
]]);

echo $this->Form->input('title', array(
    'label' => array(
        'text' => 'Titre de l\'évaluation'
    )
));

echo $this->Form->input('period_id', array(
    'class'=>'chzn-select form-control',
    'label' => array(
        'text' => 'Période associée'
        )
    )
);

echo $this->Form->input('pupils._ids', array(
    'class'=>'chzn-select form-control',
    'id'=>'PupilPupil',
    'data-placeholder' => 'Cliquez ici ou sur les boutons de niveaux pour ajouter des élèves.',
    'help' => '<div class="help-block btn-toolbar" id="levels"></div>',
    'label' => array(
        'text' => 'Élèves ayant passé l\'évaluation'
        )
    )
);

?>

<div class="form-group">
    <?php echo $this->Form->submit('Créer cette évaluation', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end();

$this->append('script'); ?>
<script type="text/javascript">

    var currentPupils = <?= $pupils; ?>;

    function sprintf( format )
    {
        for( var i=1; i < arguments.length; i++ ) {
            format = format.replace( /%s/, arguments[i] );
        }
        return format;
    }


    $.get( "/classrooms/view/<?= $classroom->id ?>.json?format=select2", function( data ) {
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