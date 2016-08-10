<?php
$editLink = $this->AuthLink->link(' <i class="fa fa-pencil"></i> ', '/evaluations/edit/'.$evaluation->id, array('escape' => false));
$classroomLink = $this->AuthLink->link($evaluation->classroom->title, '/classrooms/viewtests/'.$evaluation->classroom->id, array('escape' => false));

$this->assign('header', $evaluation->title.$editLink    );
$this->assign('description', $classroomLink);
?>

<?= /** @var \App\Model\Table\EvaluationsTable $evaluation */
$this->cell('Test::header', [$evaluation->id]); ?>

<div class="row">
    <div class="col-md-6">
        <div id="pupil-input" class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Saisir ou modifier les résultats</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body text-center">
				<div class="row">
    				<div class="col-md-6">
						<?php $barcode = $this->Url->build([
							'controller' => 'Results',
							'action' => 'add',
							$evaluation->id
						]); ?>
						<a class="btn btn-app" href="<?= $barcode ?>">
							<i class="fa fa-barcode"></i> Saisir avec des codes à barres
						</a>
					</div>
					<div class="col-md-6">
						<?php $manual = $this->Url->build([
							'controller' => 'Results',
							'action' => 'global',
							$evaluation->id
						]); ?>
						<a class="btn btn-app" href="<?= $manual ?>">
							<i class="fa fa-mouse-pointer"></i> Saisir à la souris
						</a>
					</div>
				</div>
			</div>
            <!-- /.box-body -->
        </div>
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<i class="fa fa-question"></i>
				<h3 class="box-title">Aide</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
				<!-- /.box-tools -->
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				The body of the box
			</div>
			<!-- /.box-body -->
		</div>
    </div>
	<div class="col-md-6">
		<div id="pupil-input" class="box box-default">
			<div class="box-header with-border">
				<i class="fa fa-dashboard"></i>
				<h3 class="box-title">Suivi de la saisie</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body no-padding text-center">
				<?php echo $this->element('TestResults', [
					compact('competences','levelsPupils')
				]); ?>
			</div>
			<!-- /.box-body -->
		</div>
	</div>
</div>

<?php $this->start('script'); ?>
	<script src="/js/opencomp.results.add.min.js"></script>
	<script type="text/javascript">
		var baseURL =  $('#base_url').text();
		evaluation = <?= json_encode($evaluation); ?>;
		$.get(baseURL + "results/evaluation/" + <?= $evaluation->id ?> + ".json" , function( data ) {
			loadResults(data)
		});
	</script>
<?php $this->end(); ?>