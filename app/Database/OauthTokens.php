<?php
namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class OauthTokens extends Model{

    protected $table = 'oauth_tokens';
    protected $primaryKey = 'id';
    public $timestamps = false;
}