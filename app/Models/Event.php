<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 */
class Event extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'events';

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/*
	 * The meet(s) this Event belongs to
	 */
	public function meets()
	{
		return $this->belongsToMany('App\Meet')->withPivot('current');
	}

    public function isAthleteEvent()
    {
        if ($this->athlete_team === 0) return true;
        return false;
    }

    public function isTeamEvent()
    {
        if ($this->athlete_team === 1) return true;
        return false;
    }
}
