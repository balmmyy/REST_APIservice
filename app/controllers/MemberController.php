<?php
class MemberController extends BaseController {

	public function getAll()
	{
		$dbs = new DBconnect();
		$rd = DB::collection($dbs->getTable())->get();
		return Response::json($rd);
	}

	public function getAllMember()
	{
		$dbs = new DBconnect();
		$rd = DB::collection($dbs->getTable())->get();
		$result = array();
		for($i=0; $i<count($rd); $i++){
			$mem = $rd[$i];
			if(array_key_exists('Order',$mem)){
				$mem = array_except($mem,array('Order','updated_at'));
			}
			array_push($result,$mem);
		}
		return Response::json($result);
	}

	public function getMember($key)
	{
		$dbs = new DBconnect();
		$doc = $dbs->where('_id',$key)->orWhere('memberName',$key)->get();

		if(isset($doc[0])) {

			return Response::json(array_except($doc[0]->toArray(),array('Order','updated_at')));
			//return Response::json((array)$doc[0]);
		}else{
			return Response::json(array('message'=>'Member not found'));
		}
	}

	public function addMember()
	{

		$post = Input::get();
		//remove csrf token
		if(array_key_exists('_token', $post)){
			unset($post['_token']);
		}
		$dbs = new DBconnect();
		if($dbs->insert($post)){
			return Response::json(array('message'=>'success'));
		}else{
			return Response::json(array('message'=>'error'));
		}
	}
	
	public function editMember($key)
	{
		$dbs = new DBconnect();
        $doc = $dbs->where('_id',$key)->orWhere('memberName',$key);

		$post = Input::get();

		//remove csrf token
		if(array_key_exists('_token', $post)){
			unset($post['_token']);
		}

		Eloquent::unguard();

		if(isset($doc->get()[0])){
			if($doc->update($post)){
				return Response::json(array('message'=>'success'));
			}else{
				return Response::json(array('message'=>'error'));
			}
		}else{
            return Response::json(array('message'=>'Member not found'));
		}


	}
	
	public function deleteMember($key)
	{
		$dbs = new DBconnect();

        $doc = $dbs->where('_id',$key)->orWhere('memberName',$key);

		if(isset($doc->get()[0])){
			if($doc->delete()){
				return Response::json(array('message'=>'success'));
			}else{
				return Response::json(array('message'=>'error'));
			}
		}else{
            return Response::json(array('message'=>'Member not found'));
		}


	}

}