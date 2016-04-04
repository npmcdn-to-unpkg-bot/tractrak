<?php namespace App\Models;

/**
 * Class Season
 */
class Season extends \Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'seasons';

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/*
	 * The meets owner of this meet
	 */
	public function meets()
	{
		$this->hasMany('App\Meet', 'season_id', 'id');
	}
}
