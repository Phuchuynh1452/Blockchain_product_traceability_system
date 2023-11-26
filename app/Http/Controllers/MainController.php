<?php

namespace App\Http\Controllers;


use App\Http\BlockChain\Blockchain;
use App\Http\Services\Cart\CartService;
use App\Http\Services\Menu\MenuService;
use App\Http\Services\Product\ProductService;
use App\Http\Services\Slider\SliderService;
use App\Models\Market;
use App\Models\Product;
use App\Models\Salesroom;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class MainController extends Controller
{
    protected $slider;
    protected $menu;
    protected $product;
    protected $cartService;

//    public function __construct(SliderService $slider, MenuService $menu, ProductService $product, CartService $cartService)
//    {
//        $this->slider = $slider;
//        $this->menu = $menu;
//        $this->product = $product;
//        $this->cartService =$cartService;
//    }

    public function index(){

        dd("Hhi");
        return view('userView',[
        ]);
    }

    public function checkBlock($number, $blockchaindb){

        #========================================Backup====================================#
        try {
            if (!is_numeric($number)){
                return view('warning');
            }else{
//            dd("hello");
                $a = DB::select('select * from billchains');
                if (sizeof($a) <= 0){
                    dd("Data is null");
                }
                $blockchain = $a[0]->blockchain;
                $blockchain = json_decode($blockchain);
                if (sizeof(($blockchain->chain)) <= (int)$number ){
                    return view('warning',[
                        'title'=>"Cánh Báo Mã QR Code Đã Bị Can Thiệp!",
                        'content'=>"Chúng tôi phát hiện mã QR code bạn vừa quét là giả hoặc đã bị can thiệp sửa đổi. Chúng tôi không có bất kỳ trách nhiệm nào đối với sản phẩm này. Nếu bạn muốn mua sản phẩm của chúng tôi vui lòng truy cập trang web nongsanvinhlongsv.click hoặc đến trực tiếp chuỗi cửa hàng của chúng tôi. Xin cảm ơn!",
                    ]);
                }
//            dd("hi");
                $hoangChainLast = json_decode($a[0]->blockchain);
                $hoangChain = new Blockchain(2);
                $hoangChain->chain = $hoangChainLast->chain;
                $hoangChain->difficulty = $hoangChainLast->difficulty;
                $valid = $hoangChain->isValid();
                if ($valid == false){
                    dd("1");
                }
                if ($blockchaindb == md5($hoangChain->chain[(int)$number]->hash) && $number < sizeof($hoangChainLast->chain)){
                    $today = \Carbon\Carbon::now();
                    if($hoangChain->chain[(int)$number]->data->shelf_life < $today){
                        return  view('warning',[
                            "title"=>"Sản Phẩm Hết Hạn Sử Dụng!",
                            "content"=>"Sản phẩm này đã hết hạn sử dụng vào ngày ".$hoangChain->chain[(int)$number]->data->shelf_life.". \n Bạn vui lòng liên hệ nhân viên để được giúp đỡ!"
                        ]);
                    }
                    $id_product = $hoangChain->chain[(int)$number]->data->id_product;
                    $id_bill = $hoangChain->chain[(int)$number]->data->id_bill;

                    $billreceiveds = DB::select('select * from bills where id ='.$id_bill);
                    $salerooms = Market::all();

                    $info_product = DB::select('select products.id as id_product, products.name as name_product, products.description as des_product, products.detail as detail_product, products.thumb as thumb_product,
                                                     suppliers.thumb as thumb_supplier, suppliers.madoanhnghiep as madoanhnghiep_supplier, suppliers.tencoso as tencoso_supplier, suppliers.mota as mota_supplier, suppliers.diachi as diachi_supplier, suppliers.sodienthoai as sodienthoai_supplier
                                              from  products, suppliers
                                              where products.supplier_id = suppliers.id and products.id = '.$id_product);

                    return view('userView',[
                        'number'=>$number,
                        'blockUser'=>$hoangChain->chain[(int)$number]->data,
                        'billreceiveds'=>$billreceiveds,
                        'salerooms'=>$salerooms
                    ]);
                }else{
                    return view('warning',[
                        'title'=>"Cánh Báo Mã QR Code Đã Bị Can Thiệp!",
                        'content'=>"Chúng tôi phát hiện mã QR code bạn vừa quét là giả hoặc đã bị can thiệp sửa đổi. Chúng tôi không có bất kỳ trách nhiệm nào đối với sản phẩm này. Nếu bạn muốn mua sản phẩm của chúng tôi vui lòng truy cập trang web nongsanvinhlongsv.click hoặc đến trực tiếp chuỗi cửa hàng của chúng tôi. Xin cảm ơn!",
                    ]);
                }
            }
        }catch (\Exception $e) {
            // Handle any exceptions that occur during the migration
            dd($e);

        }
    }


    public function loadProduct(Request $request)
    {
        $page = $request->input('page', 0);
        $result = $this->product->getmain($page);
        if ( count($result) != 0) {
            $html = view('products.list', ['products' => $result ])->render();
            return response()->json([ 'html' => $html ]);
        }
        return response()->json(['html' => '' ]);
    }

    public function test(){
        return view('test',[]);
    }

    public function about(){
        return view('about',[
            'title' => 'Thông tin chúng tôi'
        ]);
    }

    public function contact(){
        return view('contact',[
            'title' => 'Liên hệ'
        ]);
    }

    public function sendMess(Request $request){
        Mail::send('mail.sendUsMess', ['customer' => $request], function ($m) use ($request) {
            $m->to('lethanhuy1005@gmail.com')->subject('Phản hồi từ khách hàng!');
        });
        return redirect()->back();
    }

    public function viewSearch(){
        return view('search',[
            'title' => 'Tim kiem',
            'result' => null
        ]);
    }

    public function search(Request $request){

        // Get the search value from the request
        $search = $request->input('search');
        $now = Carbon::now()->dayOfYear;
        if ($search == ""){
            return view('search', [
                'title' => 'Search',
                'search' => $search,
                'dayOfYear' => $now
            ]);
        }
        // Search in the title and body columns from the posts table
        $posts = Product::where('name', 'LIKE', "%{$search}%")
            ->where('active',1)
            ->orderByDesc('created_at')
            ->get();
        // Return the search view with the resluts compacted

        return view('search', [
            'title' => 'Search',
            'search' => $search,
            'result' => $posts,
            'dayOfYear' => $now
        ]);
    }

}
