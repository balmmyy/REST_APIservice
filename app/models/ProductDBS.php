<?php
/**
 * Created by PhpStorm.
 * User: Palm
 * Date: 15/10/2558
 * Time: 21:54
 */

use Jenssegers\Mongodb\Model as Eloquent;

class ProductDBS extends Eloquent
{
    protected $collection = 'product';
}