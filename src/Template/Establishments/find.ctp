<?php
$this->assign('header', 'Établissements');
$this->assign('description', '');
?>

<div class="row">
    <div class="col-md-6">
        <?php if (count($schools) === 0) : ?>
            <div class="box box-solid box-danger">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-fw fa-frown-o"></i> Oh oh, zut alors :'(</h3>
                </div>
                <div class="box-body">
                    <p>Nous n'avons trouvé aucun établissement pour ces critères.</p>
                    <p>Si vous aviez affiné votre recherche, essayez de relancer la recherche avec d'autres mots.</p>
                </div>
                <div class="box-footer">
                    <p>Si l'établissement est innexistant, vous pouvez demander une création.</p>
                    <?= $this->Html->link(
                        '<i class="fa fa-fw fa-bullhorn"></i> Demander une création',
                        'mailto:support@opencomp.fr',
                        [
                            'escape' => false,
                            'class' => 'btn btn-lg btn-default'
                        ]
                    ) ?>
                </div>
            </div>
        <?php else : ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-fw fa-map-marker"></i> Localisation géographique</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <div id="establishment-localization"></div>
                <style>
                    #establishment-localization { height: 500px; }
                </style>
                <?php $this->append('javascript'); ?>
                var mapDetail = L.map('establishment-localization').setView([0,0], 14);
                L.tileLayer('https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png', {
                attribution: 'Wikimedia Maps | Cartographie &copy; <a href="http://openstreetmap.org/copyright">contributeurs OpenStreetMap</a>',
                maxZoom: 18,
                }).addTo(mapDetail);
                var markers = [];
                <?php foreach ($schools as $school) : ?>
                    <?php if (isset($school->X) && isset($school->Y)) : ?>
                        var etab_<?= $school->id ?> = L.marker([<?= $school->lat() ?>, <?= $school->lgt() ?>]).bindPopup("<?= $school->main_naming ?> <?= $school->uai_patronym ?><br /><?= $school->address ?><p><a href=''#'>>> Rejoindre cet établissement</a></p>");
                        markers.push(etab_<?= $school->id ?>);
                    <?php endif; ?>
                <?php endforeach; ?>
                var group = new L.featureGroup(markers).addTo(mapDetail);
                mapDetail.fitBounds(group.getBounds());
                <?php $this->end(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-fw fa-search"></i> Affinez votre recherche</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <?= $this->Form->create(); ?>
                <div class="col-md-6">
                    <?= $this->Form->input('patronym', ['label' => 'Rechercher dans le nom...']); ?>
                </div>
                <div class="col-md-6">
                    <?= $this->Form->input('address', ['label' => 'Rechercher dans l\'adresse...']); ?>
                </div>
                <?= $this->Form->hidden('s'); ?>
                <?= $this->Form->submit('Affiner', ['style' => 'display:none;']); ?>
                <?= $this->Form->end(); ?>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-fw fa-list"></i> Résultats de votre recherche</h3>
            </div>
            <div class="box-body table-responsive no-padding">

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Code UAI</th>
                        <th>Établissement</th>
                        <th></th>
                    </tr>
                    <tbody>
                    <?php foreach ($schools as $school) : ?>
                        <tr>
                            <td width="10%"><?= $school->id; ?></td>
                            <td>
                                <?= $school->main_naming ?> <br /><?= $school->uai_patronym ?><br />
                                <?= $school->address ?>
                            </td>
                            <td>
                                <?= $this->Html->link('<i class="fa fa-fw fa-sign-in"></i> Rejoindre', '/establishments/join/' . $school->id, ['escape' => false]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>