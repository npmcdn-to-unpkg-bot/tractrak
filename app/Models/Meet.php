<?php namespace App\Models;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Meet
 */
class Meet extends Model {

	use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'meets';

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
     * The user owner of this meet
     */
    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    /**
     * @param User $user
     */
    public function setOwner(User $user)
    {
        $this->owner_id = $user->id;
    }

    /*
     * The season of this meet
     */
//    public function season()
//    {
//        $this->hasOne('App\Season', 'id', 'season_id');
//    }
}
