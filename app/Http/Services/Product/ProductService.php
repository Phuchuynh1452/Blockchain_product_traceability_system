<?php

namespace App\Http\Services\Product;

use App\Models\Farmer;
use App\Models\img_products;
use App\Models\Menu;
use App\Models\Product;
use App\Models\Seedsandseedling;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductService
{

    public function get(){
        $result = DB::select('select products.id, products.name, products.description, products.detail, products.price, products.thumb
                                    from products');
        return $result;
    }

    public function getMenu(){
        return Menu::all();
    }

    public function getFarmer(){
        return Farmer::all();
    }

    public function getSupplier(){
        return Supplier::all();
    }

    public function getSeedsandSeedling(){
        return Seedsandseedling::all();
    }

    public function getId($id){
        $result = DB::select('select *
                                    from  fishs
                                    where fishs.id = '.$id);
        return $result;
    }

    public function create($request){
        try {
            Product::create([
                'name'=>(string)$request->input('name'),
                'description'=>(string)$request->input('description'),
                'detail'=>(string)$request->input('detail'),
                'price'=>(string)$request->input('price'),
                'quantity'=>$request->input('quantity'),
                'thumb'=>(string)$request->input('thumb'),
                'supplier_id'=>$request->input('supplier_id'),
                'farmer_id'=>$request->input('farmer_id'),
                'menu_id'=>$request->input('menu_id'),
            ]);
            Session::flash("success","Thêm thành công");
            return true;
        }catch (\Exception $err){
            Session::flash("error",'Lỗi');
            return false;
        }
    }

    public function update($request, $product){
        try {
            //-----------------------
            $product->name = (string)$request->input('name');
            $product->description = (string)$request->input('description');
            $product->detail = (string)$request->input('detail');
            $product->price = (string)$request->input('price');
            $product->quantity = $request->input('quantity');
            $product->thumb = (string)$request->input('thumb');
            $product->supplier_id = $request->input('supplier_id');
            $product->farmer_id = $request->input('farmer_id');
            $product->menu_id = $request->input('menu_id');
            $product->save();
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
        $product = Product::where('id', $id)->first();
        if ($product) {
            $product->delete();
            return true;
        }
        return false;
    }

    //Product Main
    const LIMIT = 8;

    public function getmain($page = null)
    {
        return Product::select('id', 'name', 'description', 'detail', 'price', 'thumb', 'block', 'block_number', 'quantity' , 'id_crop', 'created_at', 'updated_at')
            ->where('price','>',0)
            ->where('active','>',0)
            ->orderByDesc('created_at')
            ->when($page != null, function ($query) use ($page) {
                $query->offset($page * self::LIMIT);
            })
            ->limit(self::LIMIT)
            ->get();
    }


    public function show($id){
        return Product::where('id', $id)
            ->where('active', 1)
            ->with('menu')
            ->firstOrFail();
    }

    public function more($product){
        $menu_id = Menu::select('id','parent_id')->where('id',$product->menu_id)->firstOrFail();
        if ($menu_id->parent_id==0){
            $menu = Menu::where('parent_id', $menu_id->id)
                ->where('active',1)
                ->orWhere('id',$menu_id->id)
                ->where('active',1)
                ->pluck('id')->toArray();
        }else
        {
            $menu = Menu::where('parent_id', $menu_id->parent_id)
                ->where('active',1)
                ->orWhere('id',$menu_id->parent_id)
                ->where('active',1)
                ->pluck('id')->toArray();
        }
        return Product::select('id', 'name', 'price', 'thumb','quantity', 'created_at')
            ->where('price','>',0)
            ->where('active', 1)
            ->where('id','!=', $product->id)
            ->whereIn('menu_id', $menu)
            ->orderByDesc('quantity')
            ->limit(4)
            ->get();
    }

    public function showImg($id)
    {
        return img_products::select('id','product_id','thumb1')
            ->where('product_id',$id)
            ->get();
    }
}
