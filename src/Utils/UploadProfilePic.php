<?php


namespace App\Utils;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadProfilePic
{
    protected string $newFileName;
    public function save(String $name, $image, String $directory): string
    {
        /**
         * @var UploadedFile $file
         */
        if($image){
            $newFileName=$name.'-'.uniqid().'.'.$image->guessExtension();
            $image->move($directory, $newFileName);
        }
        return $newFileName;
    }

}