<?php
class OrderController extends BaseController {


    public function getAllOrderInMember($member)
    {
        $dbs = new DBconnect();
        $doc = $dbs->where('_id',$member)->orWhere('memberName',$member);


        if(isset($doc->get()[0])) {
            if(array_key_exists('Order',$doc->get()[0]->toArray())){
                return Response::json($doc->get()[0]['Order']);
            }else{
                return Response::json(array('message'=>'No Order'));
            }

        }else{
            return Response::json(array('message'=>'Member not found'));
        }
        //return Response::json(array('message'=>'not found'));
    }

	public function getOrderInMember($member,$id)
	{
        $dbs = new DBconnect();
        $doc = $dbs->where('_id',$member)->orWhere('memberName',$member);


        if(isset($doc->get()[0])) {
            $data = $doc->get()[0]['Order'];
            for($i=0; $i<count($data);$i++){
                $item=$data[$i];
                if($item['Order_id']==$id){
                    return Response::json($item);
                }
            }
            return Response::json(array('message'=>'Order not found'));
        }else{
            return Response::json(array('message'=>'Member not found'));
        }
	}

    public function addOrderInMember($member)
    {
        $dbs = new DBconnect();
        $doc = $dbs->where('_id',$member)->orWhere('memberName',$member);

        $post = Input::get();


        //remove csrf token
        if(array_key_exists('_token', $post)){
            unset($post['_token']);
        }

        Eloquent::unguard();

        if(isset($doc->get()[0])) {
            $data = array('Order_id'=>new MongoId);
            $data = $data + $post;
           // $data = new MongoId;
            //$data1 = $data->toArray() + $post;
            if($doc->push('Order', $data)){
                return Response::json(array('message'=>'success'));
            }else{
                return Response::json(array('message'=>'error'));
            }
        }else{
            return Response::json(array('message'=>'Member not found'));
        }
    }

    public function editOrderInMember($member,$id)
    {
        $dbs = new DBconnect();
        $doc = $dbs->where('_id',$member)->orWhere('memberName',$member);

        $post = Input::get();

        //remove csrf token
        if(array_key_exists('_token', $post)){
            unset($post['_token']);
        }

        Eloquent::unguard();

        if(isset($doc->get()[0])) {
            $data = $doc->get()[0]['Order'];
            $found = false;
            $payload = array();
            for($i=0; $i<count($data);$i++){
                $item=$data[$i];
                if($item['Order_id']==$id){

                    $found = true;

                    //$data1 = array('Order_id'=>array('$id'=>$id));
                    $data1 = array('Order_id'=>$item['Order_id']);
                    //$data1['Order_id']['$id']=$id;
                    $data1 = $data1 + $post;
                    array_push($payload,$data1);
                    //array_push($payload,array_merge($item->toArray(),$post->toArray()));
                }else{
                    array_push($payload,$item);
                }
            }
            if($found){
                if($doc->update(array('Order'=>$payload))){
                    return Response::json(array('message'=>'success'));
                }else{
                    return Response::json(array('message'=>'error'));
                }
            }else{
                return Response::json(array('message'=>'Order not found'));
            }
        }else{
            return Response::json(array('message'=>'Member not found'));
        }
    }

    public function deleteOrderInMember($member,$id)
    {
        $dbs = new DBconnect();
        $doc = $dbs->where('_id',$member)->orWhere('memberName',$member);

        $post = Input::get();

        //remove csrf token
        if(array_key_exists('_token', $post)){
            unset($post['_token']);
        }

        Eloquent::unguard();

        if(isset($doc->get()[0])) {
            $data = $doc->get()[0]['Order'];
            $found = false;
            $payload = array();
            for($i=0; $i<count($data);$i++){
                $item=$data[$i];
                if($item['Order_id']==$id){

                    $found = true;

                }else{
                    array_push($payload,$item);
                }
            }
            if($found){
                if($doc->update(array('Order'=>$payload))){
                    return Response::json(array('message'=>'success'));
                }else{
                    return Response::json(array('message'=>'error'));
                }
            }else{
                return Response::json(array('message'=>'Order not found'));
            }
        }else{
            return Response::json(array('message'=>'Member not found'));
        }
    }

    public function getAllOrder()
    {
        $dbs = new DBconnect();
        $rd = DB::collection($dbs->getTable())->get();
        $result = array();
        for($i=0; $i<count($rd); $i++){
            $mem = $rd[$i];
            if(array_key_exists('Order',$mem)){
                $mem = array_except($mem,array('_id','updated_at'));
                array_push($result,$mem);
            }
        }
        return Response::json($result);
    }

    public function getOrder($id)
    {
        $dbs = new DBconnect();
        $rd = DB::collection($dbs->getTable())->get();
        $result = array();
        for($i=0; $i<count($rd); $i++){
            $mem = $rd[$i];
            if(array_key_exists('Order',$mem)){

                $data =$mem['Order'];
                for($j=0; $j<count($data);$j++){
                    $item=$data[$j];
                    if($item['Order_id']==$id){
                        $mem = array_except($mem,array('_id','updated_at','Order'));
                        array_push($result,$mem);
                        array_push($result,$item);
                        return Response::json($result);
                    }
                }

            }
        }
        return Response::json(array('message'=>'Order not found'));
    }

    public function editOrder($id)
    {
        $dbs = new DBconnect();
        $post = Input::get();
        $rd = DB::collection($dbs->getTable())->get();
        $result = array();
        for($i=0; $i<count($rd); $i++){
            $mem = $rd[$i];
            if(array_key_exists('Order',$mem)){

                $data =$mem['Order'];
                $found = false;
                $payload = array();
                for($j=0; $j<count($data);$j++){
                    $item=$data[$j];
                    if($item['Order_id']==$id){

                        $found = true;

                        //$data1 = array('Order_id'=>array('$id'=>$id));
                        $data1 = array('Order_id'=>$item['Order_id']);
                        //$data1['Order_id']['$id']=$id;
                        $data1 = $data1 + $post;
                        array_push($payload,$data1);
                        //array_push($payload,array_merge($item->toArray(),$post->toArray()));
                    }else{
                        array_push($payload,$item);
                    }
                }
                if($found){
                    $doc = $dbs->where('_id',$mem['_id']);
                    if($doc->update(array('Order'=>$payload))){
                        return Response::json(array('message'=>'success'));
                    }else{
                        return Response::json(array('message'=>'error'));
                    }
                }

            }
        }
        return Response::json(array('message'=>'Order not found'));
    }

    public function deleteOrder($id)
    {
        $dbs = new DBconnect();
        $post = Input::get();
        $rd = DB::collection($dbs->getTable())->get();
        $result = array();
        for($i=0; $i<count($rd); $i++){
            $mem = $rd[$i];
            if(array_key_exists('Order',$mem)){

                $data =$mem['Order'];
                $found = false;
                $payload = array();
                for($j=0; $j<count($data);$j++){
                    $item=$data[$j];
                    if($item['Order_id']==$id){
                        $found = true;
                    }else{
                        array_push($payload,$item);
                    }
                }
                if($found){
                    $doc = $dbs->where('_id',$mem['_id']);
                    if($doc->update(array('Order'=>$payload))){
                        return Response::json(array('message'=>'success'));
                    }else{
                        return Response::json(array('message'=>'error'));
                    }
                }

            }
        }
        return Response::json(array('message'=>'Order not found'));
    }

}