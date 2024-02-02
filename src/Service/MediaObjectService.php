<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaObjectService
{
    private $params;
    public function __construct( ParameterBagInterface $params) {
        $this->params = $params;
    }

    /**
     * @throws Exception
     */
    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250): string
    {
        //nouveau nom de l'image
        $file_name = md5(uniqid(rand(),true)) . '.webp';

        //on récupère les infos de l'image
        $picture_infos = getimagesize($picture);

        if ($picture_infos === false){
            throw new Exception('Format d\'image incorrect');
        }

        //verification du format de l'image
        $picture_source = match ($picture_infos['mime']) {
            'image/png' => imagecreatefrompng($picture),
            'image/jpeg' => imagecreatefromjpeg($picture),
            'image/webp' => imagecreatefromwebp($picture),
            default => throw new Exception('Format d\'image incorrect'),
        };

        //on recadre l'image
        //on récupère les dimensions
        $imageWidth = $picture_infos[0];
        $imageHeight = $picture_infos[1];

        //on vérifie l'orientation de l'image
        switch ($imageWidth <=> $imageHeight){
            case -1 : //portrait
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize) / 2;
                break;
            case 0 : //carré
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break;
            case 1 : //paysage
                $squareSize = $imageHeight;
                $src_x = ($imageWidth - $squareSize) / 2;
                $src_y = 0;
                break;
        }

        //on crée une nouvelle image "vierge"
        $resized_picture = imagecreatetruecolor($width,$height);
        imagecopyresampled($resized_picture, $picture_source, 0 , 0,$src_x,$src_y,$width,$height,$squareSize,$squareSize);

        $path = $this->params->get('uploads_directory') . $folder;

        //on crée le dossier de destination s'il n'existe pas
        if (!file_exists($path.'/miniature/')){
            mkdir($path.'/miniature/',0755,true);
        }

        //on stocke l'image recadrée
        imagewebp($resized_picture,$path.'/miniature/'.$width.'x'.$height.'-'.$file_name);

        $picture->move($path.'/'.$file_name);

        return $file_name;
    }

    public function delete(string $file_name, ?string $folder = '', ?int $width = 250, ?int $height = 250): bool
    {
        if ($file_name !== 'default.webp'){
            $success = false;
            $path = $this->params->get('uploads_directory') . $folder;

            $miniature = $path.'/miniature/'.$width.'x'.$height.'-'.$file_name;

            if (file_exists($miniature)){
                unlink($miniature);
                $success = true;
            }

            $original = $path . '/' . $file_name;

            if (file_exists($original)){
                unlink($original);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}