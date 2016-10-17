<?php
$this->assign('header', 'Référentiels');
$this->assign('description', 'Instructions officielles');
?>

<?php foreach ($repositories as $repository) : ?>

    <div class="col-md-4">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-<?= $repository->color ?>">
                <h3 class="widget-user-username"><?= $repository->title ?></h3>
                <h5 class="widget-user-desc"><?= $repository->legislation_id ?></h5>
            </div>
            <div class="widget-user-image">
                <i style="background-color: white; width: 90px; height: 90px; padding: 17px;" class="text-muted img-circle fa fa-4x fa-book"></i>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-6 border-right">
                        <div class="description-block">
                            <a class="description-header" target="_blank" href="<?= $repository->legislation->url_pdf ?>">
                                <i class="fa fa-fw fa-file-pdf-o"></i> Télécharger le PDF
                            </a>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-sm-6">
                        <div class="description-block">
                            <h5 class="description-header"></h5>
                            <?= $this->Html->link(
                                '<i class="fa fa-fw fa-list"></i> Voir dans Opencomp',
                                ['action' => 'view', $repository->id],
                                ['class' => 'description-header', 'escape' => false]
                            ); ?>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.widget-user -->
    </div>

<?php endforeach; ?>
