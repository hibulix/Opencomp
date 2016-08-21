<?php
$editLink = $this->AuthLink->link(' <i class="fa fa-pencil"></i> ', '/evaluations/edit/'.$evaluation->id, array('escape' => false));
$classroomLink = $this->AuthLink->link($evaluation->classroom->title, '/classrooms/tests/'.$evaluation->classroom->id, array('escape' => false));

$this->assign('header', $evaluation->title.$editLink    );
$this->assign('description', $classroomLink);
?>

<?= $this->cell('Test::header', [$evaluation->id]); ?>

<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Elèves évalués</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <?php $first = true; ?>
                        <?php foreach ($levelsPupils as $level_id => $levels): ?>
                            <?php foreach ($levels as $level_label => $pupil): ?>
                                <li class="<?= ($first==true)? 'active' : ''?>"><a href="#level_<?= $level_id ?>" data-toggle="tab"><?= $level_label ?></a></li>
                            <?php endforeach; ?>
                            <?php $first = false; ?>
                        <?php endforeach; ?>
                    </ul>
                    <div class="tab-content" style="column-count:2; max-height: 200px; overflow:auto;">
                        <?php $first = true; ?>
                        <?php foreach ($levelsPupils as $level_id => $levels): ?>
                            <?php foreach ($levels as $level_label => $pupils): ?>
                                <div class="tab-pane <?= ($first==true)? 'active' : ''?>" id="level_<?= $level_id ?>">
                                    <ul>
                                        <?php foreach ($pupils as $pupil): ?>
                                            <li><?= $pupil['first_name'] ?> <?= $pupil['name'] ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                            <?php $first = false; ?>
                        <?php endforeach; ?>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <i class="fa fa-calendar"></i>
                <h3 class="box-title">Période de l'évaluation</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?= $evaluation->period->well_named ?>
            </div>
        </div>
        <div class="box box-default">
            <div class="box-header with-border">
                <i class="fa fa-share-alt"></i>
                <h3 class="box-title">Propriétaire et partages</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    <?php foreach ($evaluation->users as $user): ?>
                        <li class="item">
                            <div class="product-img">
                                <img class="image-circle" src="<?= "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) ).'?d='.urlencode('https://api.adorable.io/avatars/100/'.$user->id.'.png'); ?>" alt="Product Image">
                            </div>
                            <div class="product-info">
                                <?= $user->full_name ?>
                                <span class="product-description">
                          <?= $user->_joinData->ownership ?>
                        </span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    <!-- /.item -->
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>