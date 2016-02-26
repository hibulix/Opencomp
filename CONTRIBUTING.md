# Comment contribuer au projet ?

Dans tout projet, certaines règles sont à respecter _**impérativement**_.

Si vous souhaitez contribuer au développement d'Opencomp :

* __N'utilisez pas de tabulations__.
* En substitution des tabulations, utilisez des espaces (une tabulation correspondant à 4 espaces).
* Indentez correctement le code
* Pour chaque fichier __.php__, incluez un docblock (voir les fichiers déjà commités).
* Pour chaque __classe__ contenue dans un fichier __.php__, incluez un docblock (voir les fichiers déjà commités).
* Pour chaque __fonction__ contenue dans un fichier __.php__, incluez un docblock (voir les fichiers déjà commités).
* Les accolades ouvrantes des classes et des fonctions doivent systématiquement être positionnées sur une nouvelle ligne.
* Dans une fonction contenant plusieurs arguments, vous devez ajouter un espace après la virgule.
* N'utilisez que des simple quotes (__'__...__'__)
* Les structures conditionnelles if ... else doivent se présenter ainsi :

<pre>
if ($x == $y) {
    ...
} else {
    ...
}
</pre>

# Comment commenter le code du projet ?
Afin que la collaboration entre les différents développeurs puisse s'effectuer au mieux, il est important de commenter convenablement l'ensemble des portions de codes ajoutées ;)

Dans ce but, on mettra en oeuvre la syntaxe PhpDoc.

## Syntaxe des blocs de commentaires en haut de chaque classe (de type Model ou Controller).

*En haut de chaque classe (modèle, contrôleur), il est nécessaire d'ajouter un bloc de commentaire de ce type :*

    /**
     * Contrôleur de gestion des élèves
     *
     * @category	Controller
     * @package 	Opencomp
     * @version 	1.0
     * @author		Jean Traullé <jtraulle@gmail.com>
     * @license  	http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
     * @link     	http://www.opencomp.fr
     */

`@package`, `@version`, `@licence`, `@link` ne devront pas être modifiées durant toute la période de développement initial de l'application.

Il reste donc à renseigner en première ligne l’utilité principale de la classe, la catégorie (Model, View ou Controller) précédée de `@category` ainsi que l'auteur ayant rédigé le code de cette classe précédé de `@author`.

Dans le cas ou plusieurs auteurs auraient collaboré, ajouter autant de ligne `@author` que de personnes ayant contribué. 

## Syntaxe des blocs précédant chaque fonction.

*Au dessus de chaque fonction, il est nécessaire d'ajouter un bloc de commentaire de ce type :*

    /**
	 * Méthode affichant les détails d'un élève et les élèves de cette classe.
	 *
	 * @return void
	 * @param int $id
	 * @access public
	 */

La première ligne est capitale puisque elle indique la fonction première de la fonction. Elle doit donc être claire et précise.

`@return` permet d'indiquer le type que renvoie la fonction (la plupart du temps avec CakePHP, les fonctions ne retournent rien et on indique donc `void` (vide)).

`@param` permet d'indiquer le type de chaque variable passée en paramètre. On indique donc le type (très souvent, on passe un id, donc une valeur de type numérique, donc un int) correspondant à une variable (par exemple $id).
Dans le cas ou la fonction n'admet aucun paramètre, on ajoutera pas cette ligne de commentaire.

`@access` sera toujours `public`, du moins avec la version 1.3 de CakePHP.