<?php  

namespace App;

use Illuminate\Database\Eloquent\Model;


class SocialProvider extends Model{

    protected $fillable = [
		'provider_id', 
		'provider'
	];

    protected $hidden   = [
    	'created_at', 
    	'updated_at'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

}