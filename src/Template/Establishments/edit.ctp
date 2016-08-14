<?php
$this->assign('header', 'Établissement');
$this->assign('description', $establishment->id);
?>

<div class="row">
<div class="col-sm-6">

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-pencil"></i> Modifier les détails de l'établissement</h3>
        <div class="box-tools">
            <?php echo $this->Html->link('<i class="fa fa-fw fa-arrow-left"></i> '.__('retour à l\'établissement'), '/establishments/view/'.$establishment->id, array('class' => 'ontitle btn btn-default btn-sm', 'escape' => false)); ?>
        </div>
    </div>
    <div class="box-body">
<?php

echo $this->Form->create($establishment, ['align' => [
    'md' => [
        'left' => 3,
        'middle' => 9,
        'right' => 0,
    ],
]]);

echo $this->Form->input('name', array(
    'label' => array(
        'text' => 'Appellation officielle'
    )
));

echo $this->Form->input('main_naming', array(
    'label' => array(
        'text' => 'Appellation principale'
    )
));

echo $this->Form->input('uai_patronym', array(
    'label' => array(
        'text' => 'Patronyme UAI'
    )
));

echo $this->Form->input('sector', array(
    'type' => 'radio',
    'options' => ['Public'=>'Public', 'Privé'=>'Privé'],
    'label' => array(
        'text' => 'Secteur'
    )
));

echo $this->Form->input('address', array(
    'type' => 'text',
    'label' => array(
        'text' => 'Adresse'
    )
));

echo $this->Form->input('town_id', array(
    'type' => 'select',
    'options' => [$establishment->town->id => $establishment->town->name],
    'label' => array(
        'text' => 'Ville'
    ),
    'class' => 'js-data-example-ajax'
));

echo $this->Form->input('locality', array(
    'label' => array(
        'text' => 'Lieu-dit'
    )
));

echo $this->Form->input('X', array(
    'label' => array(
        'text' => 'Coordonnée X'
    ),
    'help' => '<i class="fa fa-fw fa-globe"></i> Lambert 93'
));

echo $this->Form->input('Y', array(
    'label' => array(
        'text' => 'Coordonnée Y'
    ),
    'help' => '<i class="fa fa-fw fa-globe"></i> Lambert 93'
));

?>

<div class="form-group">
    <?php echo $this->Form->submit('Modifier l\'établissement', array(
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
    function format (town) {
        if (town.title == '')
            return '<i class="fa fa-fw fa-map-marker"></i> ' + town.id + ' - ' + town.text;
        else if (town.loading)
            return town.text;
        else
            return '<i class="fa fa-fw fa-map-marker"></i> ' + town.id + ' - ' + town.name +
                ' <em style="float:right;"><i class="fa fa-fw fa-graduation-cap"></i> Académie de <strong>' +
                town.academy.name + '</strong></em>';


    }

    var town = $(".js-data-example-ajax");
    town.select2({
        ajax: {
            url: "/towns.json",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: data.towns,
                    pagination: {
                        more: (params.page * data.paging.perPage) < data.paging.totalCount
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 2,
        language: "fr",
        templateResult: format, // omitted for brevity, see the source of this page
        templateSelection: format // omitted for brevity, see the source of this page
    });
</script>
<?php $this->end(); ?>
