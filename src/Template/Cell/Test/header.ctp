<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'pupils') ? 'bg-green' : '' ?>">
            <span class="info-box-icon <?= ($action == 'pupils') ? '' : 'bg-green' ?>">
                <?= $this->Html->link('<i class="fa fa-group "></i>', '/evaluations/pupils/'.$evaluation->id, array('escape' => false,'style'=>'color:inherit;')); ?>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Elèves(s) évalué(s)</span>
                <span class="info-box-number">6</span>
                <?php if($action !== 'pupils'): ?>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                        <i class="fa fa-chevron-right"></i> voir les infos générales
                    </span>
                <?php endif; ?>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'items') ? 'bg-yellow' : '' ?>">
            <span class="info-box-icon <?= ($action == 'items') ? '' : 'bg-yellow' ?>">
                <?= $this->Html->link('<i class="fa fa-list-ul "></i>', '/evaluations/competences/'.$evaluation->id, array('escape' => false,'style'=>'color:inherit;')); ?>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Connaissances & compétences</span>
                <span class="info-box-number">6</span>
                <?php if($action !== 'items'): ?>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                        <i class="fa fa-chevron-right"></i> définir les items
                    </span>
                <?php endif; ?>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'results') ? 'bg-blue' : '' ?>">
            <span class="info-box-icon <?= ($action == 'results') ? '' : 'bg-blue' ?>">
                <?= $this->Html->link('<i class="fa fa-mouse-pointer "></i>', '/evaluations/results/'.$evaluation->id, array('escape' => false,'style'=>'color:inherit;')); ?>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Saisie des résultats</span>
                <span class="info-box-number">12% effectué</span>
                <?php if($action === 'results'): ?>
                <div class="progress">
                    <div class="progress-bar" style="width: 12%"></div>
                </div>
                <?php else: ?>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                        <i class="fa fa-chevron-right"></i> saisir les résulats
                    </span>
                <?php endif; ?>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'insights') ? 'bg-maroon' : '' ?>">
            <span class="info-box-icon <?= ($action == 'insights') ? '' : 'bg-maroon' ?>">
                <?= $this->Html->link('<i class="fa fa-pie-chart "></i>', '/evaluations/insights/'.$evaluation->id, array('escape' => false,'style'=>'color:inherit;')); ?>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Analyse des résultats</span>
                <span class="info-box-number text-muted">Non disponible</span>
                <?php if($action === 'insights'): ?>
                    <div class="progress">
                        <div class="progress-bar" style="width: 12%"></div>
                    </div>
                <?php else: ?>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                        <i class="fa fa-chevron-right"></i> analyser les résultats
                    </span>
                <?php endif; ?>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>