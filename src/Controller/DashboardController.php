<?php
namespace app\Controller;

use /** @noinspection PhpUnusedAliasInspection */
    App\Controller\AppController;
use App\Model\Table\CompetencesTable;
use Cake\ORM\TableRegistry;

class DashboardController extends AppController
{

    public function index()
    {
    }

    public function repo($id)
    {
        /** @var CompetencesTable $competences */
        $competences = TableRegistry::get('Competences');
        //$competences->behaviors()->Tree->config('scope', ['repository_id' => '0']);
        //$competences->recover();
//        $competences->behaviors()->Tree->config('scope', ['scope' => '2']);
//        $competences->recover();
//        $competences->behaviors()->Tree->config('scope', ['scope' => '3']);
//        $competences->recover();
//        $competences->behaviors()->Tree->config('scope', ['scope' => '4']);
//        $competences->recover();

        //$competences->removeBehavior('Tree');

        $list = $competences
            ->find('all')
            ->select(['id', 'parent_id', 'title', 'type', 'end_cycle'])
            ->where(['repository_id' => $id])->toArray();

        $listjson = array_map(function ($list) {
            if ($list['parent_id'] === null) {
                $list['parent_id'] = '#';
            }
            if ($list['end_cycle'] == 1) {
                $list['type'] = 'cycle';
            }
            

            return [
                'id' => strval($list['id']),
                'parent' => strval($list['parent_id']),
                'text' => $list['title'],
                'type' => strval($list['type'])
            ];
        }, $list);

        $this->set('listjson', $listjson);
        $this->set('listjson', json_encode($listjson));
        $this->set('_serialize', 'listjson');
    }

    public function test()
    {
    }
}
