<?php

namespace App\Http\Services\Saleroom;

use App\Helpers\GlobalVariable;
use App\Http\BlockChain\Blockchain;
use App\Models\Billreceived;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Salesroomchain;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Web3\Contract;
use Web3\Web3;

class SaleroomService
{
    public function get(){
        return Market::all();
    }

    public function create($request){
        $a = DB::select('select * from markets where businesscode ="'.$request->input('businesscode').'"');

        try {
            if (sizeof($a) == 0){
                Market::create([
                    'businesscode'=>(string)$request->input('businesscode'),
                    'name'=>(string)$request->input('name'),
                    'boss_name'=>(string)$request->input('boss_name'),
                    'phone'=>(string)$request->input('phone'),
                    'address'=>(string)$request->input('address'),
                    'thumb'=>(string)$request->input('thumb'),
                ]);

                $salerooms = DB::select('SELECT * FROM `markets` ORDER BY id DESC  LIMIT 1 ');
                $madoanhnghiep = $salerooms[0]->businesscode;
                $tencoso = $salerooms[0]->name;
                $diachi = $salerooms[0]->address;
                $thumb_saleroom = $salerooms[0]->thumb;
                $phone = $salerooms[0]->phone;
                $created_at = $salerooms[0]->created_at;

                $arrayValue = [ "madoanhnghiep"=>$madoanhnghiep, "tencoso"=>$tencoso, "diachi"=>$diachi,
                                "thumb_saleroom"=>$thumb_saleroom, "phone"=>$phone, "created_at"=>$created_at];

                return true;
            }else{
                Session::flash("error","Đã có Saleroom này rồi");
                return false;
            }
        }catch (\Exception $err){
            Session::flash("error",'Lỗi');
            return false;
        }
    }

    public function update($request, $saleroom){
        try {
            //-----------------------
            $saleroom->businesscode=(string)$request->input('businesscode');
            $saleroom->name=(string)$request->input('name');
            $saleroom->boss_name=(string)$request->input('boss_name');
            $saleroom->phone=(string)$request->input('phone');
            $saleroom->address=(string)$request->input('address');
            $saleroom->thumb=(string)$request->input('thumb');
            $saleroom->save();
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
        $saleroom = Market::where('id', $id)->first();
        if ($saleroom) {
            $saleroom->delete();
            return true;
        }
        return false;
    }
}
