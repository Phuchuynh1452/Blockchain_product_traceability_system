<?php

namespace App\Http\Services;
use App\Models\Billchain;
use Illuminate\Support\Facades\Session;

class BlockChainService
{
    public function getBlockchain(){

    }

    public function create($blockchainString){
        Billchain::create([
            'blockchain'=>(string)$blockchainString,
        ]);
    }

    public function update($id, $blockchainString){
        try {
            Billchain::where('id',$id)->update(['blockchain' => $blockchainString]);
            Session::flash('success', 'Cập nhật thành công !');
        }
        catch (\Exception $err){
            Session::flash('error','Lỗi');
            return false;
        }
        return true;
    }


}
