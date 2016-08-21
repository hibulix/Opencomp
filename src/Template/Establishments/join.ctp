<?php
$this->assign('header', 'Rejoindre un établissement');
$this->assign('description', 'Vous serez prêt(e) en un clin d\'oeil !');
?>

<div class="row">
    <?php if (isset($establishment)) : ?>
        <div class="col-md-6">
              <div class="box box-success">
                   <div class="box-header with-border">
                       <h3 class="box-title"><i class="fa fa-fw fa-hand-o-right"></i> Êtes vous sûr(e) ?</h3>
                   </div>
                   <!-- /.box-header -->
                   <div class="box-body">
                        <p>Souhaitez-vous rejoindre cet établissement ?</p>
                        <p class="box-body border">
                            <?= $establishment->main_naming ?>
                            <?= $establishment->uai_patronym ?><br />
                            <?= $establishment->address ?><br />
                            <?= strtoupper($establishment->town->name) ?>
                        </p>
                        <div class="callout">
                            <p>En rejoingnant cet établissement, vous attestez sur l'honneur faire parti du corps enseignant.</p>
                        </div>
                        <?= $this->Form->postLink('<i class="fa fa-fw fa-sign-in"></i> Rejoindre',
                            '/establishments/join/' . $establishment->id, [
                                'escape' => false,
                                'class' => 'btn btn-lg btn-success'
                            ]) ?>
                   </div>
               </div>
        </div>
    <?php else : ?>
    <div class="col-md-6">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Je connais le code UAI</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php

                echo $this->Form->create('Establishment');

                echo $this->Form->input('id', array(
                    'help' => 'Le code UAI (ex RNE) permet d\'identifier de façon unique un établissement.',
                    'label' => array(
                        'text' => 'Code UAI'
                    )
                )); ?>

                <div class="form-group">
                    <?php echo $this->Form->submit('Rejoindre l\'établissement', array(
                        'div' => 'col col-md-9 col-md-offset-2',
                        'class' => 'btn btn-default'
                    )); ?>
                </div>

                <?= $this->Form->end(); ?>

            </div>
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-6">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Je ne connais pas le code UAI</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row"><div class="col-md-10">
                <?php

                echo $this->Form->create('Establishments');

                echo $this->Form->input('town_id', array(
                    'type' => 'select',
                    'label' => array(
                        'text' => 'Ville'
                    ),
                    'class' => 'js-data-example-ajax'
                ));

                echo $this->Form->input('sector', array(
                    'type' => 'select',
                    'label' => array(
                        'text' => 'Secteur'
                    ),
                    'options' => [
                        "Public" => "Public",
                        "Privé" => "Privé"
                    ]
                )); ?>

                <div class="form-group">
                    <?php echo $this->Form->submit('Trouver mon établissement', array(
                        'div' => 'col col-md-9 col-md-offset-2',
                        'class' => 'btn btn-default'
                    )); ?>
                </div>

                <?= $this->Form->end(); ?>
                </div></div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    <?php endif; ?>
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