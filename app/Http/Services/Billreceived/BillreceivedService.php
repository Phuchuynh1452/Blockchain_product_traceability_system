<?php

namespace App\Http\Services\Billreceived;

use App\Http\BlockChain\Blockchain;
use App\Http\Services\BlockChainService;
use App\Models\Bill;
use App\Models\Billchain;
use App\Models\Market;
use App\Models\Product;
use App\Models\Salesroom;
use Hamcrest\Util;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Web3\Contract;
use Web3\Utils;
use Web3\Web3;

class BillreceivedService
{

    public $blockchainService;
    public function __construct(BlockChainService $blockChainService)
    {
        $this->blockchainService = $blockChainService;

    }

    public function get(){
        $result = DB::select('select bills.id, bills.quantity, bills.price, bills.total_price, bills.list_saleroom, bills.shelf_life, bills.created_at, products.name, bills.thumb as qrcode
                                    from products, bills
                                    where bills.product_id = products.id');
        return $result;
    }

    public function getSaleroom(){
        return Market::all();
    }

    public function getProduct(){
        return Product::all();
    }

    public function getFirstProduct(){
        return DB::select('select products.id, products.thumb, suppliers.madoanhnghiep, suppliers.tencoso as name_supplier
                                 from products, suppliers
                                 where products.supplier_id = suppliers.id
                                 ORDER BY id ASC LIMIT 1');
    }

    public function getProductValue($id_product){
        return $info_product = DB::select('select products.id as id_product, suppliers.madoanhnghiep, suppliers.tencoso, products.thumb
                                              from  products,suppliers
                                              where products.supplier_id = suppliers.id and products.id = '.$id_product);
    }

    public function getId($id){
        $result = DB::select('select *
                                    from  billreceiveds
                                    where id = '.$id);
        return $result;
    }

    public function create($request){
        try {

            ##------------------------------CREATE Billreceived in MySQL-----------------------------------
            $products = DB::select('select * from  products ');
            $arrsaleroom = array();
            for ($i= 0; $i < count($request->input('saleroom')); $i++){
                array_push($arrsaleroom, $request->input('saleroom')[$i]);
            }
            $arrSaleroom = implode(",",$arrsaleroom);
            foreach ($products as $product){
                if ($product->id == $request->input("id_product")){
                    $price = $product->price;
                }
            }

            $total_price = $price * $request->input("quantity");
            Bill::create([
                'quantity'=>$request->input("quantity"),
                'price'=>$price,
                'total_price'=>$total_price,
                'product_id'=>$request->input("id_product"),
                'list_saleroom'=>$arrSaleroom,
                'shelf_life'=>$request->input("shelf_life")
            ]);

            // update quantity product --- active



            $products_id = DB::select('select * from  products where id =  '.$request->input("id_product"));

            $quantity_id = $products_id[0]->quantity;
            $quantity_id = (int)$quantity_id + (int)$request->input("quantity");
            Product::where('id',$request->input("id_product"))->update(array(
                'quantity'=>$quantity_id,
                'active'=>1
            ));

            $id_bill = DB::select('SELECT * FROM `bills` ORDER BY id DESC  LIMIT 1 ');
            $id = $id_bill[0]->id;
            $id_product = $request->input("id_product");
            $created_at = $id_bill[0]->created_at;
            $shelf_life = $id_bill[0]->shelf_life;

            ## get all infomation in product

//            dd("2");
            $info_product = DB::select('select products.id as id_product, products.name as name_product, products.description as des_product, products.detail as detail_product, products.thumb as thumb_product,
                                                     suppliers.thumb as thumb_supplier, suppliers.madoanhnghiep as madoanhnghiep_supplier, suppliers.tencoso as tencoso_supplier, suppliers.mota as mota_supplier, suppliers.diachi as diachi_supplier, suppliers.sodienthoai as sodienthoai_supplier
                                              from  products, suppliers
                                              where products.supplier_id = suppliers.id and products.id = '.$id_product);

//            dd("1");
            //nha cung cap
            $madoanhnghiep = $info_product[0]->madoanhnghiep_supplier;
            $tenncc = $info_product[0]->tencoso_supplier;
            $thumb_ncc = $info_product[0]->thumb_supplier;
            $mota_ncc = $info_product[0]->mota_supplier;
            $diachi_ncc = $info_product[0]->diachi_supplier;
            $sodienthoai_ncc = $info_product[0]->sodienthoai_supplier;

            //san pham
            $ten_sp = $info_product[0]->name_product;
            $mota_sp = $info_product[0]->des_product;
            $chitiet_sp = $info_product[0]->detail_product;
            $thumb_sp = $info_product[0]->thumb_product;

            $arrayValue = [ "madoanhnghiep"=>$madoanhnghiep, "tenncc"=>$tenncc, "thumb_ncc"=> $thumb_ncc, "mota_ncc"=>$mota_ncc, "diachi_ncc"=>$diachi_ncc, "sodienthoai_ncc"=>$sodienthoai_ncc,
                            "id_product"=>$id_product, "ten_sp"=>$ten_sp,"mota_sp"=>$mota_sp, "chitiet_sp"=>$chitiet_sp,"thumb_sp"=>$thumb_sp,
                            "id_bill"=>$id,"created_at"=>$created_at,"shelf_life"=>$shelf_life
                          ];

            $blockchain = DB::select('select *
                                            from  billchains
                                            ORDER BY id ASC
                                            LIMIT 1');


            $countBlock = sizeof($blockchain);
            if ($countBlock != 0){
                $a = DB::select('select *
                                            from  billchains
                                            ORDER BY id ASC
                                            LIMIT 1');
                $hoangChainLast = json_decode($a[0]->blockchain);
                $hoangChain = new Blockchain(2);
                $hoangChain->chain = $hoangChainLast->chain;
                $hoangChain->difficulty = $hoangChainLast->difficulty;


                $value = $hoangChain->getLastBlock()->hash;

                $hoangChain->addBlock($arrayValue);
                $valid = $hoangChain->isValid();

                if ($valid == false){
                    Session::flash("error",'Chuỗi khối bị can thiệp');
                    return false;
                }

                $valueAdd = $hoangChain->getLastBlock()->hash;
                $minevar = $hoangChain->getLastBlock()->mineVar."phuc";
                $valueAdd = $valueAdd.$minevar;

//                            dd($valueAdd);

                ## ---------------------------- Generate QR code and Save Off-Chain in MySQL  -----------------------------
                $blockString = md5($hoangChain->getLastBlock()->hash);
                $blockData = json_encode($hoangChain->getLastBlock()->data);

                $result = $this->blockchainService->update($a[0]->id, json_encode($hoangChain));
                Product::where('id',$id_product)->update(array(
                    'block'=>$blockString,
                    'block_number'=>(count($hoangChain->chain)-1)
                ));


                $qrCodePath = public_path('public/qrcode/qrcode' . (count($hoangChain->chain) - 1) . '.png');


                $image = \QrCode::format('png')
                    ->size(200)
                    ->generate("http://127.0.0.1:8000/checkBlock/".(count($hoangChain->chain)-1)."/".$blockString);


                $output_file = '/img/qr-code/img-'.(count($hoangChain->chain)-1).'.png';
                Storage::disk('public')->put($output_file, $image);

                $img_link = '/storage/img/qr-code/img-'. (count($hoangChain->chain)-1) . '.png';

                Bill::where('id',$id)->update(array(
                    'thumb'=>$img_link
                ));
            }
            else{
                $hoangChain = new Blockchain(2);
                $hoangChain->addBlock($arrayValue);
                $valid = $hoangChain->isValid();
                if ($valid != true ){
                    Session::flash("error",'Chuỗi khối bị can thiệp');
                    return false;
                }

//                            dd("success");

                $value = $hoangChain->getLastBlock()->hash;
                $minevar = $hoangChain->getLastBlock()->mineVar."phuc";
                $valueAdd = $value.$minevar;

                $this->blockchainService->create(json_encode($hoangChain));
//                            dd($hoangChain);

                ## ---------------------------- Generate QR code and Save Off-Chain in MySQL ----------------------------------
                $blockString = md5($hoangChain->getLastBlock()->hash);
                $blockData = json_encode($hoangChain->getLastBlock()->data);

                Product::where('id',$id_product)->update(array(
                    'block'=>$blockString,
                    'block_number'=>(count($hoangChain->chain)-1)
                ));

                $qrCodePath = public_path('public/qrcode/qrcode' . (count($hoangChain->chain) - 1) . '.png');
                $image = \QrCode::format('png')
                    ->size(200)
                    ->generate("http://127.0.0.1:8000/checkBlock/".(count($hoangChain->chain)-1)."/".$blockString);

                $output_file = '/img/qr-code/img-'.(count($hoangChain->chain)-1).'.png';
                Storage::disk('public')->put($output_file, $image);


                $img_link = '/storage/img/qr-code/img-'.(count($hoangChain->chain)-1).'.png';

                Bill::where('id',$id)->update(array(
                    'thumb'=>$img_link
                ));
            }

            return true;
        }catch (\Exception $err){
            Session::flash("error",'Lỗi');
            return false;
        }
    }

    public function update($request, $billreceived){
        try {
            $arrsaleroom= array();
            for ($i= 0; $i < count($request->input('saleroom')); $i++){
                array_push($arrsaleroom, $request->input('saleroom')[$i]);
            }
            $arrSaleroom = implode(",",$arrsaleroom);

            $billreceived->list_saleroom = $arrSaleroom;

            $billreceived->save();
            Session::flash('success', 'Cập nhật thành công !');
        }
        catch (\Exception $err){
            Session::flash('error','Lỗi');
            return false;
        }
        return true;
    }

    public function destroy($request){
        $id = (int)$request->input('id');
        $billreceived = Billreceived::where('id', $id)->first();
        if ($billreceived) {
            $billreceived->delete();
            return true;
        }
        return false;
    }
}
