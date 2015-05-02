<!DOCTYPE html>
<html lang="fr">
<head>
    <?php echo $this->Html->charset(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo 'Opencomp | '.$title_for_layout; ?> </title>
    <?php
    echo $this->Html->meta('icon');
    echo $this->Html->css(array(
        '../components/bootstrap/dist/css/bootstrap.min',
        '../components/bootstrap/dist/css/bootstrap-theme.min',
        '../components/fontawesome/css/font-awesome.min',
        'opencomp.auth'
    ));

    echo $this->Html->script('../components/jquery/dist/jquery.min');

    ?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Afficher le menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="navbar-brand">Opencomp <sub>βeta</sub></span>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
            	<li class="dropdown">
                   <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-support"></i> <?php echo __('Aide et support') ?> <b class="caret"></b></a>

            	<ul class="dropdown-menu">
                	<li><a href="http://kb.opencomp.fr" target="_blank"><i class="fa fa-book"></i> <?php echo __('Chercher une solution dans la base de connaissance') ; ?></a></li>
                	<li><a href="http://projets.opencomp.fr/opencomp/issues/new" target="_blank"><i class="fa fa-bug"></i> <?php echo __('Signaler une anomalie, demander une fonctionnalité') ; ?></a></li>
            	</ul>
            	</li>
            </ul>
        </div><!-- /.nav-collapse -->
    </div><!-- /.container -->
</div>

<div class="container">
    <?php echo $this->Html->image("opencomp.png", array("alt" => "Opencomp logo", "class"=>"center hidden-xs")); ?>
    <div class="spacer"></div>
    <h3 class="center">Merci de vous identifier</h3>
    <div class="form-signin"><?= $this->Flash->render() ?></div>
    <?php echo $this->fetch('content'); ?>
</div> <!-- /container -->

<?php
echo $this->Html->script('../components/bootstrap/dist/js/bootstrap.min');
echo $this->fetch('script');
?>
</body>
</html>
