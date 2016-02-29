<div class="row">
    <div class="col-md-12">
<div class="page-title">
    <h2><?php echo __('Saisie manuelle des résultats de l\'évaluation'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-check"></i> '.__('j\'ai terminé la saisie'), '/evaluations/manageresults/'.$evaluation['Evaluation']['id'], array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
</div>
        </div></div>

<div class="row">
<div class="col-md-2 col-xs-2" style="padding-right:0px;">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th style="height:35px;">
                    <span class="text-success" style="display:none;"><i class="fa fa-check"></i> <small>résultats sauvegardés</small></span>
                    <span class="text-muted" style="display:none;"><i class="fa fa-spinner fa-pulse"></i> <small>sauvegarde des résultats</small></span>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr class="danger">
                <th scope="row" style="padding: 5px; height:33px;">Tous</th>
            </tr>
            <?php foreach($pupils as $level_name => $classroom_pupils): ?>
            <tr class="warning">
                <th scope="row" style="padding: 5px; height:33px;"><i class="fa fa-chevron-down fa-lg" onclick="$('.<?= $level_name ?>').fadeToggle(); $(this).toggleClass('fa-chevron-down'); $(this).toggleClass('fa-chevron-right')"></i> <?= $level_name ?></th>
            </tr>
            <?php foreach($classroom_pupils as $id_pupil => $pupil_name): ?>
                <tr class="<?= $level_name ?>">
                    <th scope="row" style="padding: 5px; height:33px;">
                        <button class="btn btn-default btn-xs"><i class="pupil-actions fa fa-user-times text-muted info" data-pupilid="<?= $id_pupil ?>" data-result="ne" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Marquer « non évalué » pour l'ensemble des items de l'évaluation"></i></button>
                        <button class="btn btn-default btn-xs"><i class="pupil-actions fa fa-stethoscope text-muted info" data-pupilid="<?= $id_pupil ?>" data-result="abs" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Marquer « absent » pour l'ensemble des items de l'évaluation"></i></button>
                        <?= $pupil_name ?>
                    </th>
                </tr>
            <?php endforeach; ?>

            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
    <style type="text/css">
        td,th{
            width:20px;
        }
    </style>
<div class="col-md-10 col-xs-10" style="padding-left:0px;">
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <?php foreach($items as $item): ?>
                <th data-container="body" data-toggle="popover" title="Item n°<?= $item['EvaluationsItem']['position'] ?>" data-trigger="hover" data-placement="bottom" data-content="<?= $item['Item']['title'] ?>">
                    Item n°<?= $item['EvaluationsItem']['position'] ?>
                </th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <tr class="danger">
            <?php foreach($items as $item): ?>
                <td style="padding: 5px; height:33px;">
                    <div style="display: flex;" class="btn-group" role="group" aria-label="...">
                        <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="a" class="btn btn-xs btn-all info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer A (acquis) à toute la classe">A</button>
                        <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="b" class="btn btn-xs btn-all info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer B (à renforcer) à toute la classe">B</button>
                        <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="c" class="btn btn-xs btn-all info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer C (en cours d'acquisition) à toute la classe">C</button>
                        <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="d" class="btn btn-xs btn-all info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer D (non acquis) à toute la classe">D</button>
                        <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="ne" class="btn btn-xs btn-all info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer NE (non évalué) à toute la classe">NE</button>
                    </div>
                </td>
            <?php endforeach; ?>
        </tr>
        <?php foreach($pupils as $level_name => $classroom_pupils): ?>
            <tr class="warning">
                <?php foreach($items as $item): ?>
                    <td style="padding: 5px; height:33px;">
                        <div style="display: flex;" class="btn-group" role="group" aria-label="...">
                            <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="a" data-level="<?= $level_name ?>" class="<?= $level_name ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer A (acquis) à tous les <?= $level_name ?>">A</button>
                            <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="b" data-level="<?= $level_name ?>" class="<?= $level_name ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer B (à renforcer) à tous les <?= $level_name ?>">B</button>
                            <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="c" data-level="<?= $level_name ?>" class="<?= $level_name ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer C (en cours d'acquisition) à tous les <?= $level_name ?>">C</button>
                            <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="d" data-level="<?= $level_name ?>" class="<?= $level_name ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer D (non acquis) à tous les <?= $level_name ?>">D</button>
                            <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="ne" data-level="<?= $level_name ?>" class="<?= $level_name ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer NE (non évalué) à tous les <?= $level_name ?>">NE</button>
                        </div>
                    </td>
                <?php endforeach; ?>
            </tr>
            <?php foreach($classroom_pupils as $id_pupil => $pupil_name): ?>
            <tr class="<?= $level_name ?>">
                <?php foreach($items as $item): ?>
                    <td style="padding: 5px; height:33px;">
                        <div style="display: flex;" class="btn-group" role="group" aria-label="...">
                            <button data-level="<?= $level_name ?>"  data-pupilid="<?= $id_pupil ?>" data-result="a"  data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_a" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Acquis">A</button>
                            <button data-level="<?= $level_name ?>" data-pupilid="<?= $id_pupil ?>" data-result="b" data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_b" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="À renforcer">B</button>
                            <button data-level="<?= $level_name ?>" data-pupilid="<?= $id_pupil ?>" data-result="c" data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_c" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="En cours d'acquisition">C</button>
                            <button data-level="<?= $level_name ?>" data-pupilid="<?= $id_pupil ?>" data-result="d" data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_d" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Non acquis">D</button>
                            <button data-level="<?= $level_name ?>" data-pupilid="<?= $id_pupil ?>" data-result="ne" data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_ne" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Non évalué">NE</button>
                            <button data-level="<?= $level_name ?>" data-pupilid="<?= $id_pupil ?>" data-result="abs" data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_abs" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Absent">ABS</button>
                        </div>
                    </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
</div>

<script type="text/javascript">
    var results = <?= $json_results ?>;
    var colors = { "a" : "success", "b" : "info", "c": "warning", "d": "danger", "ne": "dark", "abs": "dark" };
    for (var key in results) {
        var id = results[key].item_id + '_' + results[key].pupil_id + '_' + results[key].result.toLowerCase();
        var result = results[key].result.toLowerCase();
        var color = "btn-" + colors[result];

        $('#'+id).removeClass('btn-default').addClass("active").addClass(color);
    }

    $( "tr > td > div > button.btn" ).click(function() {
        var itemid = $(this).data("itemid");
        var pupilid = $(this).data("pupilid");
        var level = $(this).data("level");
        var result = $(this).data("result");

        if(typeof pupilid === 'undefined' && typeof level === 'undefined'){
            //Modifier l'item pour la classe
            saveResultForSpecificItem(itemid, result);
        } else if(typeof pupilid === 'undefined') {
            //Modifier l'item pour la niveau
            saveResultForSpecificItemAndLevel(itemid, level, result);
        } else {
            //Modifier l'item pour l'élève
            saveResultForSpecificItemAndPupil(itemid, pupilid, result);
        }
    });

    $( "i.pupil-actions" ).click(function() {
        var pupilid = $(this).data("pupilid");
        var result = $(this).data("result");
        var color = "btn-" + colors[result];

        //Modifier les items pour l'élève
        if (confirm("Voulez-vous vraiment attribuer "+result.toUpperCase()+" à cet(te) élève pour l'ensemble des items de l'évaluation ? \nLes résultats qui auraient précédement été renseignés seront perdus.") == true) {
            $('button[data-pupilid="' + pupilid + '"]')
                .removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').removeClass('btn-info')
                .removeClass('btn-dark').removeClass('active').removeClass('btn-default').addClass('btn-default')
                .filter('button[data-result="' + result + '"]')
                .removeClass('btn-default').addClass(color).addClass('active');
        }
    });

    function saveResultForSpecificItem(itemid, result){
        if (confirm("Voulez-vous vraiment attribuer "+result.toUpperCase()+" à tous les élèves de la classe pour cet item ? \nLes résultats qui auraient précédement été renseignés seront perdus.") == true) {
            var color = "btn-" + colors[result];
            $('button[data-itemid="'+itemid+'"]')
                .removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').removeClass('btn-info')
                .removeClass('btn-dark')
                .not('button[data-type="global"]').removeClass('active').removeClass('btn-default').addClass('btn-default')
                .filter('button[data-result="'+result+'"]')
                .removeClass('btn-default').addClass(color).addClass('active');
        }
    }

    function saveResultForSpecificItemAndLevel(itemid, level, result){
        if (confirm("Voulez-vous vraiment attribuer "+result.toUpperCase()+" à tous les élèves du niveau "+level+" pour cet item ? \nLes résultats qui auraient précédement été renseignés pour ces élèves seront perdus.") == true) {
            var color = "btn-" + colors[result];
            $('button[data-itemid="' + itemid + '"]')
                .filter('button[data-level="' + level + '"]')
                .removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').removeClass('btn-info')
                .removeClass('btn-dark')
                .not('button[data-type="global"]').removeClass('active').removeClass('btn-default').addClass('btn-default')
                .filter('button[data-result="' + result + '"]')
                .removeClass('btn-default').addClass(color).addClass('active');
        }
    }

    function saveResultForSpecificItemAndPupil(itemid, pupilid, result){
        var color = "btn-" + colors[result];
        $('button[data-itemid="'+itemid+'"]')
            .filter('button[data-pupilid="'+pupilid+'"]')
            .removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').removeClass('btn-info')
            .removeClass('btn-dark')
            .not('button[data-type="global"]').removeClass('active').removeClass('btn-default').addClass('btn-default')
            .filter('button[data-result="'+result+'"]')
            .removeClass('btn-default').addClass(color).addClass('active');
    }
</script>