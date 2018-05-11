<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 19/07/2017
 * Time: 15:37
 */

namespace App\Http\ViewComposers;

use Auth;
use Illuminate\View\View;
use GrandVisual\Photos;


class PhotoComposer {

    private $_photosDirectory;
    private $_files;
    private $_photoFileNames;

    /**
     * @param View $View
     */
    public function compose( View $View )
    {

        $photos = ( new Photos )
            ->getPhotosByModifedTime(
                storage_path('app/public/photos/')
        );

        $terms = ( new Photos )
            ->getTerms(
                storage_path('app/public/')."/" ."terms.txt"
            );
        // Alias for if the user is signed in
        $View->with( 'photos', $photos );
        $View->with( 'photosDirectory', './storage/photos/' );
        $View->with( 'terms', $terms );
    }


}