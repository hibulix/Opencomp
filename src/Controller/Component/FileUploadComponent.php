<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class FileUploadComponent extends Component
{
    public function checkError($file, $mimeType)
    {
        switch ($file['error']){
            case 1: // UPLOAD_ERR_INI_SIZE
                $err = 'Le fichier envoyé est trop volumineux.';
                break;
            case 2: // UPLOAD_ERR_FORM_SIZE
                $err = 'Le fichier envoyé est trop volumineux.';
                break;
            case 3: // UPLOAD_ERR_PARTIAL
                $err = 'L\'envoi du fichier a été interrompu pendant le transfert !';
                break;
            case 4: // UPLOAD_ERR_NO_FILE
                $err = 'Le fichier envoyé a une taille nulle !';
                break;
            default:
                break;
        }

        if($mimeType !== $file['type'])
            $err = 'Le fichier envoyé n\'est pas un fichier '.$mimeType.' !';

        if(($mimeType !== $file['type']) || $file['error'] !== 0)
            return $err;
        else
            return false;
    }
}
