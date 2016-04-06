<?php namespace App\Models;

/**
 * Class Race
 */
class Race extends \Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'races';

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

    /*
     * The meet this Race belongs to
     */
    public function meet()
    {
        return $this->belongsTo('App\Models\Meet');
    }

    /*
     * The race type (800m, Gender, Relay, etc)
     */
    public function type()
    {
        return $this->belongsTo('App\Models\RaceType', 'race_type', 'id');
    }

    /**
     * Is this an individual race?
     * @return bool
     */
    public function isAthleteRace()
    {
        return $this->type()->first()->isAthleteRace();
    }

    /**
     * Is this a Team race?
     * @return bool
     */
    public function isTeamRace()
    {
        return $this->type()->first()->isTeamRace();
    }

    public function athletes()
    {
        return $this->morphedByMany('App\Models\Athlete', 'competitor')->withPivot('lane', 'result', 'place');
    }

    public function teams()
    {
        return $this->morphedByMany('App\Models\Team', 'competitor')->withPivot('lane', 'result', 'place');
    }

    public function firstPlace()
    {
        if ($this->isAthleteRace()) {
            $competitors = $this->athletes();
//            dd($competitors->where('place', '1')->first());
        } else {
            $competitors = $this->teams();
        }

        return $competitors->where('place', '1')->first();
    }
}
