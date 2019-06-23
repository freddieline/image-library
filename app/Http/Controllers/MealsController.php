<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meals;

class MealsController extends Controller
{

    /**
     * @param Request $request
     * 
     */
    public function getMeal(Request $request){



        $meal = Meals::where('id' ,'=', $request->id)->first()->toArray();
        
        return array( 'data'=> $meal);
    }
}
