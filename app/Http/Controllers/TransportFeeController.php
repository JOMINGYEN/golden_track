<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Province;
use App\Models\Wards;
use App\Models\TransportFee;
use Illuminate\Support\Facades\Redirect;

class TransportFeeController extends Controller
{
    public function TransportFee(){
        $fee=TransportFee::orderby('tinhthanhpho_id','ASC')->paginate(5);
        $city=City::orderby('id','ASC')->get();
        return view('admin.pages.transportfee.transport_fee')
        ->with('city',$city)
        ->with('all_fee',$fee);
    }

    public function SelectTransportFee(Request $request){
        $data =$request->all();
        if($data['action']){
            $output = '';
            if($data['action']=="city"){
                $select_province=Province::where('tinhthanhpho_id',$data['ma_id'])->orderby('id','ASC')->get();
                $output.='<option>---Chọn Quận Huyện---</option>';
                foreach($select_province as $key =>$province){
                    $output.='<option value="'.$province->id.'">'.$province->quanhuyen_name.'</option>';
                }
            }else{
    			$select_wards = Wards::where('quanhuyen_id',$data['ma_id'])->orderby('id','ASC')->get();
    			$output.='<option>---Chọn Xã Phường Thị Trấn---</option>';
    			foreach($select_wards as $k => $ward){
    				$output.='<option value="'.$ward->id.'">'.$ward->xaphuongthitran_name.'</option>';
    			}
    		}
            echo $output;
        }
    }
    public function TransportFeeAdd(Request $request){
        $data =$request->all();
        $transport_fee_old=TransportFee::where('tinhthanhpho_id',$data['city'])
        ->where('quanhuyen_id',$data['province'])
        ->where('xaphuong_id',$data['wards'])->orderby('id','DESC')->first();
        if($transport_fee_old){
            return Redirect::to('/transport-fee')->with('error','Thêm không thành công, phí vận chuyển đã được tính cho khu vực này!');
        }else{
            $transport_fee=new TransportFee();
            $transport_fee->tinhthanhpho_id=$data['city'];
            $transport_fee->quanhuyen_id=$data['province'];
            $transport_fee->xaphuong_id=$data['wards'];
            $transport_fee->phivanchuyen_phi_van_chuyen=$data['transport_fee'];
            $transport_fee->phivanchuyen_ngay_giao_hang_du_kien=$data['transport_fee_day'];
            $transport_fee->save();
            return Redirect::to('/transport-fee')->with('message', 'Thêm thành công!');

        }
    }
    // public function SelectFee(){
    //     $fee=TransportFee::orderby('id','DESC')->paginate(5);
    //     // $output = '';
    //     // foreach ($fee as $key=>$value){
    //     //     $output .=
    //     //     '<tr>
    //     //         <td>'. $value->City->tinhthanhpho_name .'</td>
    //     //         <td>'. $value->Province->quanhuyen_name .'</td>
    //     //         <td>'. $value->Wards->xaphuongthitran_name .'</td>
    //     //         <td contenteditable data-feeship_id="'.$value->id.'" class="fee-edit">'.number_format( $value->phivanchuyen_phi_van_chuyen,0,',','.', ).'</td>
    //     //         <td contenteditable data-feeship_id="'.$value->id.'" class="fee-edit-day">'. $value->phivanchuyen_ngay_giao_hang_du_kien .'</td>
    //     //     </tr>';
    //     // }
    //     // echo $output;
    //     $city=City::orderby('id','ASC')->get();
    //     return view('admin.pages.transportfee.transport_fee')
    //     ->with('city',$city)
    //     ->with('all_fee',$fee);
    // }

    public function TransportFeeUpdate(Request $request){
        $data =$request->all();
        $transport_fee= TransportFee::find($data['feeship_id']);
        $fee_value = rtrim($data['fee_value'],'.');
        $transport_fee->phivanchuyen_phi_van_chuyen=$fee_value;
        $transport_fee->save();
    }
    public function TransportFeeUpdateDay(Request $request){
        $data =$request->all();
        $transport_fee= TransportFee::find($data['feeship_id']);
        $transport_fee->phivanchuyen_ngay_giao_hang_du_kien=$data['fee_value'];
        $transport_fee->save();
    }
}
