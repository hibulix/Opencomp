<?php

/**
  * tutors_controller.php
  * 
  * PHP version 5
  *
  * @category Controller
  * @package  Opencomp
  * @author   Jean Traullé <jtraulle@gmail.com>
  * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
  * @link     http://www.opencomp.fr
  */

/**
 * Contrôleur de gestion des responsables légaux.
 *
 * @category Controller
 * @package  Opencomp
 * @author   Jean Traullé <jtraulle@gmail.com>
 * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
 * @link     http://www.opencomp.fr
 */
class TutorsController extends AppController
{
    /**
    * Méthode permettant de visionner l'ensemble des responsables légaux.
    * 
    * @return void
    * @access public
    */
    function index()
    {
        $this->set('title_for_layout', __('Liste des Tuteurs', true));
        $this->set('tutors', $this->paginate());
    }
    
    /**
    * Méthode permettant d'ajouter un tuteur.
    * 
    * @return void
    * @access public
    */
    function add()
    {
        $this->set('title_for_layout', __('Ajouter un tuteur', true));
    }
    
    /**
    * Méthode permettant d'éditer un tuteur.
    *
    * @param int $id Identifiant du tuteur à éditer.
    * 
    * @return void
    * @access public
    */
    function edit($id = null)
    {
        $this->set('title_for_layout', __('Modifier un tuteur', true));
    }
    
    
    /**
    * Méthode permettant de supprimer un tuteur.
    *
    * @param int $id Identifiant du tuteur à supprimer.
    * 
    * @return void
    * @access public
    */
    function delete ($id)
    {
        
    }
    
    
}
?>
