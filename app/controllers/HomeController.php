<?php
class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getAll()
	{
		$dbs = new MemberDBS();
		$rd = DB::collection($dbs->getTable())->get();
		return Response::json($rd);
	}

	public function deleteAll()
	{
		$dbs = new MemberDBS();
		if(DB::collection($dbs->getTable())->delete()){
			$productDbs = new ProductDBS();
			if(DB::collection($productDbs->getTable())->delete()){
				return Response::json(array('message'=>'success'));
			}else{
				return Response::json(array('message'=>'error'));
			}
		}else{
			return Response::json(array('message'=>'error'));
		}
	}
}
