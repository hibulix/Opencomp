

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'view') ? 'bg-blue' : '' ?>">
            <span class="info-box-icon <?= ($action == 'view') ? '' : 'bg-blue' ?>" style="opacity: 1">
                <?= $this->Html->link('<i class="fa fa-child"></i>','/classrooms/view/'.$classroom_id,['style'=>'color:white;','escape'=>false]); ?>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Élèves</span>
                <span class="info-box-number"><?= $pupils ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'viewtests') ? 'bg-green' : '' ?>">
            <span class="info-box-icon <?= ($action == 'viewtests') ? '' : 'bg-green' ?>">
                <?= $this->Html->link('<i class="fa fa-file-text-o"></i>','/classrooms/viewtests/'.$classroom_id,['style'=>'color:white;','escape'=>false]); ?>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Évaluations</span>
                <span class="info-box-number">
                    <?= $this->Html->link($evaluations,'/classrooms/viewtests/'.$classroom_id,['style'=>'color:inherit;']); ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'viewunrateditems') ? 'bg-yellow' : '' ?>">
            <span class="info-box-icon <?= ($action == 'viewunrateditems') ? '' : 'bg-yellow' ?>">
                 <?= $this->Html->link('<i class="fa fa-check-square-o"></i>','/classrooms/viewunrateditems/'.$classroom_id,['style'=>'color:white;','escape'=>false]); ?>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Items non-évalués</span>
                <span class="info-box-number"><?= $unrated_items ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'viewreports') ? 'bg-red' : '' ?>">
            <span class="info-box-icon <?= ($action == 'viewreports') ? '' : 'bg-red' ?>">
                <?= $this->Html->link('<i class="fa fa-file-pdf-o"></i>','/classrooms/viewreports/'.$classroom_id,['style'=>'color:white;','escape'=>false]); ?>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Bulletin(s)</span>
                <span class="info-box-number"><?= $reports ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
