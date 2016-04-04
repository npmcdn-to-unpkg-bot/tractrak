<?php namespace App\Models;

/**
 * Class RaceType
 */
class RaceType extends \Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'race_types';

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

    /*
     * The race type (800m, Gender, Relay, etc)
     */
    public function type()
    {
        return $this->hasMany('App\Models\Race', 'id', 'race_type');
    }

    public function isAthleteRace()
    {
        if ($this->athlete_team === 0) return true;
        return false;
    }

    public function isTeamRace()
    {
        if ($this->athlete_team === 1) return true;
        return false;
    }
}
