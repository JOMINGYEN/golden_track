@extends('admin.index_layout_admin')
@section('content')
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <div class="text-lg-right mt-3 mt-lg-0">
                                <a href="{{URL::to('/staff')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Cập Nhật Nhân Viên</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {!! session()->get('message') !!}
                    {!! session()->forget('message') !!}
                </div>
            @elseif(session()->has('error'))
                <div class="alert alert-danger">
                    {!! session()->get('error') !!}
                    {!! session()->forget('error') !!}
                </div>
            @endif
            <div class="row">
                    <div class="col-lg-4 col-xl-4">
                        <div class="card-box text-center">
                            @if($staff->admin_anh)
                            <img src="{{asset('public/uploads/admin/staff/'.$staff->admin_anh)}}" class="rounded-circle avatar-xl img-thumbnail" alt="profile-image">
                            @else
                            <img src="{{URL::asset('public/backend/images/users/SHOES.png')}}" class="rounded-circle avatar-xl img-thumbnail" alt="profile-image">
                            @endif
                            <h4 class="mb-0">{{ $staff->admin_ho }}{{ $staff->admin_ten }}</h4>
                            <p class="text-muted"></p>
                            {{--  <button type="button" class="btn btn-success btn-xs waves-effect mb-2 waves-light">Follow</button>
                            <button type="button" class="btn btn-danger btn-xs waves-effect mb-2 waves-light">Message</button>  --}}
                            <div class="text-left mt-3">
                                <h4 class="font-13 text-uppercase">Thông Tin Nhân Viên :</h4>
                                <p class="text-muted font-13 mb-3">
                                    Nhân Viên: {{ $staff->admin_ten }}.
                                </p>
                                <p class="text-muted mb-2 font-13"><strong>Họ Tên :</strong> <span class="ml-2">{{ $staff->admin_ho }} {{ $staff->admin_ten }}</span></p>
                                <p class="text-muted mb-2 font-13"><strong>Số Điện Thoại :</strong><span class="ml-2">{{ $staff->admin_so_dien_thoai }}</span></p>
                                <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2 ">{{ $staff->admin_email }}</span></p>
                                <p class="text-muted mb-1 font-13"><strong>Giới Tính :</strong>
                                     <span class="ml-2">
                                        @if($staff->admin_gioi_tinh!=true)
                                            Chưa Cập Nhật
                                        @else
                                            {{ $staff->admin_gioi_tinh?'Nam':'Nữ' }}
                                        @endif
                                    </span>
                                </p>
                                <p class="text-muted mb-1 font-13"><strong>Địa Chỉ :</strong> <span class="ml-2">{{ $staff->admin_dia_chi }}</span></p>
                                <p class="text-muted mb-1 font-13"><strong>Mã Căn Cước :</strong>
                                    <span class="ml-2">
                                        {{ $staff->admin_id }}
                                    </span>
                                </p>
                                <p class="text-muted mb-1 font-13"><strong>Ngày Vào Làm :</strong> <span class="ml-2"> {{ date('d-m-Y', strtotime($staff->admin_ngay_vao_lam)) }}</span></p>
                            </div>
                        </div> <!-- end card-box -->
                    </div> <!-- end col-->
                    <div class="col-lg-8 col-xl-8">
                        <div class="card-box">
                            <h4 class="header-title">Thông Tin Nhân Viên</h4>
                            <hr>
                        <div class="tab-content">
                                <div class="tab-pane show active" id="settings">
                                    <form action="{{URL::to('/staff-edit-save/'.$staff->id)}}" class="form-horizontal" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="firstname">Họ</label>
                                                    <input type="text" name="staff_first_name" class="form-control" value="{{ $staff->admin_ho }}" required="">
                                                    @error('staff_first_name')
                                                        <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="lastname">Tên</label>
                                                    <input type="text" name="staff_last_name" class="form-control" value="{{ $staff->admin_ten }}" required="" >
                                                    @error('staff_last_name')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="useremail">Email</label>
                                                    <input type="email" class="form-control" value="{{ $staff->admin_email }}" readonly required="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="userpassword">Số Điện Thoại</label>
                                                    <input type="number" name="staff_phone_number" class="form-control" value="{{ $staff->admin_so_dien_thoai }}" required="" >
                                                    @error('staff_phone_number')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                             <!-- end col -->
                                        </div> <!-- end row -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="useremail">Giới Tính </label> <br>
                                                    @if ($staff->admin_gioi_tinh==1 || $staff->admin_gioi_tinh!=true)
                                                        <div class="custom-control custom-radio">
                                                            <input class=" custom-control-input" type="radio" name="staff_gender" value="1" id="male" checked>
                                                            <label class=" custom-control-label" for="male">Nam</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input class=" custom-control-input" type="radio" name="staff_gender" value="0" id="female" >
                                                            <label class="custom-control-label" for="female">Nữ</label>
                                                        </div>
                                                    @else
                                                        <div class="custom-control custom-radio">
                                                            <input class=" custom-control-input" type="radio" name="staff_gender" value="1" id="male" >
                                                            <label class=" custom-control-label" for="male">Nam</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input class=" custom-control-input" type="radio" name="staff_gender" value="0" id="female" checked>
                                                            <label class="custom-control-label" for="female">Nữ</label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="useremail">Địa Chỉ</label>
                                                    <input type="text" name="staff_address" class="form-control" value="{{ $staff->admin_dia_chi}}" required="">
                                                    @error('staff_address')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div><!-- end col -->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{--  <div class="col-sm-10">
                                                        <div class="fileupload btn btn-primary waves-effect mt-1">
                                                            <span><i class="mdi mdi-cloud-upload mr-1"></i>Upload</span>
                                                            <input type="file" class="upload" name="staff_img" multiple="" id="files">
                                                        </div>
                                                        <img width="100px" height="100px" id="image" src="{{asset('public/uploads/admin/staff/'.$staff->admin_anh)}}"/>
                                                    </div>  --}}
                                                    <div class="col-sm-10">
                                                        <div class="user-image mb-3 text-center">
                                                            <div class="imgPreview" >

                                                            </div>
                                                        </div>
                                                        <div class="custom-file">
                                                            <input type="file" class="upload custom-file-input" accept=".jpeg,.png,.gif,.jpg" value="{{ $staff->admin_anh }}" name="staff_img" id="images">
                                                            <label class="custom-file-label" for="images">Chọn Ảnh</label>
                                                            @error('staff_img')
                                                            <p class="alert alert-danger"> {{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <label class="col-form-label mt-3">Ảnh</label>
                                                        <img class=" mt-3" width="100px" height="100px" id="image" src="{{asset('public/uploads/admin/staff/'.$staff->admin_anh)}}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="useremail">Mã Căn Cước</label>
                                                    <input type="text" name="staff_admin_id" class="form-control" value="{{ $staff->admin_id}}" required="">
                                                    @error('staff_admin_id')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                <label class="col-form-label">Trạng Thái</label>
                                                    <select name="staff_status" class="form-control">
                                                        @if($staff->admin_trang_thai==1)
                                                            <option selected value="1">Hiển Thị</option>
                                                            <option value="0">Ẩn</option>
                                                        @else
                                                            <option value="1">Hiển Thị</option>
                                                            <option selected value="0">Ẩn</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Lưu</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- end settings content-->
                            </div> <!-- end tab-content -->
                        </div> <!-- end card-box-->
                    </div> <!-- end col -->
                </div>
                <!-- end row-->
            <!-- end content -->
            <!-- end page title -->
        </div>
        <!-- container -->
    </div>
    <!-- content -->
    <!-- Footer Start -->
    @include('admin.blocks.footer_admin')
    <!-- end Footer -->

</div>
@endsection
