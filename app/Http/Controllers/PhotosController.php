<?php

namespace App\Http\Controllers;

use GrandVisual\Photos;
use GrandVisual\SortPhotos;
use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use App\Log;
use Carbon\Carbon;
use App\Logger;


class PhotosController extends Controller
{


    /*
     * get photos from storage directorysorted by modified time
     */
    public function getPhotos()
    {
        return response()->json(['photos' => ( new Photos )
            ->getPhotosByModifedTime(
                storage_path('app/public/photos/')
            )]);

    }

    /*
     * delete a photo by filename
     */
    public function deletePhoto(Request $request)
    {
        //cast request to array
        $request = $request->toArray();

        // delete photo and return remaining photos
        try{
            return ( new Photos )->deletePhoto(
                storage_path('app/public/photos/') ,
                $request['photo']
            );


        }
        //return error if can't delete photo
        catch(\Exception $e){

            return abort(503);
        }
    }

    /*
     * send email to user
     */
    public function sendEmail(Request $request){

        //cast request to array
        $request = $request->toArray();

        //make sure email and photo path are set
        if(isset($request['email']) && isset($request['photo'])) {

            //create new job to send email
            dispatch((new SendEmailJob(

                // email
                $request['email'],

                // directory
                storage_path('app/public/photos/'),

                // photo filename
                $request['photo']

            ))
            -> onQueue( 'default' ));

        } else {
           return  $request;
        }

    }
}
