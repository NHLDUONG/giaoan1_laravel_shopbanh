<?php

namespace App\Http\Controllers;
use App\Slide;
use App\Product;
use App\ProductType;
use App\Cart;
use Session;
use App\Customer;
use App\Bill;
use App\BillDetail;
use App\User;

use Hash;
use Auth;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function getIndex() {
        $slide = Slide::all();
        //print_r($slide);  
        //exit;
        //return view('page.trangchu',['slide'=>'$slide']); cách 2
        $new_product = Product::where('new',1)->paginate(4);
        $sanpham_khuyenmai = Product::where('promotion_price','<>',0)->paginate(8);
        
        return view('page.trangchu', compact ('slide','new_product','sanpham_khuyenmai'));
    }

    
    public function getLoaiSp($type){
        $sp_theoloai = Product::where('id_type',$type)->get();
        $sp_khac = Product::where('id_type','<>',$type)->paginate(3);
        $loai = ProductType::all();
        $loai_sp = ProductType::where('id',$type)->first();
        return view('page.loai_sanpham',compact('sp_theoloai','sp_khac','loai','loai_sp'));
    }

    public function getChitiet(Request $req){
        $sanpham = Product::where ('id',$req->id)->first();
        $sp_tuongtu = Product::where ('id_type',$sanpham->id_type)->paginate(6);
        $sanpham_khuyenmai = Product::where('promotion_price','<>',0)->paginate(4);
        $new_product = Product::where('new',1)->paginate(4);
        return view('page.chitiet_sanpham', compact ('sanpham','sp_tuongtu','sanpham_khuyenmai','new_product'));
    }

    public function getLienHe(){
        return view('page.LienHe');
    }

    public function getGioiThieu(){
        return view('page.GioiThieu');
    }
    public function getproduct(Request $req,$id){
        $product = Product::find($id);
        return view('page.product', compact ('product'));
    }
    public function getAddtoCart(Request $req,$id){ // khi bấm vào nút mua hàng thỳ nó sẽ gọi hàm này 
       $product = Product::find($id);// tìm kiếm sản phẩm theo id truyền vào.
       $oldCart = Session('cart')?Session::get('cart'):null; // chỗ này là biểu thức luận lí , nếu như có cái Session('cart') thỳ gán oldCart =  Session::get('cart') ngược lại gán oldCart = null . này viết theo biểu thức luận lí cho nó ngắn gọn chuk viết dài dòng thỳ như vần nek e.

       // $oldCart = "";  
       // if(Session('cart')){
       //     $oldCart = Session::get('cart');
       // }else{
       //     $oldCart = null;
       // }

       $cart = new Cart($oldCart);// tạo giỏ hàng mới (new Cart tức là nó gọi cái file Cart.php nó chạy vào cái hàm __contruct để khởi tạo giỏ hàng)
       $cart->add($product,$id); // thêm sản phẩm vào giỏ hàng vừa khởi tạo.
       $req->session()->put('cart',$cart); //thêm giỏ hàng vừa tạo vào session để lưu tạm.
       return redirect()->back(); //quay về trang hiện tại.
    }

    public function getDelItemCart($id) { // hàm xóa sản phẩm trong giỏ hàng
        $oldCart = Session::has('cart')?Session::get('cart'):null; // chỗ này là biểu thức luận lí , nếu như có cái Session('cart') thỳ gán oldCart =  Session::get('cart') ngược lại gán oldCart = null . này viết theo biểu thức luận lí cho nó ngắn gọn chuk viết dài dòng thỳ như vần nek e.
        
       // $oldCart = "";  
       // if(Session('cart')){
       //     $oldCart = Session::get('cart');
       // }else{
       //     $oldCart = null;
       // }
        $cart = new Cart($oldCart); //  
        $cart->removeItem($id); //xóa sản phẩm có id ra khỏi giỏ hàng
        if(count($cart->items)>0){ // nếu như sau khi xóa xong mak giỏ hàng còn sản phẩm trong đó thỳ mình gán lại session giỏ hàng.
            Session::put('cart',$cart); //gán lại giỏ hàng
        }
        else{ 
            Session::forget('cart'); // nếu xóa sạch giỏ hàng hk còn sản phẩm nào thỳ xóa lun cái session.
        }
        return redirect()->back(); // quay về trang hiện tại.
    }

    public function getCheckout(){
        return view('page.dat-hang');
    }

    public function postCheckout(Request $req){
        $cart = Session::get('cart');
        
        $customer = new Customer;
        $customer->name = $req->name;
        $customer->gender = $req->gender;
        $customer->email = $req->email;
        $customer->address = $req->address;
        $customer->phone_number = $req->phone;
        $customer->note = $req->notes;
        $customer->save();

        $bill = new Bill;
        $bill->id_customer = $customer->id;
        $bill->date_order = date('Y-m-d');
        $bill->total = $cart->totalPrice;
        $bill->payment = $req->payment_method;
        $bill->note = $req->notes;
        $bill->save();

        foreach ($cart->items as $key => $value) {
            $bill_detail = new BillDetail;
            $bill_detail->id_bill = $bill->id;
            $bill_detail->id_product = $key;
            $bill_detail->quantily = $value['qty'];
            $bill_detail->unit_price = ($value['price']/$value['qty']);
            $bill_detail->save();
    
        }
        Session::forget('cart');
        return redirect()->back()->with('thongbao','Đặt hàng thành công');
       
    } 

    public function getLogin(){
        return view('page.dangnhap');
    }

    public function getSignin(){
        return view('page.dangki');
    }

    public function postSignin(Request $req){ //đây là pót đăng kí.
        $this->validate($req,
        [
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|max:20',
            'full_name'=>'required',
            're_password'=>'required|same:password'
        ],
        [
            'email.required'=>'Vui lòng nhập email',
            'email.email'=>'Không đúng định dạng email',
            'email.unique'=>'Email đã có người sử dụng',
            'password.required'=>'Vui lòng nhập mật khẩu',
            're_password.same'=>'Mật khẩu không giống nhau',
            'password.min'=>'Mật khẩu ít nhất 6 kí tự',
        ]);
        $user = new User();
        $user->full_name =$req->full_name;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->phone = $req->phone;
        $user->address = $req->address;
        $user->save();
        return redirect()->back()->with('thanhcong','Tạo tài khoản thành công');

    }

    public function postLogin(Request $req){
        $this->validate($req,
        [
            'email'=>'required|email|',
            'password'=>'required|min:6|max:20'
        ],
        [
            'email.required'=>'Vui lòng nhập email',
            'email.email'=>'Email không đúng định dạng',
            'password.required'=>'Vui lòng nhập mật khẩu',
            'password.min'=>'Mật khẩu ít nhất 6 kí tự',
            'password.max'=>'Mật khẩu không quá 6 kí tự'
        ]
        );
        $credentials = array('email'=>$req->email,'password'=>$req->password);
        if(Auth::attempt($credentials)){
            return redirect()->back()->with(['flag'=>'success','message'=>'Đăng nhập thành công']);
        }
        else{
            return redirect()->back()->with(['flag'=>'danger','message'=>'Đăng nhập không thành công']);
        }

    }

    public function postLogout(){
        Auth::logout();
        return redirect()->route('trangchu');
    }

    public function getSearch(Request $req){
        $product = Product::where('name','like','%'.$req->key.'%')
                            ->orWhere('unit_price',$req->key)
                            ->get();
        return view('page.search',compact('product'));
    }

    
}
