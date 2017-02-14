<?php
namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected function getDateFormat()
    {
        return time();
    }
    protected  function asDateTime($value)
    {
        return $value;
    }
}
