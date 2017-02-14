<?php
namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model{

    protected $table = 'rooms';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected function getDateFormat()
    {
        return time();
    }

    protected  function  asDateTime($value)
    {
        return $value;
    }

    public function users(){
        return $this->belongsTo('App\Database\Users','userid','id');
    }

    public function categorys(){
        return $this->belongsTo('App\Database\Category','category','id');
    }
}