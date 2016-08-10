<?php
$this->assign('header', $evaluation->title);
?>


<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <i class="fa fa-pie-chart"></i>
                <h3 class="box-title">Saisissez les résultats de l'évaluation</h3>
                <div class="box-tools pull-right">
                    <?= $this->AuthLink->link('<i class="fa fa-check"></i> '.__('j\'ai terminé la saisie'), '/evaluations/insights/'.$evaluation->id, array('class' => 'btn btn-sm btn-success', 'escape' => false)); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <div class="row">
                    <div class="col-md-2 col-xs-2" style="padding-right:0;">
                        <div class="">
                            <style>
                                .competences td, .competences th{
                                    display: inline-block;
                                }
                                .competences tr{
                                    white-space: nowrap;
                                }
                            </style>
                            <table style="table-layout: fixed;" class="table table-striped">
                                <thead>
                                <tr>
                                    <th style="white-space:nowrap; text-overflow: ellipsis; overflow:hidden; height:35px; padding: 5px;">
                                        <span id="error" class="text-danger" style="display:none;" data-container="body" data-toggle="tooltip" data-placement="bottom" title=""><i class="fa fa-times"></i> <small>une erreur est survenue</small></span>
                                        <span id="saved" class="text-success" style="display:none;"><i class="fa fa-floppy-o"></i> <small>résultats sauvegardés</small></span>
                                        <span id="loaded" class="text-success" style="display:none;"><i class="fa fa-check"></i> <small>résultats chargés</small></span>
                                        <span id="saving" class="text-muted" style="display:none;"><i class="fa fa-spinner fa-pulse"></i> <small>sauvegarde des résultats</small></span>
                                        <span id="loading" class="text-muted"><i class="fa fa-spinner fa-pulse"></i> <small>chargement des résultats</small></span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr style="height:35px;" class="danger">
                                    <th scope="row" style="padding: 5px; height:35px;">Tous</th>
                                </tr>
                                <?php foreach ($levelsPupils as $level_id => $levels): ?>
                                    <?php foreach ($levels as $level_label => $pupils): ?>
                                        <tr class="warning">
                                            <th scope="row" style="padding: 5px; height:35px;"><i class="fa fa-chevron-down fa-lg" onclick="$('.<?= $level_label ?>').fadeToggle(); $(this).toggleClass('fa-chevron-down'); $(this).toggleClass('fa-chevron-right')"></i> <?= $level_label ?></th>
                                        </tr>
                                        <?php foreach ($pupils as $pupil): ?>
                                            <tr class="<?= $level_label ?>">
                                                <th style="display: flex;" height="35px" scope="row" style="padding: 5px; height:35px;">
                                                    <button class="btn btn-default btn-xs"><i class="pupil-actions fa fa-user-times text-muted info" data-pupilid="<?= $pupil['id'] ?>" data-result="ne" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Marquer « non évalué » pour l'ensemble des competences de l'évaluation"></i></button>&nbsp;
                                                    <button class="btn btn-default btn-xs"><i class="pupil-actions fa fa-stethoscope text-muted info" data-pupilid="<?= $pupil['id'] ?>" data-result="abs" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Marquer « absent » pour l'ensemble des competences de l'évaluation"></i></button>&nbsp;&nbsp;
                                                    <div title="<?= $pupil['first_name'] ?> <?= $pupil['name'] ?>" style="white-space:nowrap; text-overflow: ellipsis; overflow:hidden;">
                                                        <?= $pupil['first_name'] ?> <?= $pupil['name'] ?>
                                                    </div>
                                                </th>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-10 col-xs-10" style="padding-left:0;">
                        <div class="table-responsive competences">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <?php foreach($competences as $item): ?>
                                        <th style="padding: 5px; height:35px; width:150px;" data-container="body" data-toggle="popover" title="Compétence n°<?= $item->EvaluationsCompetences['position'] ?>" data-trigger="hover" data-placement="bottom" data-content="<?= $item->Competences['title'] ?>">
                                            Compétence n°<?= $item->EvaluationsCompetences['position'] ?>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="danger">
                                    <?php foreach($competences as $item): ?>
                                        <td style="padding: 5px; height:35px; width:150px;">
                                            <div style="width:150px;" class="btn-group" role="group" aria-label="...">
                                                <button type="button" data-type="global" data-competenceid="<?= $item->Competences['id'] ?>" data-result="a" class="btn btn-xs btn-all info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer A (acquis) à toute la classe">A</button>
                                                <button type="button" data-type="global" data-competenceid="<?= $item->Competences['id'] ?>" data-result="b" class="btn btn-xs btn-all info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer B (à renforcer) à toute la classe">B</button>
                                                <button type="button" data-type="global" data-competenceid="<?= $item->Competences['id'] ?>" data-result="c" class="btn btn-xs btn-all info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer C (en cours d'acquisition) à toute la classe">C</button>
                                                <button type="button" data-type="global" data-competenceid="<?= $item->Competences['id'] ?>" data-result="d" class="btn btn-xs btn-all info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer D (non acquis) à toute la classe">D</button>
                                                <button type="button" data-type="global" data-competenceid="<?= $item->Competences['id'] ?>" data-result="ne" class="btn btn-xs btn-all info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer NE (non évalué) à toute la classe">NE</button>
                                            </div>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php foreach ($levelsPupils as $level_id => $levels): ?>
                                    <?php foreach ($levels as $level_label => $pupils): ?>
                                        <tr class="warning">
                                            <?php foreach($competences as $item): ?>
                                                <td style="padding: 5px; height:35px; width:150px;">
                                                    <div style="width:150px;" class="btn-group" role="group" aria-label="...">
                                                        <button type="button" data-type="global" data-competenceid="<?= $item->Competences['id'] ?>" data-result="a" data-levelid="<?= $level_id ?>" class="<?= $level_label ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer A (acquis) à tous les <?= $level_label ?>">A</button>
                                                        <button type="button" data-type="global" data-competenceid="<?= $item->Competences['id'] ?>" data-result="b" data-levelid="<?= $level_id ?>" class="<?= $level_label ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer B (à renforcer) à tous les <?= $level_label ?>">B</button>
                                                        <button type="button" data-type="global" data-competenceid="<?= $item->Competences['id'] ?>" data-result="c" data-levelid="<?= $level_id ?>" class="<?= $level_label ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer C (en cours d'acquisition) à tous les <?= $level_label ?>">C</button>
                                                        <button type="button" data-type="global" data-competenceid="<?= $item->Competences['id'] ?>" data-result="d" data-levelid="<?= $level_id ?>" class="<?= $level_label ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer D (non acquis) à tous les <?= $level_label ?>">D</button>
                                                        <button type="button" data-type="global" data-competenceid="<?= $item->Competences['id'] ?>" data-result="ne" data-levelid="<?= $level_id ?>" class="<?= $level_label ?> btn btn-xs btn-level info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Attribuer NE (non évalué) à tous les <?= $level_label ?>">NE</button>
                                                    </div>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                        <?php foreach ($pupils as $pupil): ?>
                                            <tr class="<?= $level_label ?>">
                                                <?php foreach($competences as $competence): ?>
                                                    <td style="padding: 5px; height:35px; width:150px;">
                                                        <div style="width:150px;" class="btn-group" role="group" aria-label="...">
                                                            <button data-levelid="<?= $level_id ?>"  data-pupilid="<?= $pupil['id'] ?>" data-result="a"  data-competenceid="<?= $competence->Competences['id'] ?>" id="<?= $competence->Competences['id'] ?>_<?= $pupil['id'] ?>_a" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Acquis">A</button>
                                                            <button data-levelid="<?= $level_id ?>" data-pupilid="<?= $pupil['id'] ?>" data-result="b" data-competenceid="<?= $competence->Competences['id'] ?>" id="<?= $competence->Competences['id'] ?>_<?= $pupil['id'] ?>_b" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="À renforcer">B</button>
                                                            <button data-levelid="<?= $level_id ?>" data-pupilid="<?= $pupil['id'] ?>" data-result="c" data-competenceid="<?= $competence->Competences['id'] ?>" id="<?= $competence->Competences['id'] ?>_<?= $pupil['id'] ?>_c" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="En cours d'acquisition">C</button>
                                                            <button data-levelid="<?= $level_id ?>" data-pupilid="<?= $pupil['id'] ?>" data-result="d" data-competenceid="<?= $competence->Competences['id'] ?>" id="<?= $competence->Competences['id'] ?>_<?= $pupil['id'] ?>_d" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Non acquis">D</button>
                                                            <button data-levelid="<?= $level_id ?>" data-pupilid="<?= $pupil['id'] ?>" data-result="ne" data-competenceid="<?= $competence->Competences['id'] ?>" id="<?= $competence->Competences['id'] ?>_<?= $pupil['id'] ?>_ne" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Non évalué">NE</button>
                                                            <button data-levelid="<?= $level_id ?>" data-pupilid="<?= $pupil['id'] ?>" data-result="abs" data-competenceid="<?= $competence->Competences['id'] ?>" id="<?= $competence->Competences['id'] ?>_<?= $pupil['id'] ?>_abs" type="button" class="btn btn-xs btn-default info" data-container="body" data-toggle="tooltip" data-placement="bottom" title="Absent">ABS</button>
                                                        </div>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>

<?php $this->start('script'); ?>
<script type="text/javascript">
    var results = <?= $jsonResults ?>;
    var colors = { "a" : "success", "b" : "info", "c": "warning", "d": "danger", "ne": "dark", "abs": "dark" };
    var baseURL =  $('#base_url').text();
    $( document ).ready(function() {
        for (var key in results) {
            var id = results[key].competence_id + '_' + results[key].pupil_id + '_' + results[key].result.toLowerCase();
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
        var competenceid = $(this).data("competenceid");
        var pupilid = $(this).data("pupilid");
        var levelid = $(this).data("levelid");
        var result = $(this).data("result");

        if(typeof pupilid === 'undefined' && typeof levelid === 'undefined'){
            //Modifier l'item pour la classe
            saveResultForSpecificCompetence(competenceid, result);
        } else if(typeof pupilid === 'undefined') {
            //Modifier l'item pour la niveau
            saveResultForSpecificCompetenceAndLevel(competenceid, levelid, result);
        } else {
            //Modifier l'item pour l'élève
            saveResultForSpecificCompetenceAndPupil(competenceid, pupilid, result);
        }
        $('.info').tooltip('hide');
    });

    $( "i.pupil-actions" ).click(function() {
        var pupilid = $(this).data("pupilid");
        var result = $(this).data("result");

        bootbox.confirm("Voulez-vous vraiment attribuer "+result.toUpperCase()+" à tous les compétences de cette évaluation pour cet élève ? <br />Les résultats qui auraient précédement été renseignés seront perdus.", function(res) {
            if (res == true) {
                var color = "btn-" + colors[result];
                $('#saving').show(); $('#saved').hide(); $('#error').hide();
                $.get( baseURL + "/results/setresultforspecificpupil/<?= $params['pass'][0] ?>/"+pupilid+"/"+result.toUpperCase(), function( data ){
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

    function saveResultForSpecificCompetence(competenceid, result){
        bootbox.confirm("Voulez-vous vraiment attribuer "+result.toUpperCase()+" à tous les élèves de la classe pour cet item ? <br />Les résultats qui auraient précédement été renseignés seront perdus.", function(res) {
            if (res == true) {
                var color = "btn-" + colors[result];
                $('#saving').show(); $('#saved').hide(); $('#error').hide();
                $.get( baseURL + "/results/setresultforspecificitem/<?= $params['pass'][0] ?>/"+competenceid+"/"+result.toUpperCase(), function( data ){
                    $('button[data-competenceid="'+competenceid+'"]')
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

    function saveResultForSpecificCompetenceAndLevel(competenceid, level, result){
        bootbox.confirm("Voulez-vous vraiment attribuer "+result.toUpperCase()+" à tous les élèves de ce niveau pour cet item ? <br />Les résultats qui auraient précédement été renseignés seront perdus.", function(res) {
            if (res == true) {
                var color = "btn-" + colors[result];
                $('#saving').show(); $('#saved').hide(); $('#error').hide();
                $.get( baseURL + "/results/setresultforspecificitemlevel/<?= $params['pass'][0] ?>/"+competenceid+"/"+level+"/"+result.toUpperCase(), function( data ){
                    $('button[data-competenceid="' + competenceid + '"]')
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

    function saveResultForSpecificCompetenceAndPupil(competenceid, pupilid, result){
        var color = "btn-" + colors[result];
        $('#saving').show(); $('#saved').hide(); $('#error').hide();
        $.get( baseURL + "/results/setresultforspecificitempupil/<?= $params['pass'][0] ?>/"+competenceid+"/"+pupilid+"/"+result.toUpperCase(), function( data ){
            $('button[data-competenceid="'+competenceid+'"]')
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

<?php $this->end();
