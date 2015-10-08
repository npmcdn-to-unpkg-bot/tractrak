<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Level
 */
class Level extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'levels';

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

}
