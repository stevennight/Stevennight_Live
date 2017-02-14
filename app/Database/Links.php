<?php
namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Links extends Model{

    protected $table = 'links';
    protected $primaryKey ='id';

    public $timestamps = false;
    protected function getDateFormat(){
        return time();
    }

    protected function  asDateTime($value)
    {
        return $value;
    }
}