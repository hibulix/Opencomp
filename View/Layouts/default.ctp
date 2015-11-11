<!DOCTYPE html>
<html lang="fr">
  <head>
    <?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout.' | Opencomp'; ?>
	</title>

    <?php
		echo $this->Html->css(array(
            '../components/bootstrap/dist/css/bootstrap.min',
            '../components/bootstrap/dist/css/bootstrap-theme.min',
            '../components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min',
            '../components/fontawesome/css/font-awesome.min',
            '../components/chosen/chosen.min',
            'chosen.bootstrap.css',
            '../components/jstree/dist/themes/default/style.min',
		    'opencomp.app'
        ));

		echo $this->fetch('meta');
		echo $this->fetch('css');

		echo $this->Html->script('../components/jquery/dist/jquery.min');
	?>

    <link href='https://fonts.googleapis.com/css?family=Titillium+Web|Open+Sans:300' rel='stylesheet' type='text/css'>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav icon -->
    <?php echo $this->Html->meta('icon'); ?>

    <!-- start Mixpanel -->
    <script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
        for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f)}})(document,window.mixpanel||[]);
        mixpanel.init("0b927716f255a2354e51c5ab603f7fa4");
        mixpanel.identify("<?php echo $this->Session->read('Auth.User.id') ?>");
        mixpanel.people.set({
            $email: "<?php echo $this->Session->read('Auth.User.email') ?>",
            $first_name: "<?php echo $this->Session->read('Auth.User.first_name') ?>",
            $last_name: "<?php echo $this->Session->read('Auth.User.name') ?>"
        });
    </script>
    <!-- end Mixpanel -->

  </head>

  <body>
    <span id="base_url" hidden><?php echo $this->Html->url('/', true); ?></span>
	<div id="wrap">

        <div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Afficher le menu</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php echo $this->Html->link('Opencomp <sub>βeta</sub>', '/dashboard', array(
                        'class' => 'navbar-brand info',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'bottom',
                        'data-original-title' => 'Cliquez pour afficher le bureau',
                        'escape' => false
                    )); ?>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                    	<li><?php echo $this->Html->link('<i class="fa fa-lg fa-home"></i> '.__('Mes classes'), '/dashboard', array('escape' => false)); ?></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-lg fa-list"></i> <?php echo __('Référentiels') ?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><?php echo $this->Html->link('<i class="fa fa-book"></i> '.__('Instructions officielles'), '/competences', array('escape' => false)); ?></li>
                                <li><?php echo $this->Html->link('<i class="fa fa-book"></i> '.__('Livret Personnel de Compétences'), '/lpcnodes', array('escape' => false)); ?></li>

                            </ul>
                            </li>
                            <?php if(AuthComponent::user('role') === 'admin'){ ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-lg fa-rocket"></i> <?php echo __('Administration') ; ?> <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><?php echo $this->Html->link('<i class="fa fa-home"></i> '.__('Gestion des établissements'), '/academies', array('escape' => false)); ?></li>
                                    <li><?php echo $this->Html->link('<i class="fa fa-user"></i> '.__('Gestion des utilisateurs'), '/users', array('escape' => false)); ?></li>
                                    <li><?php echo $this->Html->link('<i class="fa fa-cogs"></i> '.__('Paramètres de l\'application'), '/settings/', array('escape' => false)); ?></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-lg fa-support"></i> <?php echo __('Aide et support') ?> <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><?php echo $this->Html->link('<i class="fa fa-book"></i> '.__('Base de connaissance et support email'), '/pages/help', array('escape' => false)); ?></a></li>
                                    <li><?php echo $this->Html->link('<i class="fa fa-comment"></i> '.__('Obtenir une assistance en direct'), '/pages/livechat', array('escape' => false)); ?></a></li>
                                    <li><?php echo $this->Html->link('<i class="fa fa-lightbulb-o"></i> '.__('Partager une idée'), '/pages/idea', array('escape' => false)); ?></a></li>
                                </ul>
                            </li>
                            <?php } ?>

                    </ul>
                    <?php $new_notifications = ($this->Session->read('Auth.User.new_notifications') == true) ? 'color:gold;' : ''; ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><?php echo $this->Html->link('<i class="fa fa-lg fa-bell"></i>', '/dashboard/changelog', array(
                            'class' => 'info',
                            'style' => $new_notifications,
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'bottom',
                            'data-original-title' => 'voir les nouveautés',
                            'escape' => false
                        )); ?></li>
                        <li class="dropdown">
                            <?php $first_name = $this->Session->read('Auth.User.first_name');
                            $name = $this->Session->read('Auth.User.name'); ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo($first_name.' '.$name); ?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li class="disabled"><?php echo $this->Html->link('<i class="fa fa-edit"></i> '.__('Mon compte (bientôt)'), '', array('escape' => false)); ?></li>
                                <li><?php echo $this->Html->link('<i class="fa fa-sign-out"></i> '.__('Se déconnecter'), '/settings/save', array('escape' => false)); ?></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.nav-collapse -->
            </div><!-- /.container -->
        </div>

    <div class="container">
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>
		<div id="push"></div>
    </div> <!-- /container -->
    </div>
	<div id="footer">
      <div class="container">
        <p class="muted credit"><a href="http://opencomp.fr">Opencomp</a> est <a href="https://github.com/jtraulle/Opencomp">publié</a> sous licence <a href="http://www.gnu.org/licenses/agpl-3.0-standalone.html">GNU AGPL v3</a>. <sub><em>En utilisant le logiciel, vous acceptez les termes de la licence.</em></sub><span class="pull-right">Lead Dev. <a href="https://github.com/jtraulle">Jean Traullé</a>, 2010-<?php echo date('Y');?></span></p>

      </div>
    </div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <?php
    	echo $this->Html->script(array(
            '../components/jstree/dist/jstree.min',
            '../components/chosen/chosen.jquery.min',
            '../components/bootstrap/dist/js/bootstrap.min',
            '../components/bootstrap-datepicker/js/bootstrap-datepicker',
            '../components/bootstrap-datepicker/js/locales/bootstrap-datepicker.fr',
            '../components/bootstrap-filestyle/src/bootstrap-filestyle',
            'opencomp.app'
        ));
	    echo $this->fetch('script');
    ?>
  </body>
</html>
