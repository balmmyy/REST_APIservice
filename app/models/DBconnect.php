<?php
use Jenssegers\Mongodb\Model as Eloquent;

class DBconnect extends Eloquent
{
	protected $collection = 'member';
}