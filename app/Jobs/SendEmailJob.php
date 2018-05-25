<?php

namespace App\Jobs;

/**
 * Created by PhpStorm.
 * User: user
 * Date: 08/05/2018
 * Time: 15:49
 */
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;
use App\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /*
     *
     */
    private $_photoPath;

    /*
     *
     */
    private $_email;

    public $photo;


    public function __construct( $email,  $directory, $photo){

        $this->photo = $photo;
        $this->_photoPath = $directory. $photo;
        $this->_email = $email;
    }

    /*
     * send email with email address provided with photo path
     */
    public function handle(){

            Mail::to($this->_email)
                ->send(new SendEmail( $this->_photoPath ))
            ;

            return $this;
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {

        Log::firstOrCreate([
            'image-filename' => $this->photo,
            'sent' => false,
            'deleted' => false,
            'failed' => true,
            'updated_at' => Carbon::now()->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString()
        ]);

    }

}