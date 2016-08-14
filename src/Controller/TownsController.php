<?php
namespace app\Controller;

use App\Controller\AppController;

/**
 * TownsController.php
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
 * Contrôleur de gestion des établissements scolaires
 *
 * @category Controller
 * @package  Opencomp
 * @author   Jean Traullé <jtraulle@gmail.com>
 * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
 * @link     http://www.opencomp.fr
 */
class TownsController extends AppController
{
    public $paginate = [
        'limit' => 30
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadComponent('Search.Prg', [
            'actions' => ['index']
        ]);
    }

    public function index()
    {
        $query = $this->Towns
            // Use the plugins 'search' custom finder and pass in the
            // processed query params
            ->find('search', ['search' => $this->request->query])
            ->contain(['Academies']);

        $towns = $this->paginate($query);
        $paging['count'] = $this->request->params['paging']['Towns']['count'];
        $paging['perPage'] = $this->request->params['paging']['Towns']['perPage'];
        $paging['pageCount'] = $this->request->params['paging']['Towns']['pageCount'];

        $this->set(compact('towns', 'paging'));
        $this->set('_serialize', ['paging','towns']);
    }
}
