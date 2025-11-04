<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Product;
use App\Models\ProductImport;
use App\Models\ProductInStock;
use App\Models\ProductImportDetail;
use App\Models\Supplier;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;

session_start();

class ProductImportController extends Controller {
	public function Index() {
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $this->UpdateIdImportDetail();
            $all_product_import = ProductImport::orderby('id', 'desc')->paginate(5);
            return view('admin.pages.product_import.product_import')->with('all_product_import', $all_product_import);
        }
    }
    public function UpdateIdImportDetail(){
        $all_detail=ProductImportDetail::all();
        $all_import=ProductImport::all();
        foreach($all_import as $key=>$import){
            foreach($all_detail as $k=>$detail){
                if($import->donnhaphang_ma_don_nhap_hang==$detail->chitietnhap_ma_don_nhap_hang){
                    $detail_update=ProductImportDetail::find($detail->id);
                    $detail_update->donnhaphang_id=$import->id;
                    $detail_update->save();
                }
            }
        }
    }
	public function AuthLogin() {
		$admin_id = Session::get('admin_id');
		if ($admin_id) {
			return Redirect::to('/dashboard');
		} else {
			return Redirect::to('/admin')->send();
		}
	}
	public function ProductImportAddMultiple() {
		$this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $this->UpdateIdImportDetail();
            $admin = Admin::where('user_id', Session::get('admin_id'))->get();
            $all_product = Product::orderby('tbl_sanpham.id', 'desc')->paginate(5);
            $all_supplier = Supplier::orderby('id', 'desc')->get();
           
            return view('admin.pages.product_import.product_import_add_multiple')
            ->with('all_product', $all_product)
            ->with('get_admin', $admin)
         
            ->with('all_supplier', $all_supplier);
        }
	}
	public function ProductImportAddQueue(Request $request) {
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $data = $request->all();
            $session_id = substr(md5(microtime()) . rand(0, 26), 5);
            $queue = Session::get('queue');
            $price = 1;
            $qty = 1;
            if ($queue) {
                $queue[] = array(
                    'session_id' => $session_id,
                    'product_name' => $data['product_name'],
                    'product_image' => $data['product_image'],
                    'product_id' => $data['product_id'],
                    'product_quantity' => $qty,
                    'product_price' => $price,
                    'product_total' => $price * $qty,
                );
            } else {
                $queue[] = array(
                    'session_id' => $session_id,
                    'product_name' => $data['product_name'],
                    'product_image' => $data['product_image'],
                    'product_id' => $data['product_id'],
                    'product_quantity' => $qty,
                    'product_price' => $price,
                    'product_total' => $price * $qty,
                );
            }
            Session::put('queue', $queue);
            Session::save();
        }
    }

	public function ProductImportDeleteRowQueue(Request $request) {
		$this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $data = $request->all();
            $queue = Session::get('queue');
            if ($queue == true) {
                foreach ($queue as $key => $value) {
                    if ($value['session_id'] == $data['product_session_id']) {
                        unset($queue[$key]);
                    }
                }
                Session::put('queue', $queue);
                Session::save();
            }
        }
	}

	public function ProductImportAddMultipleSave(Request $request)
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $data = $request->all();

        $this->validate($request, [
            'product_import_no' => 'bail|required|max:255|min:6',
        ], [
            'required' => 'Không được để trống',
            'min' => 'Quá ngắn',
            'max' => 'Quá dài',
        ]);

        $admin = Admin::where('user_id', Session::get('admin_id'))->first();

        if (ProductImport::where('donnhaphang_ma_don_nhap_hang', $data['product_import_no'])->exists()) {
            return Redirect::to('/product-import-add-multiple')->with('error', 'Thêm không thành công, đơn nhập đã tồn tại!');
        }

        $product_import = new ProductImport();
        $product_import->donnhaphang_ma_don_nhap_hang = $data['product_import_no'];
        $product_import->donnhaphang_ngay_nhap = $data['product_import_day'];
        $product_import->donnhaphang_trang_thai = $data['product_import_status'];
        $product_import->nhacungcap_id = $data['product_import_supplier'];
        $product_import->admin_id = $admin->id;

        $queue = Session::get('queue');

        // Cập nhật giá và số lượng cho từng sản phẩm trong queue
        foreach ($data['session_id'] as $key => $value) {
            foreach ($queue as $k => $v) {
                if ($v['session_id'] == $key) {
                    $queue[$k]['product_quantity'] = $data['product_quantity'][$key];
                    $queue[$k]['product_price'] = $data['product_price'][$key];
                    $queue[$k]['product_total'] = $data['product_total'][$key];
                }
            }
        }

        $total = 0;

        foreach ($queue as $value) {
            $product_id = $value['product_id'];

            $get_product_import_detail = ProductImportDetail::where('sanpham_id', $product_id)
                ->where('chitietnhap_ma_don_nhap_hang', $data['product_import_no'])
                ->first();

            $get_product_in_stock = ProductInstock::where('sanpham_id', $product_id)->first();

            // Nếu sản phẩm chưa từng có trong kho
            if (!$get_product_import_detail && !$get_product_in_stock) {
                $import_product_detail = new ProductImportDetail();
                $import_product_detail->chitietnhap_so_luong_nhap = $value['product_quantity'];
                $import_product_detail->chitietnhap_gia_nhap = $value['product_price'];
                $import_product_detail->sanpham_id = $product_id;
                $import_product_detail->chitietnhap_ma_don_nhap_hang = $data['product_import_no'];
                $import_product_detail->save();

                $product_in_stock = new ProductInstock();
                $product_in_stock->sanpham_id = $product_id;
                $product_in_stock->sanphamtonkho_so_luong_da_ban = 0;
                $product_in_stock->sanphamtonkho_so_luong_ton = $value['product_quantity'];
                $product_in_stock->save();

                $total += $value['product_total'];
            } else {
                // Đã tồn tại trong kho => chỉ cập nhật số lượng + thêm chi tiết nhập mới
                $import_product_detail = new ProductImportDetail();
                $import_product_detail->chitietnhap_so_luong_nhap = $value['product_quantity'];
                $import_product_detail->chitietnhap_gia_nhap = $value['product_price'];
                $import_product_detail->sanpham_id = $product_id;
                $import_product_detail->chitietnhap_ma_don_nhap_hang = $data['product_import_no'];
                $import_product_detail->save();

                $product_in_stock_update = ProductInstock::find($get_product_in_stock->id);
                $product_in_stock_update->sanphamtonkho_so_luong_ton += $value['product_quantity'];
                $product_in_stock_update->save();

                $total += $value['product_total'];
            }
        }

        $product_import->donnhaphang_tong_tien = $total;
        $product_import->save();

        Session::forget('queue');

        return Redirect::to('/product-import')->with('message', 'Thêm thành công!');
    }


	public function UnactiveProductImport($product_import_id)
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $active_product_import = ProductImport::find($product_import_id);
        if (!$active_product_import) {
            return Redirect::to('/product-import')->with('error', 'Không tồn tại!');
        }

        $all_import_detail = ProductImportDetail::where('donnhaphang_id', $product_import_id)->get();
        $active_product_import->donnhaphang_trang_thai = 0;

        $count = 0;

        // Kiểm tra tồn kho đủ để hủy nhập (trừ lại số lượng)
        foreach ($all_import_detail as $value) {
            $product_in_stock = ProductInStock::where('sanpham_id', $value->sanpham_id)->first();
            if ($product_in_stock && $value->chitietnhap_so_luong_nhap <= $product_in_stock->sanphamtonkho_so_luong_ton) {
                $count++;
            } else {
                return Redirect::to('/product-import')->with('error', 'Hủy đơn nhập không thành công!');
            }
        }

        if ($count == $all_import_detail->count()) {
            foreach ($all_import_detail as $val) {
                $product_in_stock = ProductInStock::where('sanpham_id', $val->sanpham_id)->first();
                if ($product_in_stock) {
                    $product_in_stock->sanphamtonkho_so_luong_ton -= $val->chitietnhap_so_luong_nhap;
                    $product_in_stock->save();
                }
            }
        } else {
            return Redirect::to('/product-import')->with('error', 'Hủy đơn nhập không thành công!');
        }

        $active_product_import->save();
        return Redirect::to('/product-import')->with('message', 'Hủy đơn nhập thành công!');
    }

	public function ActiveProductImport($product_import_id)
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $active_product_import = ProductImport::find($product_import_id);
        if (!$active_product_import) {
            return Redirect::to('/product-import')->with('error', 'Không tồn tại!');
        }

        $all_import_detail = ProductImportDetail::where('donnhaphang_id', $product_import_id)->get();

        $active_product_import->donnhaphang_trang_thai = 1;

        foreach ($all_import_detail as $value) {
            $product_in_stock = ProductInStock::where('sanpham_id', $value->sanpham_id)->first();

            if ($product_in_stock) {
                $product_in_stock->sanphamtonkho_so_luong_ton += $value->chitietnhap_so_luong_nhap;
                $product_in_stock->save();
            }
        }

        $active_product_import->save();

        return Redirect::to('/product-import')->with('message', 'Hoàn tác thành công!');
    }


    public function DeleteProductImport($product_import_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $active_product_import = ProductImport::find($product_import_id);
            if (!$active_product_import) {
                return Redirect::to('/product-import')->with('error', 'Không tồn tại!');
            } else {
                if($active_product_import->donnhaphang_trang_thai==1){
                    return Redirect::to('/product-import')->with('error', 'Xóa không thành công!');
                }elseif($active_product_import->donnhaphang_trang_thai==0){
                    $all_import_detail=ProductImportDetail::where('donnhaphang_id',$product_import_id)->get();
                    foreach ($all_import_detail as $key => $value) {
                        $delete_detail=ProductImportDetail::find($value->id);
                        $delete_detail->delete();
                    }
                    $active_product_import->delete();
                    return Redirect::to('/product-import')->with('message', 'Xóa thành công!');
                }
            }
        }
    }

    public function ProductImportAdd(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $admin = Admin::where('user_id', Session::get('admin_id'))->get();
            $all_supplier = Supplier::orderby('id', 'desc')->get();
            $all_product_import = ProductImport::where('donnhaphang_trang_thai', 0)->paginate(5);
            return view('admin.pages.product_import.product_import_add')
        ->with('get_admin', $admin)
        ->with('all_product_import', $all_product_import)
        ->with('all_supplier', $all_supplier);
        }
    }

    public function ProductImportAddSave(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $admin = Admin::where('user_id', Session::get('admin_id'))->first();
            $all_product_import = ProductImport::where('donnhaphang_ma_don_nhap_hang', '=', $data['product_import_no'])->exists();
            if ($all_product_import) {
                return Redirect::to('/product-import-add')->with('error', 'Add Fail, Already Exist');
            } else {
                $product_import = new ProductImport();
                $product_import->donnhaphang_ma_don_nhap_hang = $data['product_import_no'];
                $product_import->donnhaphang_ngay_nhap = $data['product_import_day'];
                $product_import->donnhaphang_trang_thai = $data['product_import_status'];
                $product_import->nhacungcap_id = $data['product_import_supplier'];
                $product_import->admin_id = $admin->id;
                $product_import->donnhaphang_tong_tien = 0;
                $product_import->save();
            }
            return Redirect::to('/product-import-add')->with('message', 'Add Success');
        }
    }


	public function ProductImportEdit($product_import_id) {
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $admin = Admin::where('user_id', Session::get('admin_id'))->get();
            $all_product = Product::orderby('tbl_sanpham.id', 'desc')->get();
            $all_supplier = Supplier::orderby('id', 'desc')->get();
            $product_import = ProductImport::find($product_import_id);
            $get_product_import_detail = ProductImportDetail::where('chitietnhap_ma_don_nhap_hang', $product_import->donnhaphang_ma_don_nhap_hang)->get();
            return view('admin.pages.product_import.product_import_edit')
            ->with('all_product', $all_product)
            ->with('get_admin', $admin)
            ->with('product_import', $product_import)
            ->with('get_product_import_detail', $get_product_import_detail)
            ->with('all_supplier', $all_supplier);
        }
    }

    public function ProductImportShowDetail($product_import_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $product_import=ProductImport::find($product_import_id);
            if (!$product_import) {
                return Redirect::to('/product-import-add')->with('error', 'Không tồn tại!');
            } else {
                $product_import_detail=ProductImportDetail::where('donnhaphang_id', $product_import_id)->get();
                return view('admin.pages.product_import.product_import_show_detail')
                ->with('product_import', $product_import)
                ->with('product_import_detail', $product_import_detail);
            }
        }
    }

	public function ProductImportEditSave(Request $request, $product_import_id) {
		$this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $product_import=ProductImport::find($product_import_id);
            if (!$product_import) {
                return Redirect::to('/product-import-add')->with('error', 'Không tồn tại!');
            } else {
                $data = $request->all();
                $all_product_import = ProductImport::where('donnhaphang_ma_don_nhap_hang', '=', $data['product_import_no'])->whereNotIn('id', [$product_import_id])->exists();
                if ($all_product_import) {
                    return Redirect::to('/product-import-edit/'.$product_import_id)->with('error', 'Cập nhật không thành công, đã tồn tại đơn nhập!');
                } else {
                    $product_import = ProductImport::find($product_import_id);
                    $product_import->donnhaphang_ngay_nhap = $data['product_import_day'];
                    $product_import->donnhaphang_trang_thai = $data['product_import_status'];
                    $product_import->nhacungcap_id = $data['product_import_supplier'];
                    $product_import->save();
                    return Redirect::to('/product-import')->with('message', 'Cập nhật thành công!');
                }
            }
        }
	}
    public function ProductImportAddDetail($product_import_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $product_import=ProductImport::find($product_import_id);
            if (!$product_import) {
                return Redirect::to('/product-import-add')->with('error', 'Không tồn tại!');
            } else {
                $all_product = Product::orderBy('id', 'DESC')->get();
                $product_import = ProductImport::find($product_import_id);
                return view('admin.pages.product_import.product_import_add_detail')
                ->with('product_import', $product_import)
                ->with('all_product', $all_product);
            }
        }
    }

    public function ProductImportAddDetailSave(Request $request, $product_import_id)
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $product_import = ProductImport::find($product_import_id);
        if (!$product_import) {
            return Redirect::to('/product-import-add')->with('error', 'Không tồn tại!');
        }

        $data = $request->all();

        $get_product_import_detail = ProductImportDetail::where('sanpham_id', $data['product_import_detail_product_id'])
            ->where('donnhaphang_id', $product_import_id)
            ->first();

        $product_in_stock = ProductInStock::where('sanpham_id', $data['product_import_detail_product_id'])->first();

        $quantity = $data['product_import_detail_quantity'];
        $price = $data['product_import_detail_price'];
        $product_id = $data['product_import_detail_product_id'];

        // Nếu chưa có chi tiết nhập và chưa có tồn kho
        if (!$get_product_import_detail && !$product_in_stock) {
            $import_product_detail = new ProductImportDetail();
            $import_product_detail->chitietnhap_so_luong_nhap = $quantity;
            $import_product_detail->chitietnhap_gia_nhap = $price;
            $import_product_detail->sanpham_id = $product_id;
            $import_product_detail->chitietnhap_ma_don_nhap_hang = $data['product_import_no'];
            $import_product_detail->donnhaphang_id = $product_import_id;
            $import_product_detail->save();

            $product_in_stock_new = new ProductInStock();
            $product_in_stock_new->sanpham_id = $product_id;
            $product_in_stock_new->sanphamtonkho_so_luong_ton = $quantity;
            $product_in_stock_new->sanphamtonkho_so_luong_da_ban = 0;
            $product_in_stock_new->save();

        } elseif (!$get_product_import_detail && $product_in_stock) {
            // Nếu chưa có chi tiết nhập nhưng đã có tồn kho
            $import_product_detail = new ProductImportDetail();
            $import_product_detail->chitietnhap_so_luong_nhap = $quantity;
            $import_product_detail->chitietnhap_gia_nhap = $price;
            $import_product_detail->sanpham_id = $product_id;
            $import_product_detail->chitietnhap_ma_don_nhap_hang = $data['product_import_no'];
            $import_product_detail->donnhaphang_id = $product_import_id;
            $import_product_detail->save();

            $product_in_stock->sanphamtonkho_so_luong_ton += $quantity;
            $product_in_stock->save();

        } else {
            // Nếu đã có chi tiết nhập và đã có tồn kho
            $import_product_detail = ProductImportDetail::find($get_product_import_detail->id);
            $import_product_detail->chitietnhap_so_luong_nhap += $quantity;
            $import_product_detail->chitietnhap_gia_nhap = $price;
            $import_product_detail->save();

            $product_in_stock->sanphamtonkho_so_luong_ton += $quantity;
            $product_in_stock->save();
        }

        // Cập nhật tổng tiền đơn nhập
        $product_import->donnhaphang_tong_tien += $price * $quantity;
        $product_import->save();

        // Cập nhật giá bán (nếu cần giữ logic đó)
        $product_update_price = Product::find($product_id);
        $product_update_price->save();

        return Redirect::to('/product-import-edit/' . $product_import_id);
    }


	public function ProductImportEditDetail($product_import_detail_id)
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $product_import_detail = ProductImportDetail::find($product_import_detail_id);

        if (!$product_import_detail) {
            return Redirect::to('/product-import-add')->with('error', 'Không tồn tại!');
        }

        $all_product = Product::orderBy('id', 'DESC')->get();

        $get_product_in_stock = ProductInStock::where('sanpham_id', $product_import_detail->sanpham_id)->first();

        return view('admin.pages.product_import.product_import_edit_detail')
            ->with('product_import_detail', $product_import_detail)
            ->with('product_in_stock', $get_product_in_stock)
            ->with('all_product', $all_product);
    }


	public function ProductImportEditDetailSave(Request $request, $product_import_detail_id)
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $data = $request->all();

        $product_id = $data['product_import_detail_product_id'];
        $quantity_new = $data['product_import_detail_quantity'];
        $quantity_old = $data['product_import_detail_quantity_old'];
        $price_new = $data['product_import_detail_price'];
        $price_old = $data['product_import_detail_price_old'];
        $price_retail = $data['product_import_detail_price_retail'];
        $import_id = $data['product_import_id'];

        $product_in_stock = ProductInStock::where('sanpham_id', $product_id)->first();
        $import_product_detail = ProductImportDetail::find($product_import_detail_id);
        $import_product = ProductImport::find($import_id);

        if (!$product_in_stock || !$import_product_detail || !$import_product) {
            return Redirect::to('/product-import-show-detail/' . $import_id)->with('error', 'Dữ liệu không hợp lệ hoặc không tồn tại');
        }

        // Kiểm tra tồn kho hiện tại có đủ để điều chỉnh
        if (($product_in_stock->sanphamtonkho_so_luong_ton - $quantity_old) < 0) {
            return Redirect::to('/product-import-show-detail/' . $import_id)->with('error', 'Không đủ số lượng trong kho để cập nhật');
        }

        // Cập nhật số lượng tồn kho
        $product_in_stock->sanphamtonkho_so_luong_ton = $product_in_stock->sanphamtonkho_so_luong_ton - $quantity_old + $quantity_new;
        $product_in_stock->save();

        // Cập nhật chi tiết nhập
        $import_product_detail->chitietnhap_so_luong_nhap = $quantity_new;
        $import_product_detail->chitietnhap_gia_nhap = $price_new;
        $import_product_detail->save();

        // Cập nhật tổng tiền đơn nhập
        $import_product->donnhaphang_tong_tien += ($price_new * $quantity_new) - ($price_old * $quantity_old);
        $import_product->save();

        // Cập nhật giá bán sản phẩm (nếu cần)
        $product_update_price = Product::find($product_id);
        if ($product_update_price) {
            $product_update_price->sanpham_gia_ban = $price_retail;
            $product_update_price->save();
        }

        return Redirect::to('/product-import-show-detail/' . $import_id)->with('message', 'Cập nhật chi tiết thành công!');
    }


    public function ProductImportDeletetDetail($product_import_detail_id)
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $product_import_detail = ProductImportDetail::find($product_import_detail_id);

        if (!$product_import_detail) {
            return Redirect::to('/product-import')->with('error', 'Chi tiết đơn nhập không tồn tại!');
        }

        $product_in_stock = ProductInStock::where('sanpham_id', $product_import_detail->sanpham_id)->first();

        if (!$product_in_stock) {
            return Redirect::to('/product-import-show-detail/' . $product_import_detail->donnhaphang_id)
                ->with('message', 'Không tồn tại tồn kho sản phẩm!');
        }

        $quantity_to_remove = $product_import_detail->chitietnhap_so_luong_nhap;

        if (($product_in_stock->sanphamtonkho_so_luong_ton - $quantity_to_remove) < 0) {
            return Redirect::to('/product-import-show-detail/' . $product_import_detail->donnhaphang_id)
                ->with('message', 'Xoá thất bại, số lượng tồn kho không đủ!');
        }

        // Cập nhật tổng tiền đơn nhập
        $product_import = ProductImport::find($product_import_detail->donnhaphang_id);
        if ($product_import) {
            $product_import->donnhaphang_tong_tien -= ($quantity_to_remove * $product_import_detail->chitietnhap_gia_nhap);
            $product_import->save();
        }

        if (($product_in_stock->sanphamtonkho_so_luong_ton - $quantity_to_remove) == 0) {
            $product_in_stock->sanphamtonkho_so_luong_ton = 0;
            $product_in_stock->save();

            $product = Product::find($product_import_detail->sanpham_id);
            if ($product) {
                $product->sanpham_trang_thai = 2; // Tạm hết hàng
                $product->save();
            }
        } else {
            $product_in_stock->sanphamtonkho_so_luong_ton -= $quantity_to_remove;
            $product_in_stock->save();
        }

        $product_import_detail->delete();

        return Redirect::to('/product-import-show-detail/' . $product_import_detail->donnhaphang_id)
            ->with('message', 'Xoá chi tiết nhập thành công!');
    }


    public function SelectImageProduct(Request $request){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $product_id=$request->product_id;
            $product=Product::find($product_id);
            $output = '<img class=" mt-3" width="300px" height="370px" id="image" src="'.asset('public/uploads/admin/product/'.$product->sanpham_anh).'" />';
            echo  $output;
        }
    }
}