<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Saleroom\SaleroomService;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SaleroomController extends Controller
{
    protected $saleroomService;
    public function __construct(SaleroomService $saleroomService)
    {
        $this->saleroomService = $saleroomService;
    }

    public function index(){
        $salerooms = $this->saleroomService->get();
        return view('admin.salerooms.list',[
            "title"=>"Cửa Hàng",
            "salerooms"=>$salerooms
        ]);
    }
    public function create(){
        return view('admin.salerooms.add',[
            "title"=>"Cửa Hàng",
        ]);
    }


    public function store(Request $request){
        if(session()->get('perr') == 1){
            $result = $this->saleroomService->create($request);
            if($result){
                $salerooms = $this->saleroomService->get();
                return view('admin.salerooms.list',[
                    "title"=>"Saleroom",
                    "salerooms"=>$salerooms
                ]);
            }else{
                return redirect()->back();
            }
        }else{
            return redirect()->back();
        }
    }




    public function show(Market $salesroom){
        return view('admin.salerooms.edit',[
            'title' => 'Chỉnh sửa Cửa Hàng: '. $salesroom->name,
            'saleroom' => $salesroom
        ]);
    }

    public function update(Request $request, Market $salesroom)
    {
        $result = $this->saleroomService->update($request, $salesroom);
        if($result){
            return redirect()->route('admin.salerooms.list');
        }
        else
            return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $result = $this->saleroomService->destroy($request);
        if($result){
            return response()->json([
                'error' =>false,
                'message' => 'Xóa Cửa Hàng thành công!'
            ]);
        }
        return response()->json(['error'=>true]);
    }
}
