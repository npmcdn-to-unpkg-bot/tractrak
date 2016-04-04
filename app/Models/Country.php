<?php namespace App\Models;

/**
 * Class Country
 */
class Country extends \Eloquent
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The teams in this state
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasManyThrough('App\Models\Team', 'App\Models\State', 'countryid', 'stateid', 'id');
    }

    /**
     * The teams in this state
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function states()
    {
        return $this->hasMany('App\Models\State', 'countryid', 'id');
    }
}
