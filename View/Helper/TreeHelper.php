<?php

/**
 * Tree helper
 *
 * Helper to generate tree representations of MPTT or recursively nested data
 */
class TreeHelper extends AppHelper {

  var $helpers = array('Html');

  function generate($array, $ModelName) {

      if (count($array)) {
          echo "\n<ul>\n";
          foreach ($array as $vals) {
              foreach ($vals as $val) {
                  $child = "";
                  if(isset($val['Child'.$ModelName]) && count($val['Child'.$ModelName]))
                      $child = "jstree-closed";
                  if(isset($val['Item']) && count($val['Item']))
                      $child = "jstree-closed";

                  if(isset($val['Item']['title'])){
                      echo "<li data-jstree='{ \"icon\" : \"fa fa-lg ".$this->returnClassTypeItem($val['Item']['type'])." fa-cube\" }' data-type=\"feuille\" data-id=\"" . $val['Item']['id'] . "\">". $this->returnLevels($val['Level']) . $val['Item']['title'];
                      echo "</li>\n";
                  }

                  if(isset($val[$ModelName])) {
                      echo "<li class='".$child."' data-jstree='{ \"icon\" : \"fa fa-lg fa-cubes\" }' data-type='noeud' data-id='".$val[$ModelName]['id']."' id='".$val[$ModelName]['id']."'> ".$val[$ModelName]['title'];
                      echo "</li>\n";
                  }
              }
          }
          echo "</ul>\n";
      }
  }

  function returnClassTypeItem($id){
      switch ($id) {
          case 1:
              return "text-danger";
              break;
          case 2:
              return "text-info";
              break;
          case 3:
              return "text-success";
              break;
      }
  }

  function returnLevels(array $levels){
    $label = '';
    foreach($levels as $level){
      $label .= '<span class="label label-default">'.$level['title'].'</span> ';
    }
    return $label;
  }

}
?>
