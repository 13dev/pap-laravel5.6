<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    /**
     * Return a unique personnal access token.
     *
     * @var string
     */
    public static function generate(): string
    {
        do {
            $api_token = md5(microtime());
        } while (User::where('api_token', $api_token)->exists());

        return $api_token;
    }
}
