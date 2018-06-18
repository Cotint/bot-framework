<?php

namespace model;

use Exception;
use Illuminate\Database\Eloquent\Model as Eloquent;

class History extends Eloquent
{
    protected $table='history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [

        'chat_id', 'user_id','username', 'first_name','last_name','last_state'

    ];



    public static function saveUserInfo($data)
    {
        try {

            $history = History::updateOrCreate([
                'chat_id' =>$data['chat_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'username' =>  $data['username'],
                'last_state' => '0',
            ]);

            if (!$history) {
                throw new Exception("Error in registering user info");
            }

        }

        catch (Exception $exception){
            echo 'Message: ' .$exception->getMessage();
        }
    }

}