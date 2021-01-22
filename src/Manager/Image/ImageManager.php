<?php


namespace App\Manager\Image;


use Exception;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImageManager
{
    /**
     * Create directory if not exist
     * @param $pathdir
     */
    protected function createDir($pathdir){
        if(!is_dir($pathdir)){
            mkdir($pathdir);
        }
    }

    /**
     * Fonction permettant de déplacer les images du dossier extracted vers le dossier public/annonces/images
     * @param SymfonyStyle $io
     * @param $folderExtracted
     * @param $folderImages
     * @param $folderThumbs
     * @param $folder
     * @param int $tailleW
     * @param int $tailleH
     */
    public function moveImages(SymfonyStyle $io, $folderExtracted, $folderImages, $folderThumbs, $folder, $tailleW = 150, $tailleH = 150)
    {
        $config = ['Mode' => 'FULL'];
        $pathExtractedFolder = $folderExtracted . $folder;
        $dir = scandir($pathExtractedFolder);

        // ------------------------ CONFIGURATION PHOTO
        $findConfig = false;
        foreach ($dir as $file) {
            if (preg_match('/([^\s]+(\.(?i)(cfg))$)/i', $file, $matches)) {
                $config = parse_ini_file($pathExtractedFolder . '/' . $file);
                $findConfig = true;
            }
        }

        $io->comment('Config Photo : ' . $folder);
        $isEmpty = true;

        if (!$findConfig) {
            $io->text('Fichier config introuvable -> so default FULL MODE');
        }

        // ------------------------ INITIALISATION DES DOSSIERS DE DESTINATION
        $pathImgFolder = $folderImages . $folder;
        $pathThumbFolder = $folderThumbs . $folder;
        $this->createDir($pathImgFolder);
        $this->createDir($pathThumbFolder);

        // ------------------------- ACTION SUR IMG EN FONCTION DE LA CONFIG
        ini_set('gd.jpeg_ignore_warning', true);
        switch ($config['Mode']) {
            case "FULL":
                $io->text("[Photos en mode FULL]");
                foreach ($dir as $item) {
                    if (preg_match('/([^\s]+(\.(?i)(JPG|GIF|PNG|JPEG|jpg|gif|png))$)/i', $item, $matches)) {
                        $isEmpty = false;
                        $this->moveAction($io, $pathExtractedFolder, $pathImgFolder, $pathThumbFolder, $item, $tailleW, $tailleH);
                    }
                }
                break;
            case "URL":
                $io->text("[Photos en mode URL]");
                $isEmpty = false;
                break;
            default:
                $io->error("Photos en mode DIFF");
                break;
        }


        if ($isEmpty) {
            $io->comment("Dossier " . $folder . " vide ou fichiers non conforme ou config erroné.");
        } else {
            $io->text("Transfert images [OK]");
        }
        unset($dir);

        $io->newLine(1);
    }

    /**
     * Function appellant la création de thumb et déplacement des images dans le dossier spécifié
     * @param SymfonyStyle $io
     * @param $pathExtractedFolder
     * @param $pathImgFolder
     * @param $pathThumbFolder
     * @param $item
     * @param $tailleW
     * @param $tailleH
     */
    protected function moveAction(SymfonyStyle $io, $pathExtractedFolder, $pathImgFolder, $pathThumbFolder, $item,  $tailleW, $tailleH)
    {
        $this->createThumb($pathExtractedFolder, $pathThumbFolder, $item, $tailleW, $tailleH);

        // déplacement des images
        if (rename(
            $pathExtractedFolder . "/" . $item,
            $pathImgFolder . "/" . $item
        )) {
        } else {
            $io->warning("L'image : " . $item . " n'a pas été déplacé.");
        }
    }

    /**
     * Resize and create l'image en thumbs
     * @param $item
     * @param $pathExtractedFolder
     * @param $pathThumbFolder
     * @param $tailleW
     * @param $tailleH
     */
    public function createThumb($pathExtractedFolder, $pathThumbFolder, $item, $tailleW, $tailleH)
    {
        $file = $pathExtractedFolder . "/" . $item;
        list($width, $height) = getimagesize($file);

        $ratio_orig = $width/$height;
        $w = $tailleW;
        $h = $tailleH;


        if ($w/$h > $ratio_orig) {
            $w = $h*$ratio_orig;
        } else {
            $h = $w/$ratio_orig;
        }

        ini_set('gd.jpeg_ignore_warning', true);
        $src = @imagecreatefromjpeg($file);
        $thumb = imagecreatetruecolor($w, $h);
        @imagecopyresampled($thumb, $src, 0, 0, 0, 0, $w, $h, $width, $height);

        $nameWithoutExt = pathinfo($pathExtractedFolder . '/' .$item)['filename'];
        $name = $nameWithoutExt . '-thumbs.jpg';

        @imagejpeg($thumb, $pathThumbFolder . "/" . $name,75);
    }

    /**
     * Download et déplace l'image via une URL
     * @param $folderImages
     * @param $folderThumbs
     * @param $file
     * @param $folder
     * @return bool|string
     */
    public function downloadImgURL($folderImages, $folderThumbs, $file, $folder){
        try{
            if(!is_dir($folderImages . $folder)){
                mkdir($folderImages . $folder);
            }
            if(!is_dir($folderThumbs . $folder)){
                mkdir($folderThumbs . $folder);
            }
            $current = file_get_contents($file);
            $filename = substr($file, strripos($file, "/")+1 , strlen($file));
            $file = $folderImages . $folder . '/' .$filename;
            file_put_contents($file, $current);
        }catch (Exception $e){
            return  null;
        }

        return $filename;
    }
}