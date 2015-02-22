#### Métriques <sub>[ [Qualité](http://fr.wikipedia.org/wiki/Qualit%C3%A9_logicielle), [Intégration continue](http://fr.wikipedia.org/wiki/Intégration_continue), [Dépendances](http://fr.wikipedia.org/wiki/D%C3%A9pendance_logicielle) ]</sub>

[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/Opencomp/Opencomp.svg?style=flat)](https://scrutinizer-ci.com/g/jtraulle/Opencomp/) [![Code Climate](https://codeclimate.com/github/Opencomp/Opencomp/badges/gpa.svg)](https://codeclimate.com/github/Opencomp/Opencomp) [![Codacy Badge](https://www.codacy.com/project/badge/13218f52491d4052a156883182019b9a)](https://www.codacy.com/public/jtraulle/Opencomp) [![SensioLabs Insight](https://img.shields.io/sensiolabs/i/799f30c2-873c-4226-901c-98951ba5ff33.svg?style=flat)](https://insight.sensiolabs.com/projects/799f30c2-873c-4226-901c-98951ba5ff33) [![Build Status](https://travis-ci.org/Opencomp/Opencomp.svg?branch=develop)](https://travis-ci.org/jtraulle/Opencomp) [![Dependency Status](https://gemnasium.com/Opencomp/Opencomp.svg)](https://gemnasium.com/jtraulle/Opencomp)


----

Opencomp souhaite proposer aux enseignants du primaire qui évaluent les élèves selon l’acquisition de compétences une solution simple, rapide et fiable pour leur permettre de générer aisément les bulletins de leurs élèves.

----

Installation
------------

**_<pre>/!\ Ce logiciel étant actuellement en développement actif, il peut être instable.</pre>_**

*Notez que ces instructions sont principalement a destination des développeurs et des utilisateurs avertis et bidouilleurs. Si vous souhaitez simplement utiliser Opencomp mais que vous éprouvez des difficultés d'installation, sachez que des paquets prêts à l'emploi seront prochainement disponibles. Dans l'intervale, vous pouvez [me contacter](http://blog.opencomp.fr/nous-contacter/).*

### 1. Télécharger, décompresser, configurer Apache

* [Téléchargez la dernière version du script](https://codeload.github.com/jtraulle/Opencomp/zip/develop).
* Décompressez et transférez le dossier sur votre serveur web.
* Assurez vous que le Module de réécriture d'URL Apache (mod_rewrite) est activé sur votre serveur

### 2. Installer les dépendances backend (Composer)

*Il s'agit des librairies PHP sur lesquelles repose Opencomp pour fonctionner*

* Téléchargez le gestionnaire de dépendances backend Composer `curl -sS https://getcomposer.org/installer | php`
* Récupérez l'ensemble des dépendances en exécutant `php composer.phar install`

### 3. Installer les dépendances frontend (Bower)

*Il s'agit des librairies Javascript et CSS utilisées pour l'interactivité et les styles de l'application*

* Téléchargez et installez Node pour votre Système d'exploitation depuis http://nodejs.org/download/
* Téléchargez le gestionnaire de dépendances frontend Bower à l'aide de npm `npm install -g bower`
* Récupérez l'ensemble des dépendances en exécutant `bower install`

### 4 . Installer le serveur de file d'attente de message (Beanstalkd)

*Les générations de PDF étant très consommatrices de ressources, elles sont gérées par l'intermédiaire du serveur de file d'attente de message **beanstalkd** *.

* Installez **beanstalkd** avec
    * GNU/Linux basé Debian `apt-get install beanstalkd`
    * OS X `brew install beanstalkd`


* Installez **supervisord** pour pouvoir gérer le démon permettant de générer les PDF en tant que service :
    * `easy_install supervisor` (pour fonctionner, la commande `easy_install` nécessite l'installation préalable de Python) ...
    * éditez le fichier de configuration de **supervisord** `nano /etc/supervisor/supervisord.conf`
    * ajoutez les lignes suivantes
    ```txt
    [program:opencomp-worker]
    command=/var/www/répertoireDinstallationOpencomp/Console/cake generatepupilreport
    autostart=true
    autorestart=true
    ```
    * démarrez le service Opencomp `supervisorctl start opencomp-worker`

### 5. Créer et configurer la base de données

* Créer une base de donnée MySQL en important les dumps SQL `struct.sql` et `data.sql` présents dans le répertoire `app/Model/Datasource/` du dossier téléchargé.
* Éditez les informations de connexion à la base de données MySQL présentes dans le fichier `app/Config/database.php` (lignes 62 et suivantes).

### 6. Profitez !

* Accédez à votre serveur web, les identifiants par défaut sont admin/admin.
* Rapportez vos suggestions et avis sur [le gestionnaire de demandes du projet](http://projets.opencomp.fr/opencomp/issues/new).

----

Ceux qui le souhaitent peuvent me remercier via Flattr :blush:

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=jtraulle&url=https://github.com/jtraulle/Opencomp&title=Opencomp&language=php&tags=github&category=software)

----

Licence
-------

<pre>Ce programme est distribué dans l'espoir qu'il sera utile, mais SANS AUCUNE GARANTIE ;
sans même la garantie implicite de COMMERCIALISATION ou D’ADAPTATION A UN OBJET PARTICULIER.

Pour plus d'informations, reportez vous au fichier LICENCE.txt de l'archive.</pre>

**Opencomp est distribué sous licence _GNU Affero General Public Licence v3_**

>La licence initiale Affero GPL était destinée à assurer aux utilisateurs d'une application web un accès à ses sources. L'Affero GPL version 3 étend cet objectif : elle s'applique à tous les logiciels en réseaux, donc elle s'applique bien aussi aux programmes comme les serveurs de jeux. Les termes supplémentaires sont aussi plus flexibles, donc si quelqu'un utilise des sources sous AGPL dans un programme sans interface réseau, il n'aurait qu'à fournir les sources de la même façon que dans la GPL. En rendant les deux licences compatibles, les développeurs de logiciels seront en mesure de renforcer leur gauche d'auteur tout en capitalisant sur les portions de code mûres à leur disposition sous licence GPL. <br />(_D'après http://www.gnu.org/licenses/quick-guide-gplv3.fr.html_)
