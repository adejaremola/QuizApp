<?php  

namespace App;

use Illuminate\Database\Eloquent\Model;


class Profile extends Model{

    protected $fillable = [
		'id', 
		'user_id', 
		'pic_url'
	];

    protected $hidden   = [
    	'created_at', 
    	'updated_at'
    ];
}