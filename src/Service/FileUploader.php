<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $publicDirectory;
    private $privateDirectory;
    private $slugger;

    public function __construct($publicDirectory, $privateDirectory, SluggerInterface $slugger)
    {
        $this->publicDirectory = $publicDirectory;
        $this->privateDirectory = $privateDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, $folder=null, $isPublic=true): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            if($folder){
                if(!is_dir($folder)){
                    mkdir($folder);
                }
            }

            $directory = $isPublic ? $this->getPublicDirectory() : $this->getPrivateDirectory();
            $directory = $directory . '/' . $folder;

            $file->move($directory, $fileName);
        } catch (FileException $e) {
            return false;
        }

        return $fileName;
    }

    public function deleteFile($fileName, $folderName, $isPrivate = false)
    {
        if($fileName){
            $file = $this->getDirectory($isPrivate) . $folderName . '/' . $fileName;
            if(file_exists($file)){
                unlink($file);
            }
        }
    }

    public function replaceFile($fileName, $oldFileName, $folderName, $isPrivate = false)
    {
        $oldFile = $this->getDirectory($isPrivate) . $folderName . '/' . $oldFileName;
        if($fileName && file_exists($oldFile) && $fileName !== $oldFileName){
            unlink($oldFile);
        }
    }

    private function getDirectory($isPrivate)
    {
        $path = $this->publicDirectory;
        if($isPrivate){
            $path = $this->privateDirectory;
        }

        return $path;
    }

    public function getPublicDirectory()
    {
        return $this->publicDirectory;
    }

    public function getPrivateDirectory()
    {
        return $this->privateDirectory;
    }


}