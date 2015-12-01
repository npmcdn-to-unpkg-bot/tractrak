<?php namespace App\Models;

use URL;
use Endroid\QrCode\QrCode;
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

    public function isPaid()
    {
        return $this->paid === 1;
    }

    /*
     * The season of this meet
     */
//    public function season()
//    {
//        $this->hasOne('App\Season', 'id', 'season_id');
//    }

    public function qr()
    {
        $qrCode = new QrCode();
        $qrCode
            ->setText(URL::route('frontend.meet.live', ['id' => $this->id]))
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
            ->setLabel('TracTrak.com')
            ->setLabelFontSize(24)
        ;

        return $qrCode;
    }

    public function isDropBoxReady()
    {
        return !is_null($this->owner()->first()->accessToken);
    }

    public function races()
    {
        return $this->hasMany('App\Models\Race');
    }
}
