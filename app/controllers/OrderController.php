<?php
class OrderController extends BaseController {


    public function getAllOrderInMember($member)
    {
        $dbs = new MemberDBS();
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
        $dbs = new MemberDBS();
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
        $memberDbs = new MemberDBS();
        $doc = $memberDbs->where('_id',$member)->orWhere('memberName',$member);

        $post = Input::get();

        if(!array_key_exists('product', $post)){
            return Response::json(array('message'=>'Please provide product. (example-> \'product\':\'book\')'));
        }

        //remove csrf token
        if(array_key_exists('_token', $post)){
            unset($post['_token']);
        }

        Eloquent::unguard();

        if(isset($doc->get()[0])) {
            $data = array('Order_id'=>new MongoId);
            $data = $data + $post;

            if($doc->push('Order', $data)){
                $ProductController = new ProductController();
                if($ProductController->addOrderOfProduct($post['product'],$data['Order_id'])){
                    return Response::json(array('message'=>'success'));
                }else{
                    return Response::json(array('message'=>'error'));
                }
            }else{
                return Response::json(array('message'=>'error'));
            }
        }else{
            return Response::json(array('message'=>'Member not found'));
        }
    }

    public function editOrderInMember($member,$id)
    {
        $dbs = new MemberDBS();
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
            $ori ='';
            $new ='';
            $data1 = array();
            for($i=0; $i<count($data);$i++){
                $item=$data[$i];
                if($item['Order_id']==$id){

                    $found = true;

                    /* $data1 = array('Order_id'=>$item['Order_id']);
                     $data1 = $data1 + $post;*/
                    $ori=$item['product'];
                    $data1 = array_merge($item,$post);
                    $new = $data1['product'];
                    array_push($payload,$data1);
                }else{
                    array_push($payload,$item);
                }
            }
            if($found){
                if($doc->update(array('Order'=>$payload))){
                    $ProductController = new ProductController();
                    if($ProductController->deleteOrderOfProduct($ori,$id)){
                        if($ProductController->addOrderOfProduct($new,$data1['Order_id'])) {
                            return Response::json(array('message' => 'success'));
                        }
                    }else{
                        return Response::json(array('message'=>'error'));
                    }
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
        $dbs = new MemberDBS();
        $doc = $dbs->where('_id',$member)->orWhere('memberName',$member);

        if(isset($doc->get()[0])) {
            $data = $doc->get()[0]['Order'];
            $found = false;
            $product='';
            $payload = array();
            for($i=0; $i<count($data);$i++){
                $item=$data[$i];
                if($item['Order_id']==$id){

                    $found = true;
                    $product = $item['product'];

                }else{
                    array_push($payload,$item);
                }
            }
            if($found){
                if($doc->update(array('Order'=>$payload))){
                    $ProductController = new ProductController();
                    if($ProductController->deleteOrderOfProduct($product,$id)){
                        return Response::json(array('message'=>'success'));
                    }else{
                        return Response::json(array('message'=>'error to update the order in product list'));
                    }
                }else{
                    return Response::json(array('message'=>'error to update the order in member list'));
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
        $dbs = new MemberDBS();
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
        $dbs = new MemberDBS();
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
        $dbs = new MemberDBS();
        $post = Input::get();
        $rd = DB::collection($dbs->getTable())->get();

        Eloquent::unguard();
        for($i=0; $i<count($rd); $i++){
            $mem = $rd[$i];
            if(array_key_exists('Order',$mem)){

                $data =$mem['Order'];
                $found = false;
                $payload = array();
                $ori ='';
                $new ='';
                $data1 = array();
                for($j=0; $j<count($data);$j++){
                    $item=$data[$j];
                    if($item['Order_id']==$id){

                        $found = true;
                        $save = $item['product'];
                       /* $data1 = array('Order_id'=>$item['Order_id']);
                        $data1 = $data1 + $post;*/
                        $ori=$item['product'];
                        $data1 = array_merge($item,$post);
                        $new = $data1['product'];
                        array_push($payload,$data1);

                    }else{
                        array_push($payload,$item);
                    }
                }
                if($found){
                    $doc = $dbs->where('_id',$mem['_id']);
                    if($doc->update(array('Order'=>$payload))){
                        $ProductController = new ProductController();
                        if($ProductController->deleteOrderOfProduct($ori,$id)){
                            if($ProductController->addOrderOfProduct($new,$data1['Order_id'])) {
                                return Response::json(array('message' => 'success'));
                            }
                        }else{
                            return Response::json(array('message'=>'error'));
                        }


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
        $dbs = new MemberDBS();
        $rd = DB::collection($dbs->getTable())->get();

        for($i=0; $i<count($rd); $i++){
            $mem = $rd[$i];
            if(array_key_exists('Order',$mem)){

                $data =$mem['Order'];
                $found = false;
                $payload = array();
                $product='';
                for($j=0; $j<count($data);$j++){
                    $item=$data[$j];
                    if($item['Order_id']==$id){
                        $found = true;
                        $product = $item['product'];
                    }else{
                        array_push($payload,$item);
                    }
                }
                if($found){
                    $doc = $dbs->where('_id',$mem['_id']);
                    if($doc->update(array('Order'=>$payload))){
                        $ProductController = new ProductController();
                        if($ProductController->deleteOrderOfProduct($product,$id)){

                            return Response::json(array('message'=>'success'));
                        }else{
                            return Response::json(array('message'=>'error to update the order in product list'));
                        }
                    }else{
                        return Response::json(array('message'=>'error to update the order in member list'));
                    }
                }
            }
        }
        return Response::json(array('message'=>'Order not found'));
    }

}