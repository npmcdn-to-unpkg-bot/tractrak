<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Team
 */
class Team extends Model {

	use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'teams';

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * For soft deletes
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	/*
	 * The Athletes that belong on this Team
	 */
	public function athletes()
	{
		return $this->belongsToMany('App\Models\Athlete')->withPivot('current');
	}

	/*
	 * The events(s) this Athlete belongs to
	 */
	public function events()
	{
		return $this->hasMany('App\Models\Event')->withPivot('lane', 'result', 'time');
	}
}
