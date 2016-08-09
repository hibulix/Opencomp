<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title_for_layout ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/components/AdminLTE/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/components/AdminLTE/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="/components/animate.css/animate.min.css">
    <link rel="stylesheet" href="/components/sweetalert/dist/sweetalert.css">
    <link rel="stylesheet" href="/components/AdminLTE/dist/css/AdminLTE.min.css">


    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="/components/AdminLTE/dist/css/skins/skin-purple.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/leaflet@1.0.0-rc.2/dist/leaflet.css" />
    <link rel="stylesheet" href="/css/opencomp.app.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->
</head>
<body class="hold-transition skin-purple sidebar-mini">
<span id="base_url" hidden><?php echo $this->Url->build('/', true); ?></span>
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>Oc</b> β</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Opencomp</b> <sub>βeta</sub></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <?php if ($this->request->session()->check('Auth.User')): ?>
                    <!-- Notifications Menu -->
                    <li class="dropdown notifications-menu">
                        <!-- Menu toggle button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 10 notifications</li>
                            <li>
                                <!-- Inner Menu: contains the notifications -->
                                <ul class="menu">
                                    <li><!-- start notification -->
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                        </a>
                                    </li>
                                    <!-- end notification -->
                                </ul>
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="<?= "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $this->request->session()->read('Auth.User.email') ) ) ).'?d='.urlencode('https://api.adorable.io/avatars/100/'.$this->request->session()->read('Auth.User.id').'.png'); ?>" class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?= $this->request->session()->read('Auth.User.first_name').' '.$this->request->session()->read('Auth.User.last_name'); ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="<?= "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $this->request->session()->read('Auth.User.email') ) ) ).'?d='.urlencode('https://api.adorable.io/avatars/100/'.$this->request->session()->read('Auth.User.id').'.png'); ?>" class="img-circle" alt="User Image">

                                <p>
                                    Alexander Pierce - Web Developer
                                    <small>Member since Nov. 2012</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Mon compte</a>
                                </div>
                                <div class="pull-right">
                                    <a href="#" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Se déconnecter</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <li class="header">PARAMÈTRES</li>
                <!-- Optionally, you can add icons to the links -->
                <li <?= ($params['controller'] == 'Establishments') ? 'class="active"' : ''; ?>><?php echo $this->AuthLink->link('<i class="fa fa-home"></i> <span>Établissements</span>', '/establishments', array('escape' => false)); ?></li>
                <li <?= ($params['controller'] == 'Users') ? 'class="active"' : ''; ?>><?php echo $this->Html->link('<i class="fa fa-group"></i> <span>Utilisateurs</span>', '/users', array('escape' => false)); ?></li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-cogs"></i> <span>Nomenclatures</span> <i class="fa fa-angle-right pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="#"><i class="fa fa-object-group"></i> Cycles</a></li>
                        <li><a href="#"><i class="fa fa-list-ol"></i> Niveaux</a></li>
                        <li class="active"><a href="#"><i class="fa fa-graduation-cap"></i> <span>Académies</span></a></li>
                        <li><a href="#"><i class="fa fa-globe"></i> Communes</a></li>
                        <li><a href="#"><i class="fa fa-balance-scale"></i> Référentiels</a></li>
                        <li><a href="#"><i class="fa fa-cloud-download"></i> Import depuis Etalab</a></li>
                    </ul>
                </li>
                <li class="header">MES CLASSES</li>
                <!-- Optionally, you can add icons to the links -->
                <li><a href="#"><i class="fa fa-line-chart"></i> <span>Mon bureau</span></a></li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-briefcase"></i> <span>CE2 &nbsp;<sub>Ecole Edmond Marquis</sub></span> <i class="fa fa-angle-right pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="#"><i class="fa fa-child"></i> <span>Elèves</span></a></li>
                        <li><a href="#"><i class="fa fa-file-text-o"></i> <span>Evaluations</span></a></li>
                        <li><a href="#"><i class="fa fa-ban"></i> <span>Items non-évalués</span></a></li>
                        <li><a href="#"><i class="fa fa-file-pdf-o"></i> <span>Bulletins</span></a></li>
                    </ul>
                </li>

            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?= $this->fetch('header') ?>
                <small><?= $this->fetch('description') ?></small>
            </h1>
            <?php if($this->fetch('breadcrumb')): ?>
                <ol class="breadcrumb">
                    <?= $this->fetch('breadcrumb') ?>
                </ol>
            <?php endif; ?>
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
        </section>

        <!-- Main content -->
        <section class="content">

            <?= $this->fetch('content') ?>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Lead Dev. <a tabindex="-1" href="https://github.com/jtraulle">Jean Traullé</a>, 2010-<?php echo date('Y');?>
        </div>
        <!-- Default to the left -->
        <a tabindex="-1" href="http://opencomp.fr">Opencomp</a> est <a tabindex="-1" href="https://github.com/jtraulle/Opencomp">publié</a> sous licence <a tabindex="-1" href="http://www.gnu.org/licenses/agpl-3.0-standalone.html">GNU AGPL v3</a>. <sub><em>En utilisant le logiciel, vous acceptez les termes de la licence.</em></sub>
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.0 -->
<script src="/components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/components/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="/components/AdminLTE/dist/js/app.min.js"></script>
<script src="https://npmcdn.com/leaflet@1.0.0-rc.2/dist/leaflet.js"></script>
<script src="/components/AdminLTE/plugins/select2/select2.min.js"></script>
<script src="/components/AdminLTE/plugins/select2/i18n/fr.js"></script>
<script src="/components/bootbox.js/bootbox.js"></script>
<script src="/components/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
    <?= $this->fetch('javascript') ?>
</script>
<?= $this->fetch('script') ?>

</body>
</html>
