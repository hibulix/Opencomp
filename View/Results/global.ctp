<div class="row">
    <div class="col-md-12">
<div class="page-title">
    <h2><?php echo __('Saisie à la souris des résultats de l\'évaluation'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-check"></i> '.__('j\'ai terminé la saisie'), '/evaluations/analyseresults/'.$evaluation['Evaluation']['id'], array('class' => 'ontitle btn btn-success', 'escape' => false)); ?>
</div>
<h3><?= $evaluation['Evaluation']['title'] ?></h3>
        </div></div>

<div class="row">
<div class="col-md-2 col-xs-2" style="padding-right:0px;">
    <div class="table-responsive" style="overflow:hidden;">
        <table class="table table-striped">
            <thead>
            <tr>
                <th style="height:35px;">
                    <span id="error" class="text-danger" style="display:none;" data-container="body" data-toggle="tooltip" data-placement="bottom" title=""><i class="fa fa-times"></i> <small>une erreur est survenue</small></span>
                    <span id="saved" class="text-success" style="display:none;"><i class="fa fa-floppy-o"></i> <small>résultats sauvegardés</small></span>
                    <span id="loaded" class="text-success" style="display:none;"><i class="fa fa-check"></i> <small>résultats chargés</small></span>
                    <span id="saving" class="text-muted" style="display:none;"><i class="fa fa-spinner fa-pulse"></i> <small>sauvegarde des résultats</small></span>
                    <span id="loading" class="text-muted"><i class="fa fa-spinner fa-pulse"></i> <small>chargement des résultats</small></span>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr class="danger">
                <th scope="row" style="padding: 5px; height:33px;">Tous</th>
            </tr>
            <?php foreach($pupils as $level_id => $level): ?>
            <tr class="warning">
                <th scope="row" style="padding: 5px; height:33px;"><i class="fa fa-chevron-down fa-lg" onclick="$('.<?= $level['title'] ?>').fadeToggle(); $(this).toggleClass('fa-chevron-down'); $(this).toggleClass('fa-chevron-right')"></i> <?= $level['title'] ?></th>
            </tr>
            <?php foreach($level['pupils'] as $id_pupil => $pupil_name): ?>
                <tr class="<?= $level['title'] ?>">
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
        <?php foreach($pupils as $level_id => $level): ?>
            <tr class="warning">
                <?php foreach($items as $item): ?>
                    <td style="padding: 5px; height:33px;">
                        <div style="display: flex;" class="btn-group" role="group" aria-label="...">
                            <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="a" data-levelid="<?= $level_id ?>" class="<?= $level['title'] ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer A (acquis) à tous les <?= $level['title'] ?>">A</button>
                            <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="b" data-levelid="<?= $level_id ?>" class="<?= $level['title'] ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer B (à renforcer) à tous les <?= $level['title'] ?>">B</button>
                            <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="c" data-levelid="<?= $level_id ?>" class="<?= $level['title'] ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer C (en cours d'acquisition) à tous les <?= $level['title'] ?>">C</button>
                            <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="d" data-levelid="<?= $level_id ?>" class="<?= $level['title'] ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer D (non acquis) à tous les <?= $level['title'] ?>">D</button>
                            <button type="button" data-type="global" data-itemid="<?= $item['Item']['id'] ?>" data-result="ne" data-levelid="<?= $level_id ?>" class="<?= $level['title'] ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer NE (non évalué) à tous les <?= $level['title'] ?>">NE</button>
                        </div>
                    </td>
                <?php endforeach; ?>
            </tr>
            <?php foreach($level['pupils'] as $id_pupil => $pupil_name): ?>
            <tr class="<?= $level['title'] ?>">
                <?php foreach($items as $item): ?>
                    <td style="padding: 5px; height:33px;">
                        <div style="display: flex;" class="btn-group" role="group" aria-label="...">
                            <button data-levelid="<?= $level_id ?>"  data-pupilid="<?= $id_pupil ?>" data-result="a"  data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_a" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Acquis">A</button>
                            <button data-levelid="<?= $level_id ?>" data-pupilid="<?= $id_pupil ?>" data-result="b" data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_b" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="À renforcer">B</button>
                            <button data-levelid="<?= $level_id ?>" data-pupilid="<?= $id_pupil ?>" data-result="c" data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_c" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="En cours d'acquisition">C</button>
                            <button data-levelid="<?= $level_id ?>" data-pupilid="<?= $id_pupil ?>" data-result="d" data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_d" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Non acquis">D</button>
                            <button data-levelid="<?= $level_id ?>" data-pupilid="<?= $id_pupil ?>" data-result="ne" data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_ne" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Non évalué">NE</button>
                            <button data-levelid="<?= $level_id ?>" data-pupilid="<?= $id_pupil ?>" data-result="abs" data-itemid="<?= $item['Item']['id'] ?>" id="<?= $item['Item']['id'] ?>_<?= $id_pupil ?>_abs" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Absent">ABS</button>
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
    $( document ).ready(function() {
        for (var key in results) {
            var id = results[key].item_id + '_' + results[key].pupil_id + '_' + results[key].result.toLowerCase();
            var result = results[key].result.toLowerCase();
            var color = "btn-" + colors[result];

            $('#'+id).removeClass('btn-default').addClass("active").addClass(color);
        }
        $('#loading').hide();
        $('#loaded').show();
        setTimeout(function() {
            $("#loaded").fadeOut();
        }, 5000);
    });

    $( "tr > td > div > button.btn" ).click(function() {
        var itemid = $(this).data("itemid");
        var pupilid = $(this).data("pupilid");
        var levelid = $(this).data("levelid");
        var result = $(this).data("result");

        if(typeof pupilid === 'undefined' && typeof levelid === 'undefined'){
            //Modifier l'item pour la classe
            saveResultForSpecificItem(itemid, result);
        } else if(typeof pupilid === 'undefined') {
            //Modifier l'item pour la niveau
            saveResultForSpecificItemAndLevel(itemid, levelid, result);
        } else {
            //Modifier l'item pour l'élève
            saveResultForSpecificItemAndPupil(itemid, pupilid, result);
        }
        $('.info').tooltip('hide');
    });

    $( "i.pupil-actions" ).click(function() {
        var pupilid = $(this).data("pupilid");
        var result = $(this).data("result");
        var color = "btn-" + colors[result];

        bootbox.confirm("Voulez-vous vraiment attribuer "+result.toUpperCase()+" à tous les items de cette évaluation pour cet élève ? <br />Les résultats qui auraient précédement été renseignés seront perdus.", function(res) {
            if (res == true) {
                var color = "btn-" + colors[result];
                $('#saving').show(); $('#saved').hide(); $('#error').hide();
                $.get( "<?= FULL_BASE_URL ?>/results/setresultforspecificpupil/<?= $this->params['pass'][0] ?>/"+pupilid+"/"+result.toUpperCase(), function( data ){
                    $('button[data-pupilid="' + pupilid + '"]')
                        .removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').removeClass('btn-info')
                        .removeClass('btn-dark').removeClass('active').removeClass('btn-default').addClass('btn-default')
                        .filter('button[data-result="' + result + '"]')
                        .removeClass('btn-default').addClass(color).addClass('active');
                    $('#saving').hide(); $('#saved').show();
                    setTimeout(function() {
                        $("#saved").fadeOut();
                    }, 5000);
                }).fail(function(data){
                    $('#saving').hide();
                    $('#error').show()
                      .tooltip('hide').attr('data-original-title', data.responseJSON.message)
                      .tooltip('fixTitle').tooltip('show');
                });
            }
        });
    });

    function saveResultForSpecificItem(itemid, result){
        bootbox.confirm("Voulez-vous vraiment attribuer "+result.toUpperCase()+" à tous les élèves de la classe pour cet item ? <br />Les résultats qui auraient précédement été renseignés seront perdus.", function(res) {
            if (res == true) {
                var color = "btn-" + colors[result];
                $('#saving').show(); $('#saved').hide(); $('#error').hide();
                $.get( "<?= FULL_BASE_URL ?>/results/setresultforspecificitem/<?= $this->params['pass'][0] ?>/"+itemid+"/"+result.toUpperCase(), function( data ){
                    $('button[data-itemid="'+itemid+'"]')
                        .removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').removeClass('btn-info')
                        .removeClass('btn-dark')
                        .not('button[data-type="global"]').removeClass('active').removeClass('btn-default').addClass('btn-default')
                        .filter('button[data-result="'+result+'"]')
                        .removeClass('btn-default').addClass(color).addClass('active');
                    $('#saving').hide(); $('#saved').show();
                    setTimeout(function() {
                        $("#saved").fadeOut();
                    }, 5000);
                }).fail(function(data){
                    $('#saving').hide();
                    $('#error').show()
                      .tooltip('hide').attr('data-original-title', data.responseJSON.message)
                      .tooltip('fixTitle').tooltip('show');
                });
            }
        });
    }

    function saveResultForSpecificItemAndLevel(itemid, level, result){
        bootbox.confirm("Voulez-vous vraiment attribuer "+result.toUpperCase()+" à tous les élèves de ce niveau pour cet item ? <br />Les résultats qui auraient précédement été renseignés seront perdus.", function(res) {
            if (res == true) {
                var color = "btn-" + colors[result];
                $('#saving').show(); $('#saved').hide(); $('#error').hide();
                $.get( "<?= FULL_BASE_URL ?>/results/setresultforspecificitemlevel/<?= $this->params['pass'][0] ?>/"+itemid+"/"+level+"/"+result.toUpperCase(), function( data ){
                    $('button[data-itemid="' + itemid + '"]')
                        .filter('button[data-levelid="' + level + '"]')
                        .removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').removeClass('btn-info')
                        .removeClass('btn-dark')
                        .not('button[data-type="global"]').removeClass('active').removeClass('btn-default').addClass('btn-default')
                        .filter('button[data-result="' + result + '"]')
                        .removeClass('btn-default').addClass(color).addClass('active');
                    $('#saving').hide(); $('#saved').show();
                    setTimeout(function() {
                        $("#saved").fadeOut();
                    }, 5000);
                }).fail(function(data){
                    $('#saving').hide();
                    $('#error').show()
                      .tooltip('hide').attr('data-original-title', data.responseJSON.message)
                      .tooltip('fixTitle').tooltip('show');
                });
            }
        });
    }

    function saveResultForSpecificItemAndPupil(itemid, pupilid, result){
        var color = "btn-" + colors[result];
        $('#saving').show(); $('#saved').hide(); $('#error').hide();
        $.get( "<?= FULL_BASE_URL ?>/results/setresultforspecificitempupil/<?= $this->params['pass'][0] ?>/"+itemid+"/"+pupilid+"/"+result.toUpperCase(), function( data ){
            $('button[data-itemid="'+itemid+'"]')
                .filter('button[data-pupilid="'+pupilid+'"]')
                .removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').removeClass('btn-info')
                .removeClass('btn-dark')
                .not('button[data-type="global"]').removeClass('active').removeClass('btn-default').addClass('btn-default')
                .filter('button[data-result="'+result+'"]')
                .removeClass('btn-default').addClass(color).addClass('active');
            $('#saving').hide(); $('#saved').show();
            setTimeout(function() {
                $("#saved").fadeOut();
            }, 5000);
        }).fail(function(data){
            $('#saving').hide();
            $('#error').show()
              .tooltip('hide').attr('data-original-title', data.responseJSON.message)
              .tooltip('fixTitle').tooltip('show');
        });
    }
</script>
