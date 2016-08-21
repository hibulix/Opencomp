<div class="row">
    <div class="col-md-4 col-xs-2" style="padding-right:0;">
        <style>
            .competences td, .competences th{
                text-align: center;
                white-space: nowrap;
            }
        </style>
        <table style="table-layout: fixed;" class="table table-striped">
            <thead>
            <tr>
                <th style="white-space:nowrap; text-overflow: ellipsis; overflow:hidden; height:33px; padding: 5px;">
                    <span id="error" class="text-danger" style="display:none;" data-container="body" data-toggle="tooltip" data-placement="bottom" title=""><i class="fa fa-times"></i> <small>une erreur est survenue</small></span>
                    <span id="saved" class="text-success" style="display:none;"><i class="fa fa-floppy-o"></i> <small>résultats sauvegardés</small></span>
                    <span id="loaded" class="text-success" style="display:none;"><i class="fa fa-check"></i> <small>résultats chargés</small></span>
                    <span id="saving" class="text-muted" style="display:none;"><i class="fa fa-spinner fa-pulse"></i> <small>sauvegarde des résultats</small></span>
                    <span id="loading" class="text-muted"><i class="fa fa-spinner fa-pulse"></i> <small>chargement des résultats</small></span>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php /** @var array $levelsPupils */
            foreach ($levelsPupils as $levelId => $levels) : ?>
                <?php foreach ($levels as $levelLabel => $pupils) : ?>
                    <tr class="warning">
                        <th scope="row" style="padding: 5px; height:33px;"><i class="fa fa-chevron-down fa-lg" onclick="$('.<?= $levelLabel ?>').fadeToggle(); $(this).toggleClass('fa-chevron-down'); $(this).toggleClass('fa-chevron-right')"></i> <?= $levelLabel ?></th>
                    </tr>
                    <?php foreach ($pupils as $pupil) : ?>
                        <tr class="<?= $levelLabel ?>">
                            <th style="display: flex; padding:5px;" height="33px" scope="row">
                                <div id="<?= $pupil['id'] ?>" title="<?= $pupil['first_name'] ?> <?= $pupil['name'] ?>" style="white-space:nowrap; text-overflow: ellipsis; overflow:hidden;">
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

    <div class="col-md-8 col-xs-8" style="padding-left:0;">
        <div class="table-responsive competences">
            <table class="table table-striped">
                <thead>
                <tr>
                    <?php foreach ($competences as $competence) : ?>
                        <th style="padding: 5px; height:33px; " data-container="body" data-toggle="popover" title="Compétence n°<?= $competence->EvaluationsCompetences['position'] ?>" data-trigger="hover" data-placement="bottom" data-content="<?= $competence->Competences['title'] ?>">
                            C<?= $competence->EvaluationsCompetences['position'] ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($levelsPupils as $levelId => $levels) : ?>
                    <?php foreach ($levels as $levelLabel => $pupils) : ?>
                        <tr class="warning">
                            <?php foreach ($competences as $competence) : ?>
                                <td style="padding: 5px; height:33px; ">
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php foreach ($pupils as $pupil) : ?>
                            <tr class="<?= $levelLabel ?>">
                                <?php foreach ($competences as $competence) : ?>
                                    <td style="padding: 5px; height:33px; ">
                                        <span id="<?= $competence->Competences['id'] ?>_<?= $pupil->id ?>" class="badge"></span>
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