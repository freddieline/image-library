<?php

namespace GrandVisual;
use Symfony\Component\Finder\SplFileInfo;
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08/05/2018
 * Time: 15:31
 */

use App\Logger;
class Photos
{
    private $_photos;

    private $_photoFileNames = [];

    /*
     * delete photo
     */
    public function deletePhoto( $storagePath, $filename ){

        try{
            //delete photo
            \File::move($storagePath. "/" . $filename , storage_path("app/public/deleted/"). $filename );

            Logger::updateOrCreate(
                [ 'image-filename' => $filename ],
                [
                    'sent' => false,
                    'deleted' => true
                ]
            );

            //return photos
            return $this->getPhotosByModifedTime( $storagePath );




        }
        catch(\Exception $e){
            //if error then throw error to Controller
            throw new \Exception("Can't delete photo");

        }
    }

    public function getTerms( $termsPath ){
        return \File::get( $termsPath );
    }

    public function getPhotosByModifedTime( $storagePath ){

        /*
         * get photos from storage and sort by modified time
         */
        $this->_photos = collect(\File::allFiles( $storagePath ))
            ->sortBy(\Closure::fromCallable( [$this, 'sortByModifiedTime']));

        /*
         * cycle through photos and return filenames
         */
        $this->_photos->each(function($file){
            array_push($this->_photoFileNames, $file->getFilename());
        });

        return $this->_photoFileNames;
    }

    /**
     * @param SplFileInfo $File
     * @return int
     */
    private function sortByModifiedTime( SplFileInfo $File ) {

        return $File->getMTime();

    }
}