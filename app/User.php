<?php
namespace App;
use Lib\Model;

class User extends Model
{
	protected $table = 'users';
	protected $timestamps = false;
	protected $dates = ['date'];
	public $fields = [];
	public $hidden = ['password'];

}