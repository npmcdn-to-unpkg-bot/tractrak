<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Stadium
 */
class Stadium extends \Eloquent {

	use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'stadiums';

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
     * The meets held in this stadium
     */
	public function meets()
	{
		return $this->hasMany(Meet::class, 'stadium_id', 'id');
	}

	public function state()
    {
        return $this->hasOne('App\Models\State', 'id', 'stateid');
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'countryid');
    }
}
