<?php
/**
 * Created by PhpStorm.
 * User: Palm
 * Date: 15/10/2558
 * Time: 22:10
 */
class ProductController extends BaseController {

    public function getAllProduct()
    {
        $productDbs = new ProductDBS();
        $rd = DB::collection($productDbs->getTable())->get();
        return Response::json($rd);
    }

    public function getProduct($product)
    {

        $dbs = new ProductDBS();
        $doc = $dbs->where('_id',$product)->orWhere('product',$product)->get();

        if(isset($doc[0])) {

            return Response::json(array_except($doc[0]->toArray(),array('updated_at')));
            //return Response::json((array)$doc[0]);
        }else{
            return Response::json(array('message'=>'Product not found'));
        }
    }

    public function deleteOrderOfProduct($product,$orderID)
    {
        $dbs = new ProductDBS();
        $doc = $dbs->where('product',$product);

        if(isset($doc->get()[0])) {
            $data = $doc->get()[0]['Order'];
            $found = false;
            $payload = array();
            for($i=0; $i<count($data);$i++){
                $item=$data[$i];
                if($item==$orderID){

                    $found = true;

                }else{
                    array_push($payload,$item);
                }
            }
            if($found){
                if($doc->update(array('Order'=>$payload))){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
        return true;
    }



    public function addOrderOfProduct($product,$orderID)
    {
        $productDbs = new ProductDBS();
        $product_doc = $productDbs->where('product',$product);
        if(isset($product_doc->get()[0])) {
            //found product in ProductDBS
            if($product_doc->push('Order', $orderID)){
                return true;
            }else{
                return false;
            }
        }else{
            //not found product in ProductDBS
            $product_detail = array('product'=>$product,'Order'=> array($orderID));
            if($productDbs->insert($product_detail)){
                //if($product_doc->push('Order', array('Order_id'=>$data['Order_id']))){
                return true;
            }else{
                return false;
            }
        }
    }
}
