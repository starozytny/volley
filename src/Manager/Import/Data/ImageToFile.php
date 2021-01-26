<?php

namespace App\Manager\Import\Data;

use App\Manager\Image\ImageManager;

class ImageToFile
{
    protected $file;
    protected $thumbs;
    protected $pathImg;
    protected $pathThumbs;
    protected $folder;

    function __construct($file , $tabPathImg, $folder)
    {
        $this->pathImg = $tabPathImg['images'];
        $this->pathThumbs = $tabPathImg['thumbs'];
        $this->folder = $folder;

        $this->setFile($file);
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $test = substr($file, 0,4);
        if($test == 'http' || $test == 'https'){
            $imageManager = new ImageManager();

            $imageManager->setPathImg($this->pathImg);
            $filename = $imageManager->downloadImgURL($file, $this->folder);

            if ($filename != null){
                $imageManager->setPathThumb($this->pathThumbs);
                $imageManager->createThumb($file, $filename, $this->folder, $this->pathImg, 150 ,150);

                $this->file = $filename;
                $thumbs = substr($filename, 0, strripos($filename, '.')) . '-thumbs.jpg';
            }else{
                $thumbs = null;
            }

        }else{
            if(file_exists($this->pathImg . $this->folder ."/". $file)){

                $this->file = $file;
                $thumbs = substr($file, 0, strripos($file, '.')) . '-thumbs.jpg';
            }else{
                $this->file = null;
                $thumbs = null;
            }

        }
        $this->setThumbs($thumbs);
    }

    /**
     * @return mixed
     */
    public function getThumbs()
    {
        return $this->thumbs;
    }

    /**
     * @param mixed $thumbs
     */
    public function setThumbs($thumbs): void
    {
        $this->thumbs = $thumbs;
    }
}
