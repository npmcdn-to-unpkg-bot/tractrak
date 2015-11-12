<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Athlete
 *  Gender: 0=male, 1=female
 */
class Athlete extends Model {

	use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'athletes';

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
	 * The team(s) this Athlete belongs to
	 */
	public function teams()
	{
		return $this->belongsToMany('App\Models\Team')->withPivot('current');
	}
}
