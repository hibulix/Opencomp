<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;

class LoadCSVComponent extends Component
{
    public $components = ['RequestHandler'];

    public function downloadCSV($src, $dest)
    {
        $this->_registry->getController()->set('_serialize', true);

        try{
            //récupération du fichier sur data_gouv
            $string = file_get_contents($src);

            file_put_contents($dest,$string);
            $this->_registry->getController()->set('error', false);
            $this->_registry->getController()->set('message', '');
        }catch(\Exception $e){
            $this->_registry->getController()->set('error', true);
            $this->_registry->getController()->set('message', $e->getMessage());
        }
    }

    public function populateCSV(string $csvPath, string $dstTable, int $truncateTable = 0, string $charsetSet,
                                string $separatorField, int $hasHeader = 0){
        $this->_registry->getController()->set('_serialize', true);

        try {
            $conn = ConnectionManager::get('default');

            if($truncateTable === 1){
                $conn->execute(
                    'TRUNCATE '.$dstTable.' ;'
                );
            }
            $conn->execute(
                'LOAD DATA INFILE \''.$csvPath.'\' REPLACE INTO TABLE '.$dstTable.
                ' CHARACTER SET "'.$charsetSet.'" FIELDS TERMINATED BY "'.$separatorField.
                '" IGNORE '.$hasHeader.' LINES;'
            );

            $this->_registry->getController()->set('error', false);
            $this->_registry->getController()->set('message', '');
        } catch (\Exception $e) {
            $this->_registry->getController()->set('error', true);
            $this->_registry->getController()->set('message', $e->getMessage());
        }
    }
}
