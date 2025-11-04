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
                                <a href="{{URL::to('/staff')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại Nhân Viên</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Thêm Nhân Viên</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- content -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <h4 class="header-title">Thêm Nhân Viên</h4>
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
                            <hr>
                            <form action="{{URL::to('/staff-add-save')}}" class="parsley-form" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="pass1">Tên<span class="text-danger">*</span></label>
                                    <input name="staff_name" type="text" placeholder="Tên" required="" class="form-control">
                                    @error('staff_name')
                                    <p class="alert alert-danger"> {{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="pass1">Email<span class="text-danger">*</span></label>
                                    <input name="staff_email" type="text" placeholder="Email" required="" class="form-control">
                                    @error('staff_email')
                                    <p class="alert alert-danger"> {{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="pass1">Mật Khẩu<span class="text-danger">*</span></label>
                                    <input name="staff_password" type="password" placeholder="Mật khẩu" required="" class="form-control">
                                    @error('staff_password')
                                    <p class="alert alert-danger"> {{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="passWord2">Xác Nhận Mật Khẩu <span class="text-danger">*</span></label>
                                    <input name="staff_password_confirm" type="password" required="" placeholder="Xác nhận mật khẩu" class="form-control">
                                    @error('staff_password_confirm')
                                    <p class="alert alert-danger"> {{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Quyền</label>
                                    <select name="admin_role" class="form-control">
                                        <option value="1">Admin</option>
                                        <option value="2">Nhân viên</option>
                                        <option value="3">Giao Hàng</option>
                                    </select>
                                </div>

                                <div class="form-group text-right mb-0">
                                    <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
                                        Lưu
                                    </button>
                                </div>
                            </form>
                        </div> <!-- end card-box -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            <!-- end content -->

            <!-- end page title -->
        </div>
        <!-- container -->

    </div>
    <!-- content -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    2016 - 2019 &copy; Minton theme by <a href="#">Thien</a>
                </div>
                <div class="col-md-6">
                    <div class="text-md-right footer-links d-none d-sm-block">
                        <a href="#">About Us</a>
                        <a href="#">Help</a>
                        <a href="#">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->

</div>
@endsection
