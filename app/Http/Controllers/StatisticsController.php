<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\Product;
use App\Models\ProductInStock;
use App\Models\ProductImportDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductViews;
use App\Models\ProductImport;
use Carbon\Carbon;
use Excel;
use App\Exports\ExcelOrder;
use App\Exports\ExcelImport;
use App\Exports\ExcelViews;
use App\Exports\ExcelInStock;
use Illuminate\Support\Facades\Redirect;
session_start();
class StatisticsController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }
    public function ShowProductViewsStatistics(){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $all_views=ProductViews::all();
            if ($all_views->count()>0) {
                foreach ($all_views as $key=>$total_view) {
                    $pro_total_view=ProductViews::where('sanpham_id', $total_view->sanpham_id)->sum('viewssanpham_views');
                    $total_view_array[]=array(
                    'product_id'=>$total_view->sanpham_id,
                    'sum_view'=>$pro_total_view
                );
                }
            } else {
                $total_view_array=null;
            }
            $all_product_views=ProductViews::orderby('viewssanpham_ngay_xem', 'DESC')->paginate(10);
            return view('admin.pages.statistics.statistics_product_views')
            ->with('total_view_array', $total_view_array)
            ->with('all_product_views', $all_product_views);
        }
    }

    public function SearchViewsSelect(Request $request){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $data=$request->all();
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $date_now = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
            $date_week=date("Y-m-d", strtotime($date_now . "- 7  day"));
            $date_month=date("Y-m-d", strtotime($date_now . "- 30  day"));
            $date_quarter=date("Y-m-d", strtotime($date_now . "- 120  day"));
            $date_year=date("Y-m-d", strtotime($date_now . "- 365  day"));
            if ($data['search_type']==1) {
                $all_product_views=ProductViews::whereDate('viewssanpham_ngay_xem', $date_now)->get();
            } elseif ($data['search_type']==2) {
                $all_product_views=ProductViews::whereDate('viewssanpham_ngay_xem', '<=', $date_now)->whereDate('viewssanpham_ngay_xem', '>=', $date_week)->get();
            } elseif ($data['search_type']==3) {
                $all_product_views=ProductViews::whereDate('viewssanpham_ngay_xem', '<=', $date_now)->whereDate('viewssanpham_ngay_xem', '>=', $date_month)->get();
            } elseif ($data['search_type']==4) {
                $all_product_views=ProductViews::whereDate('viewssanpham_ngay_xem', '<=', $date_now)->whereDate('viewssanpham_ngay_xem', '>=', $date_quarter)->get();
            } elseif ($data['search_type']==5) {
                $all_product_views=ProductViews::whereDate('viewssanpham_ngay_xem', '<=', $date_now)->whereDate('viewssanpham_ngay_xem', '>=', $date_year)->get();
            } else {
                $all_product_views=ProductViews::orderby('viewssanpham_ngay_xem', 'DESC')->get();
            }
            if ($all_product_views->count()>0) {
                foreach ($all_product_views as $key=>$total_view) {
                    if ($data['search_type']==1) {
                        $pro_total_view=ProductViews::where('sanpham_id', $total_view->sanpham_id)->
                    whereDate('viewssanpham_ngay_xem', $date_now)->sum('viewssanpham_views');
                    } elseif ($data['search_type']==2) {
                        $pro_total_view=ProductViews::where('sanpham_id', $total_view->sanpham_id)
                    ->whereDate('viewssanpham_ngay_xem', '<=', $date_now)->whereDate('viewssanpham_ngay_xem', '>=', $date_week)->sum('viewssanpham_views');
                    } elseif ($data['search_type']==3) {
                        $pro_total_view=ProductViews::where('sanpham_id', $total_view->sanpham_id)
                    ->whereDate('viewssanpham_ngay_xem', '<=', $date_now)->whereDate('viewssanpham_ngay_xem', '>=', $date_month)->sum('viewssanpham_views');
                    } elseif ($data['search_type']==4) {
                        $pro_total_view=ProductViews::where('sanpham_id', $total_view->sanpham_id)
                    ->whereDate('viewssanpham_ngay_xem', '<=', $date_now)->whereDate('viewssanpham_ngay_xem', '>=', $date_quarter)->sum('viewssanpham_views');
                    } elseif ($data['search_type']==5) {
                        $pro_total_view=ProductViews::where('sanpham_id', $total_view->sanpham_id)->
                    whereDate('viewssanpham_ngay_xem', '<=', $date_now)->whereDate('viewssanpham_ngay_xem', '>=', $date_year)->sum('viewssanpham_views');
                    } else {
                        $pro_total_view=ProductViews::where('sanpham_id', $total_view->sanpham_id)->sum('viewssanpham_views');
                    }
                    $total_view_array[]=array(
                    'product_id'=>$total_view->sanpham_id,
                    'sum_view'=>$pro_total_view
                );
                }
            } else {
                $total_view_array=null;
            }
            $output = '';
            foreach ($all_product_views as $key=>$views) {
                $output .= '
            <tr>
            <td>
                <a href="javascript: void(0);">
                    <img src="'.asset('public/uploads/admin/product/'.$views->Product->sanpham_anh).'" alt="contact-img" title="contact-img" class="avatar-lg rounded-circle img-thumbnail">
                </a>
            </td>
            <td>
                '. $views->Product->sanpham_ten .'
            </td>
            <td>
            '. $views->viewssanpham_views .'
            </td>
            <td>
            '. $views->viewssanpham_ngay_xem .'
            </td>';
                foreach ($total_view_array as $k=>$sum_views) {
                    if ($sum_views['product_id']==$views->sanpham_id) {
                        $output .= '
                    <td>'.$sum_views['sum_view'].'</td>
                    ';
                        break;
                    }
                }
                $output .= ' </tr>
            ';
            }
            echo $output;
        }
    }

    public function SearchFromToDayViews(Request $request){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $data=$request->all();
            $all_views=ProductViews::all();
            if ($all_views->count()>0) {
                foreach ($all_views as $key=>$total_view) {
                    $pro_total_view=ProductViews::where('sanpham_id', $total_view->sanpham_id)->sum('viewssanpham_views');
                    $total_view_array[]=array(
                    'product_id'=>$total_view->sanpham_id,
                    'sum_view'=>$pro_total_view
                );
                }
            } else {
                $total_view_array=null;
            }
            if ($data['from_day'] && $data['to_day']) {
                $all_product_views=ProductViews::whereDate('viewssanpham_ngay_xem', '>=', $data['from_day'])->whereDate('viewssanpham_ngay_xem', '<=', $data['to_day'])->get();
            } elseif (!$data['from_day'] && $data['to_day']) {
                $all_product_views=ProductViews::whereDate('viewssanpham_ngay_xem', '<=', $data['to_day'])->get();
            } elseif ($data['from_day'] && !$data['to_day']) {
                $all_product_views=ProductViews::whereDate('viewssanpham_ngay_xem', '>=', $data['from_day'])->get();
            } else {
                $all_product_views=ProductViews::orderby('viewssanpham_ngay_xem', 'DESC')->get();
            }
            $output = '';
            foreach ($all_product_views as $key=>$views) {
                $output .= '
            <tr>
            <td>
                <a href="javascript: void(0);">
                    <img src="'.asset('public/uploads/admin/product/'.$views->Product->sanpham_anh).'" alt="contact-img" title="contact-img" class="avatar-lg rounded-circle img-thumbnail">
                </a>
            </td>
            <td>
                '. $views->Product->sanpham_ten .'
            </td>
            <td>
            '. $views->viewssanpham_views .'
            </td>
            <td>
            '. $views->viewssanpham_ngay_xem .'
            </td>';
                foreach ($total_view_array as $k=>$sum_views) {
                    if ($sum_views['product_id']==$views->sanpham_id) {
                        $output .= '
                    <td>'.$sum_views['sum_view'].'</td>
                    ';
                        break;
                    }
                }
                $output .= ' </tr>
            ';
            }
            echo $output;
        }
    }

    public function ShowProductInStockStatistics()
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $all_product_in_stock_statistics = ProductInStock::orderBy('id', 'DESC')->paginate(10);

        return view('admin.pages.statistics.statistics_product_in_stock')
            ->with('all_product_in_stock_statistics', $all_product_in_stock_statistics);
    }


    public function SearchProductInStockStatistics(Request $request)
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $data = $request->all();
        $all_product = Product::all();

        $pro_id = [];

        if ($all_product->count() > 0 && !empty($data['product_name'])) {
            $pro_name = Product::where('sanpham_ten', 'like', '%' . $data['product_name'] . '%')->get();
            foreach ($pro_name as $v) {
                $pro_id[] = $v->id;
            }
        }

        if (!empty($data['product_name'])) {
            $all_product_in_stock_statistics = ProductInStock::whereIn('sanpham_id', $pro_id)->get();
        } else {
            $all_product_in_stock_statistics = ProductInStock::orderBy('id', 'DESC')->get();
        }

        $output = '';
        foreach ($all_product_in_stock_statistics as $product_in_stock) {
            $output .= '
            <tr>
                <td>
                    <a href="javascript:void(0);">
                        <img src="' . asset('public/uploads/admin/product/' . $product_in_stock->Product->sanpham_anh) . '" alt="product" title="product" class="avatar-lg rounded-circle img-thumbnail">
                    </a>
                </td>
                <td>' . $product_in_stock->Product->sanpham_ten . '</td>
                <td>' . number_format($product_in_stock->sanphamtonkho_so_luong_ton, 0, ',', '.') . '</td>
                <td>' . number_format($product_in_stock->sanphamtonkho_so_luong_da_ban, 0, ',', '.') . '</td>
            </tr>
            ';
        }

        echo $output;
    }


    public function ShowImportStatistics(){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $all_product_import_statistics=ProductImport::orderby('id', 'DESC')->paginate(10);
            if ($all_product_import_statistics->count()>0) {
                foreach ($all_product_import_statistics as $key=>$import_detail) {
                    $id_import[]=$import_detail->id;
                }
            } else {
                $id_import=null;
            }
            if ($id_import!=null) {
                $count_detail=ProductImportDetail::whereIn('donnhaphang_id', $id_import)->count();
                $sum_detail=ProductImportDetail::whereIn('donnhaphang_id', $id_import)->sum('chitietnhap_so_luong_nhap');
                $all_import_detail=ProductImportDetail::whereIn('donnhaphang_id', $id_import)->get();
                $sum_total_import=ProductImport::sum('donnhaphang_tong_tien');
                $count_import=ProductImport::whereIn('id', $id_import)->count();
            } else {
                $count_detail=0;
                $sum_detail=0;
                $all_import_detail=null;
                $sum_total_import=0;
                $count_import=0;
            }
            return view('admin.pages.statistics.statistics_product_import')
            ->with('all_import_detail', $all_import_detail)
            ->with('sum_total_import', $sum_total_import)
            ->with('sum_detail', $sum_detail)
            ->with('count_import', $count_import)
            ->with('count_detail', $count_detail)
            ->with('all_product_import_statistics', $all_product_import_statistics);
        }
    }

    public function SearchImportStatistics(Request $request){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $data=$request->all();
            if ($data['from_day'] && $data['to_day']) {
                $all_product_import_statistics=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $data['from_day'])
            ->whereDate('donnhaphang_ngay_nhap', '<=', $data['to_day'])->get();
                $sum_total_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $data['from_day'])
            ->whereDate('donnhaphang_ngay_nhap', '<=', $data['to_day'])->sum('donnhaphang_tong_tien');
            } elseif (!$data['from_day'] && $data['to_day']) {
                $all_product_import_statistics=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $data['to_day'])->get();
                $sum_total_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $data['to_day'])->sum('donnhaphang_tong_tien');
            } elseif ($data['from_day'] && !$data['to_day']) {
                $all_product_import_statistics=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $data['from_day'])->get();
                $sum_total_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $data['from_day'])->sum('donnhaphang_tong_tien');
            } else {
                $all_product_import_statistics=ProductImport::orderby('donnhaphang_ngay_nhap', 'DESC')->get();
                $sum_total_import=ProductImport::sum('donnhaphang_tong_tien');
            }
            if ($all_product_import_statistics->count()>0) {
                foreach ($all_product_import_statistics as $key=>$import_detail) {
                    $id_import[]=$import_detail->id;
                }
            } else {
                $id_import=null;
            }
            if ($id_import!=null) {
                $all_import_detail=ProductImportDetail::whereIn('donnhaphang_id', $id_import)->get();
                $count_detail=ProductImportDetail::whereIn('donnhaphang_id', $id_import)->count();
                $sum_detail=ProductImportDetail::whereIn('donnhaphang_id', $id_import)->sum('chitietnhap_so_luong_nhap');
                $count_import=ProductImport::whereIn('id', $id_import)->count();
            } else {
                $all_import_detail=null;
                $count_detail=0;
                $sum_detail=0;
                $count_import=0;
            }
            $output = '';
            $output .= '
            <label class="col-form-label"> <h4>Thống Kê</h4></label>
            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 " cellspacing="0" id="tickets-table">
                <thead class="bg-light">
                <tr>
                    <th class="font-weight-medium">Tổng Cộng</th>
                    <th class="font-weight-medium">Số Đơn Nhập</th>
                    <th class="font-weight-medium">Số Sản Phẩm</th>
                    <th class="font-weight-medium">Số Lượng Nhập</th>
                </tr>
                </thead>
                <tbody class="font-14 " >

                    <tr>
                        <td>
                            '.number_format( $sum_total_import ,0,',','.' ).' VNĐ' .'
                        </td>
                        <td>
                        '. number_format( $count_import ,0,',','.' ) .'
                        </td>
                        <td>
                            '. number_format( $count_detail ,0,',','.' ) .'
                        </td>
                        <td>
                        '. number_format( $sum_detail ,0,',','.' ) .'
                        </td>
                    </tr>
                </tbody>
            </table>
            <label class="col-form-label"> <h4>Đơn Nhập</h4></label>
            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 " cellspacing="0" id="tickets-table">
            <thead class="bg-light">
                <tr>
                    <th class="font-weight-medium">Mã Đơn Nhập</th>
                    <th class="font-weight-medium">Ngày Nhập</th>
                    <th class="font-weight-medium">Nhà Cung Cấp</th>
                    <th class="font-weight-medium">Tổng Cộng</th>
                </tr>
            </thead>
            <tbody class="font-14 show_views_type_search" >';
            foreach ($all_product_import_statistics as $key=>$product_import) {
                $output .= ' <tr>
                        <td>
                            '. $product_import->donnhaphang_ma_don_nhap_hang .'
                        </td>
                        <td>
                            '. date('d-m-Y', strtotime($product_import->donnhaphang_ngay_nhap)) .'
                        </td>
                        <td>
                        '.$product_import->Supplier->nhacungcap_ten .'
                        </td>
                        <td>
                            '.number_format($product_import->donnhaphang_tong_tien, 0, ',', '.').' VNĐ' .'
                        </td>
                    </tr>';
            }
            $output .= '
            </tbody>
        </table>
        ';
            echo $output;
        }
    }

    public function SearchSelectImportStatistics(Request $request){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $data=$request->all();
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $date_now = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
            $date_week=date("Y-m-d", strtotime($date_now . "- 7  day"));
            $date_month=date("Y-m-d", strtotime($date_now . "- 30  day"));
            $date_quarter=date("Y-m-d", strtotime($date_now . "- 120  day"));
            $date_year=date("Y-m-d", strtotime($date_now . "- 365  day"));
            if ($data['search_type']==1) {
                $sum_total_import=ProductImport::whereDate('donnhaphang_ngay_nhap', $date_now)->sum('donnhaphang_tong_tien');
                $all_product_import_statistics=ProductImport::whereDate('donnhaphang_ngay_nhap', $date_now)->get();
            } elseif ($data['search_type']==2) {
                $sum_total_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $date_now)->whereDate('donnhaphang_ngay_nhap', '>=', $date_week)->sum('donnhaphang_tong_tien');
                $all_product_import_statistics=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $date_now)->whereDate('donnhaphang_ngay_nhap', '>=', $date_week)->get();
            } elseif ($data['search_type']==3) {
                $sum_total_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $date_now)->whereDate('donnhaphang_ngay_nhap', '>=', $date_month)->sum('donnhaphang_tong_tien');
                $all_product_import_statistics=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $date_now)->whereDate('donnhaphang_ngay_nhap', '>=', $date_month)->get();
            } elseif ($data['search_type']==4) {
                $sum_total_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $date_now)->whereDate('donnhaphang_ngay_nhap', '>=', $date_quarter)->sum('donnhaphang_tong_tien');
                $all_product_import_statistics=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $date_now)->whereDate('donnhaphang_ngay_nhap', '>=', $date_quarter)->get();
            } elseif ($data['search_type']==5) {
                $sum_total_import=ProductImport:: whereDate('donnhaphang_ngay_nhap', '<=', $date_now)->whereDate('donnhaphang_ngay_nhap', '>=', $date_year)->sum('donnhaphang_tong_tien');
                $all_product_import_statistics=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $date_now)->whereDate('donnhaphang_ngay_nhap', '>=', $date_year)->get();
            } else {
                $sum_total_import=ProductImport::sum('donnhaphang_tong_tien');
                $all_product_import_statistics=ProductImport::orderby('donnhaphang_ngay_nhap', 'DESC')->get();
            }
            if ($all_product_import_statistics->count()>0) {
                foreach ($all_product_import_statistics as $key=>$import_detail) {
                    $id_import[]=$import_detail->id;
                }
            } else {
                $id_import=null;
            }
            if ($id_import!=null) {
                $all_import_detail=ProductImportDetail::whereIn('donnhaphang_id', $id_import)->get();
                $count_detail=ProductImportDetail::whereIn('donnhaphang_id', $id_import)->count();
                $sum_detail=ProductImportDetail::whereIn('donnhaphang_id', $id_import)->sum('chitietnhap_so_luong_nhap');
                $count_import=ProductImport::whereIn('id', $id_import)->count();
            } else {
                $all_import_detail=null;
                $count_detail=0;
                $sum_detail=0;
                $count_import=0;
            }
            $output = '';
            $output .= '
                <label class="col-form-label"> <h4>Thống Kê</h4></label>
                <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 " cellspacing="0" id="tickets-table">
                    <thead class="bg-light">
                    <tr>
                        <th class="font-weight-medium">Tổng Cộng</th>
                        <th class="font-weight-medium">Số Đơn Nhập</th>
                        <th class="font-weight-medium">Số Sản Phẩm</th>
                        <th class="font-weight-medium">Số Lượng Nhập</th>
                    </tr>
                    </thead>
                    <tbody class="font-14 " >

                        <tr>
                            <td>
                                '.number_format( $sum_total_import ,0,',','.' ).' VNĐ' .'
                            </td>
                            <td>
                            '. number_format( $count_import ,0,',','.' ) .'
                            </td>
                            <td>
                                '. number_format( $count_detail ,0,',','.' ) .'
                            </td>
                            <td>
                            '. number_format( $sum_detail ,0,',','.' ) .'
                            </td>
                        </tr>
                    </tbody>
                </table>
                <label class="col-form-label"> <h4>Đơn Nhập</h4></label>
                <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 " cellspacing="0" id="tickets-table">
                <thead class="bg-light">
                <tr>
                    <th class="font-weight-medium">Mã Đơn Nhập</th>
                    <th class="font-weight-medium">Ngày Nhập</th>
                    <th class="font-weight-medium">Nhà Cung Cấp</th>
                    <th class="font-weight-medium">Tổng Cộng</th>
                </tr>
            </thead>
            <tbody class="font-14 show_views_type_search" >';
            foreach ($all_product_import_statistics as $key=>$product_import) {
                $output .= ' <tr>
                        <td>
                            '. $product_import->donnhaphang_ma_don_nhap_hang .'
                        </td>
                        <td>
                            '. date('d-m-Y', strtotime($product_import->donnhaphang_ngay_nhap)) .'
                        </td>
                        <td>
                        '.$product_import->Supplier->nhacungcap_ten .'
                        </td>
                        <td>
                            '.number_format($product_import->donnhaphang_tong_tien, 0, ',', '.').' VNĐ' .'
                        </td>
                    </tr>';
            }
            $output .= '
            </tbody>
        </table>
        ';
            echo $output;
        }
    }

    public function ShowSalesStatistics()
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $all_order_statistics = Order::orderBy('id', 'DESC')->paginate(10);

        if ($all_order_statistics->count() > 0) {
            $id_order = $all_order_statistics->pluck('id')->toArray();
        } else {
            $id_order = null;
        }

        if ($id_order !== null) {
            $count_detail = OrderDetail::whereIn('dondathang_id', $id_order)->count();
            $sum_total_order = Order::sum('dondathang_tong_tien');
            $count_order = count($id_order);
            $count_order_unconfirmed = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 0)->count();
            $count_order_confirmed = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 1)->count();
            $count_order_in_transit = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 2)->count();
            $count_order_delivered = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 3)->count();
            $count_order_cancel = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 4)->count();
        } else {
            $count_detail = 0;
            $sum_total_order = 0;
            $count_order = 0;
            $count_order_unconfirmed = 0;
            $count_order_confirmed = 0;
            $count_order_in_transit = 0;
            $count_order_delivered = 0;
            $count_order_cancel = 0;
        }

        $all_order_statistics_success = Order::where('dondathang_trang_thai', 3)->orderBy('id', 'DESC')->get();

        if ($all_order_statistics_success->count() > 0) {
            $id_order_success = $all_order_statistics_success->pluck('id')->toArray();

            $sum_total_order_success = Order::whereIn('id', $id_order_success)->sum('dondathang_tong_tien');
            $sum_total_fee_success = Order::whereIn('id', $id_order_success)->sum('dondathang_phi_van_chuyen');
            $sum_detail = OrderDetail::whereIn('dondathang_id', $id_order_success)->sum('chitietdondathang_so_luong');

            $all_order_detail = OrderDetail::whereIn('dondathang_id', $id_order_success)->get();
            $sum_total_import = 0;

            // Gom chi tiết nhập theo sản phẩm, bỏ 
            $import_price_map = ProductImportDetail::select('sanpham_id')
                ->selectRaw('MAX(chitietnhap_gia_nhap) as gia_nhap') // giả định giá mới nhất
                ->groupBy('sanpham_id')
                ->pluck('gia_nhap', 'sanpham_id')
                ->toArray();

            foreach ($all_order_detail as $order_detail) {
                $product_id = $order_detail->sanpham_id;
                $quantity = $order_detail->chitietdondathang_so_luong;

                if (isset($import_price_map[$product_id])) {
                    $sum_total_import += $quantity * $import_price_map[$product_id];
                }
            }

        } else {
            $sum_total_order_success = 0;
            $sum_total_fee_success = 0;
            $sum_detail = 0;
            $sum_total_import = 0;
        }

        return view('admin.pages.statistics.statistics_order')
            ->with('sum_total_order', $sum_total_order)
            ->with('sum_total_order_success', $sum_total_order_success)
            ->with('sum_detail', $sum_detail)
            ->with('sum_total_fee_success', $sum_total_fee_success)
            ->with('sum_total_import', $sum_total_import)
            ->with('count_order', $count_order)
            ->with('count_detail', $count_detail)
            ->with('all_order_statistics', $all_order_statistics)
            ->with('count_order_cancel', $count_order_cancel)
            ->with('count_order_delivered', $count_order_delivered)
            ->with('count_order_in_transit', $count_order_in_transit)
            ->with('count_order_confirmed', $count_order_confirmed)
            ->with('count_order_unconfirmed', $count_order_unconfirmed);
    }


    public function SearchOrderStatistics(Request $request)
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $data = $request->all();

        // Lọc đơn hàng theo ngày
        if (!empty($data['from_day']) && !empty($data['to_day'])) {
            $all_order_statistics = Order::whereDate('dondathang_ngay_dat_hang', '>=', $data['from_day'])
                ->whereDate('dondathang_ngay_dat_hang', '<=', $data['to_day'])
                ->get();

            $sum_total_order = Order::whereDate('dondathang_ngay_dat_hang', '>=', $data['from_day'])
                ->whereDate('dondathang_ngay_dat_hang', '<=', $data['to_day'])
                ->sum('dondathang_tong_tien');

        } elseif (empty($data['from_day']) && !empty($data['to_day'])) {
            $all_order_statistics = Order::whereDate('dondathang_ngay_dat_hang', '<=', $data['to_day'])->get();
            $sum_total_order = Order::whereDate('dondathang_ngay_dat_hang', '<=', $data['to_day'])->sum('dondathang_tong_tien');

        } elseif (!empty($data['from_day']) && empty($data['to_day'])) {
            $all_order_statistics = Order::whereDate('dondathang_ngay_dat_hang', '>=', $data['from_day'])->get();
            $sum_total_order = Order::whereDate('dondathang_ngay_dat_hang', '>=', $data['from_day'])->sum('dondathang_tong_tien');

        } else {
            $all_order_statistics = Order::orderBy('dondathang_ngay_dat_hang', 'DESC')->get();
            $sum_total_order = Order::sum('dondathang_tong_tien');
        }

        $id_order = $all_order_statistics->pluck('id')->toArray();

        if (!empty($id_order)) {
            $all_order_statistics_success = Order::whereIn('id', $id_order)
                ->where('dondathang_trang_thai', 3)
                ->get();

            $count_detail = OrderDetail::whereIn('dondathang_id', $id_order)->count();
            $all_order_detail = OrderDetail::whereIn('dondathang_id', $id_order)->get();
            $count_order = count($id_order);
            $count_order_unconfirmed = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 0)->count();
            $count_order_confirmed = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 1)->count();
            $count_order_in_transit = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 2)->count();
            $count_order_delivered = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 3)->count();
            $count_order_cancel = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 4)->count();

            $id_order_success = $all_order_statistics_success->pluck('id')->toArray();

            if (!empty($id_order_success)) {
                $sum_total_order_success = Order::whereIn('id', $id_order_success)->sum('dondathang_tong_tien');
                $sum_total_fee_success = Order::whereIn('id', $id_order_success)->sum('dondathang_phi_van_chuyen');
                $sum_detail = OrderDetail::whereIn('dondathang_id', $id_order_success)->sum('chitietdondathang_so_luong');

                $all_order_detail_success = OrderDetail::whereIn('dondathang_id', $id_order_success)->get();

                // Lấy giá nhập cao nhất mỗi sản phẩm
                $import_price_map = ProductImportDetail::select('sanpham_id')
                    ->selectRaw('MAX(chitietnhap_gia_nhap) as gia_nhap')
                    ->groupBy('sanpham_id')
                    ->pluck('gia_nhap', 'sanpham_id')
                    ->toArray();

                $sum_total_import = 0;
                foreach ($all_order_detail_success as $order_detail) {
                    $product_id = $order_detail->sanpham_id;
                    $quantity = $order_detail->chitietdondathang_so_luong;

                    if (isset($import_price_map[$product_id])) {
                        $sum_total_import += $quantity * $import_price_map[$product_id];
                    }
                }
            } else {
                $sum_total_order_success = 0;
                $sum_total_fee_success = 0;
                $sum_total_import = 0;
                $sum_detail = 0;
            }
        } else {
            $count_detail = 0;
            $all_order_detail = null;
            $count_order = 0;
            $count_order_unconfirmed = 0;
            $count_order_confirmed = 0;
            $count_order_in_transit = 0;
            $count_order_delivered = 0;
            $count_order_cancel = 0;
            $sum_total_order_success = 0;
            $sum_total_fee_success = 0;
            $sum_total_import = 0;
            $sum_detail = 0;
        }

        // In kết quả HTML
        $output = view('admin.pages.statistics.ajax.statistics_search_result', [
            'sum_total_order_success' => $sum_total_order_success,
            'sum_total_import' => $sum_total_import,
            'sum_total_fee_success' => $sum_total_fee_success,
            'sum_total_order' => $sum_total_order,
            'count_detail' => $count_detail,
            'sum_detail' => $sum_detail,
            'count_order' => $count_order,
            'count_order_unconfirmed' => $count_order_unconfirmed,
            'count_order_confirmed' => $count_order_confirmed,
            'count_order_in_transit' => $count_order_in_transit,
            'count_order_delivered' => $count_order_delivered,
            'count_order_cancel' => $count_order_cancel,
            'all_order_statistics' => $all_order_statistics,
        ])->render();

        echo $output;
    }


   public function SearchSelectOrderStatistics(Request $request)
    {
        $this->AuthLogin();

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        }

        $data = $request->all();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date_now = Carbon::now()->format('Y-m-d');
        $date_week = date("Y-m-d", strtotime($date_now . "-7 day"));
        $date_month = date("Y-m-d", strtotime($date_now . "-30 day"));
        $date_quarter = date("Y-m-d", strtotime($date_now . "-120 day"));
        $date_year = date("Y-m-d", strtotime($date_now . "-365 day"));

        switch ($data['search_type']) {
            case 1:
                $all_order_statistics = Order::whereDate('dondathang_ngay_dat_hang', '=', $date_now)->get();
                $sum_total_order = $all_order_statistics->sum('dondathang_tong_tien');
                break;
            case 2:
                $all_order_statistics = Order::whereBetween('dondathang_ngay_dat_hang', [$date_week, $date_now])->get();
                $sum_total_order = $all_order_statistics->sum('dondathang_tong_tien');
                break;
            case 3:
                $all_order_statistics = Order::whereBetween('dondathang_ngay_dat_hang', [$date_month, $date_now])->get();
                $sum_total_order = $all_order_statistics->sum('dondathang_tong_tien');
                break;
            case 4:
                $all_order_statistics = Order::whereBetween('dondathang_ngay_dat_hang', [$date_quarter, $date_now])->get();
                $sum_total_order = $all_order_statistics->sum('dondathang_tong_tien');
                break;
            case 5:
                $all_order_statistics = Order::whereBetween('dondathang_ngay_dat_hang', [$date_year, $date_now])->get();
                $sum_total_order = $all_order_statistics->sum('dondathang_tong_tien');
                break;
            default:
                $all_order_statistics = Order::orderBy('dondathang_ngay_dat_hang', 'DESC')->get();
                $sum_total_order = Order::sum('dondathang_tong_tien');
                break;
        }

        $id_order = $all_order_statistics->pluck('id')->toArray();

        if (!empty($id_order)) {
            $all_order_statistics_success = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 3)->get();
            $id_order_success = $all_order_statistics_success->pluck('id')->toArray();

            $count_detail = OrderDetail::whereIn('dondathang_id', $id_order)->count();
            $sum_detail = OrderDetail::whereIn('dondathang_id', $id_order_success)->sum('chitietdondathang_so_luong');

            $count_order = count($id_order);
            $count_order_unconfirmed = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 0)->count();
            $count_order_confirmed = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 1)->count();
            $count_order_in_transit = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 2)->count();
            $count_order_delivered = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 3)->count();
            $count_order_cancel = Order::whereIn('id', $id_order)->where('dondathang_trang_thai', 4)->count();

            $sum_total_order_success = Order::whereIn('id', $id_order_success)->sum('dondathang_tong_tien');
            $sum_total_fee_success = Order::whereIn('id', $id_order_success)->sum('dondathang_phi_van_chuyen');

            // Tính chi phí nhập hàng
            $order_details_success = OrderDetail::whereIn('dondathang_id', $id_order_success)->get();

            $import_price_map = ProductImportDetail::select('sanpham_id')
                ->selectRaw('MAX(chitietnhap_gia_nhap) as gia_nhap')
                ->groupBy('sanpham_id')
                ->pluck('gia_nhap', 'sanpham_id')
                ->toArray();

            $sum_total_import = 0;
            foreach ($order_details_success as $od) {
                $product_id = $od->sanpham_id;
                $qty = $od->chitietdondathang_so_luong;
                if (isset($import_price_map[$product_id])) {
                    $sum_total_import += $qty * $import_price_map[$product_id];
                }
            }
        } else {
            $sum_total_order_success = 0;
            $sum_total_fee_success = 0;
            $sum_total_import = 0;
            $sum_detail = 0;
            $count_detail = 0;
            $count_order = 0;
            $count_order_unconfirmed = 0;
            $count_order_confirmed = 0;
            $count_order_in_transit = 0;
            $count_order_delivered = 0;
            $count_order_cancel = 0;
        }

        // HTML kết quả
        $output = '';

        $output .= '
        <label class="col-form-label"><h4>Thống Kê Doanh Thu</h4></label>
        <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" cellspacing="0" id="tickets-table">
            <thead class="bg-light">
            <tr>
                <th>Doanh Thu + Phí Vận Chuyển</th>
                <th>Doanh Thu</th>
                <th>Tổng Đã Bán</th>
                <th>Tổng Đơn Hàng</th>
                <th>Số Sản Phẩm</th>
                <th>Số Lượng Đã Bán</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>' . number_format($sum_total_order_success - $sum_total_import, 0, ',', '.') . ' VNĐ</td>
                <td>' . number_format($sum_total_order_success - $sum_total_import - $sum_total_fee_success, 0, ',', '.') . ' VNĐ</td>
                <td>' . number_format($sum_total_order_success, 0, ',', '.') . ' VNĐ</td>
                <td>' . number_format($sum_total_order, 0, ',', '.') . ' VNĐ</td>
                <td>' . number_format($count_detail, 0, ',', '.') . '</td>
                <td>' . number_format($sum_detail, 0, ',', '.') . '</td>
            </tr>
            </tbody>
        </table>

        <label class="col-form-label"><h4>Thống Kê Đơn Hàng</h4></label>
        <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" cellspacing="0" id="tickets-table">
            <thead class="bg-light">
            <tr>
                <th>Số Đơn Hàng</th>
                <th>Chưa Xác Nhận</th>
                <th>Đã Xác Nhận</th>
                <th>Đang Giao</th>
                <th>Đã Giao</th>
                <th>Đã Hủy</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>' . number_format($count_order, 0, ',', '.') . '</td>
                <td>' . number_format($count_order_unconfirmed, 0, ',', '.') . '</td>
                <td>' . number_format($count_order_confirmed, 0, ',', '.') . '</td>
                <td>' . number_format($count_order_in_transit, 0, ',', '.') . '</td>
                <td>' . number_format($count_order_delivered, 0, ',', '.') . '</td>
                <td>' . number_format($count_order_cancel, 0, ',', '.') . '</td>
            </tr>
            </tbody>
        </table>

        <label class="col-form-label"><h4>Đơn Hàng</h4></label>
        <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" cellspacing="0" id="tickets-table">
            <thead class="bg-light">
            <tr>
                <th>Mã Đơn Hàng</th>
                <th>Ngày Đặt</th>
                <th>Khách Hàng</th>
                <th>Tổng Cộng</th>
                <th>Trạng Thái</th>
            </tr>
            </thead>
            <tbody class="font-14 show_views_type_search">';
        foreach ($all_order_statistics as $order) {
            $output .= '<tr>
                <td>' . $order->dondathang_ma_don_dat_hang . '</td>
                <td>' . date('d-m-Y', strtotime($order->dondathang_ngay_dat_hang)) . '</td>
                <td>' . $order->Customer->khachhang_ho . ' ' . $order->Customer->khachhang_ten . '</td>
                <td>' . number_format($order->dondathang_tong_tien, 0, ',', '.') . ' VNĐ</td>
                <td>';
            switch ($order->dondathang_trang_thai) {
                case 0: $output .= 'Chưa Xác Nhận'; break;
                case 1: $output .= 'Đã Xác Nhận'; break;
                case 2: $output .= 'Đang Giao Hàng'; break;
                case 3: $output .= 'Đã Giao Hàng'; break;
                case 4: $output .= 'Đơn Hàng Đã Bị Hủy'; break;
            }
            $output .= '</td></tr>';
        }
        $output .= '</tbody></table>';

        echo $output;
    }


    public function ExportOrderXlsx(Request $request){
        $today = date("Y-m-d");
        $order_first=Order::select('dondathang_ngay_dat_hang')->orderby('dondathang_ngay_dat_hang','ASC')->first();
        if($request->search_from_day_statistical_order && $request->search_to_day_statistical_order){
            return Excel::download(new ExcelOrder($request->search_from_day_statistical_order,$request->search_to_day_statistical_order) ,'thong_ke_don_hang.xlsx');
        }elseif($request->search_from_day_statistical_order && !$request->search_to_day_statistical_order){
            return Excel::download(new ExcelOrder($request->search_from_day_statistical_order,$today) ,'thong_ke_don_hang.xlsx');
        }elseif(!$request->search_from_day_statistical_order && $request->search_to_day_statistical_order){
            return Excel::download(new ExcelOrder($order_first,$request->search_to_day_statistical_order) ,'thong_ke_don_hang.xlsx');
        }else{
            return Excel::download(new ExcelOrder($order_first,$today) ,'thong_ke_don_hang.xlsx');
        }
    }

    public function ExportImportXlsx(Request $request){
        $today = date("Y-m-d");
        $import_first=ProductImport::select('donnhaphang_ngay_nhap')->orderby('donnhaphang_ngay_nhap','ASC')->first();
        if($request->search_from_day_statistical_product_import && $request->search_to_day_statistical_product_import){
            return Excel::download(new ExcelImport($request->search_from_day_statistical_product_import,$request->search_to_day_statistical_product_import) ,'thong_ke_nhap.xlsx');
        }elseif($request->search_from_day_statistical_product_import && !$request->search_to_day_statistical_product_import){
            return Excel::download(new ExcelImport($request->search_from_day_statistical_product_import,$today) ,'thong_ke_nhap.xlsx');
        }elseif(!$request->search_from_day_statistical_product_import && $request->search_to_day_statistical_product_import){
            return Excel::download(new ExcelImport($import_first,$request->search_to_day_statistical_product_import) ,'thong_ke_nhap.xlsx');
        }else{
            return Excel::download(new ExcelImport($import_first,$today) ,'thong_ke_nhap.xlsx');
        }
    }

    public function ExportViewsXlsx(Request $request){
        $today = date("Y-m-d");
        $views_first=ProductViews::select('viewssanpham_ngay_xem')->orderby('viewssanpham_ngay_xem','ASC')->first();
        if($request->search_from_day_views && $request->search_to_day_views){
            return Excel::download(new ExcelViews($request->search_from_day_views,$request->search_to_day_views) ,'thong_ke_luot_xem_san_pham.xlsx');
        }elseif($request->search_from_day_views && !$request->search_to_day_views){
            return Excel::download(new ExcelViews($request->search_from_day_views,$today) ,'thong_ke_luot_xem_san_pham.xlsx');
        }elseif(!$request->search_from_day_views && $request->search_to_day_views){
            return Excel::download(new ExcelViews($views_first,$request->search_to_day_views) ,'thong_ke_luot_xem_san_pham.xlsx');
        }else{
            return Excel::download(new ExcelViews($views_first,$today) ,'thong_ke_luot_xem_san_pham.xlsx');
        }
    }

    public function ExportInStockXlsx(){
        return Excel::download(new ExcelInStock() ,'thong_ke_ton_kho.xlsx');
    }
}
