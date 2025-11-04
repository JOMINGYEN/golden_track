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
                                <a href="{{URL::to('/product-discount')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại Khuyến Mãi</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Thêm Khuyến Mãi</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Thông Tin Khuyến Mãi</h4>
                            <hr>
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
                                <div class="col-12">
                                    <div class="p-2">
                                            <form action="{{URL::to('/product-discount-add-save')}}" enctype="multipart/form-data" method="POST" class="form-horizontal">
                                                {{ csrf_field() }}
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                    <div class="card-body">
                                                        <label class="col-form-label">Tiêu Đề</label>
                                                        <input type="text" name="product_discount_title" required="" class="form-control product_discount_title" placeholder="Tiêu đề khuyến mãi">
                                                        @error('product_discount_title')
                                                        <p class="alert alert-danger"> {{ $message }}</p>
                                                        @enderror
                                                        <label class="col-form-label">Thời Gian</label>
                                                        <input type="number" name="product_discount_time" required="" class="form-control product_discount_time" placeholder="Số ngày khuyến mãi">
                                                        @error('product_discount_time')
                                                        <p class="alert alert-danger"> {{ $message }}</p>
                                                        @enderror
                                                        <label class=" col-form-label">Ngày Khuyến Mãi</label>
                                                        <input type="date" name="product_discount_day" required="" class="form-control " >
                                                        <hr>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label class="col-form-label">Loại Khuyến Mãi</label>
                                                                <select name="product_discount_type" required="" class="form-control product_discount_type">
                                                                    <option value="1">%</option>
                                                                    <option value="0">$</option>
                                                                </select>
                                                                <label class="col-form-label">Giá Trị</label>
                                                                <input type="number" min="1" name="product_discount_number" required="" class="form-control product_discount_number" placeholder="Giá trị khuyến mãi">
                                                                @error('product_discount_number')
                                                                <p class="alert alert-danger"> {{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label class="col-form-label">Nội Dung</label>
                                                                <textarea class="form-control product_discount_content" id="product-discount-description-ckeditor" required="" name="product_discount_content"  placeholder="Nội Dung"></textarea>
                                                                @error('product_discount_content')
                                                                <p class="alert alert-danger"> {{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label class="col-form-label">Ảnh</label>
                                                                {{--  <div class="fileupload btn btn-primary waves-effect mt-1">
                                                                    <span><i class="mdi mdi-cloud-upload mr-1"></i>Upload</span>
                                                                    <input type="file" class="upload product_discount_img" required="" name="product_discount_img" multiple="" id="files">
                                                                </div>
                                                                <img width="100px" height="100px" id="image" />  --}}

                                                                <div class="col-sm-12">
                                                                    <div class="user-image mb-3 text-center">
                                                                        <div class="imgPreview" >

                                                                        </div>
                                                                    </div>
                                                                    <div class="custom-file">
                                                                        <input type="file" class="upload custom-file-input" accept=".jpeg,.png,.gif,.jpg" required="" name="product_discount_img" id="images">
                                                                        <label class="custom-file-label" for="images">Chọn Ảnh</label>
                                                                        @error('product_discount_img')
                                                                        <p class="alert alert-danger"> {{ $message }}</p>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label class="col-form-label">Trạng Thái</label>
                                                                <select name="product_discount_status" required="" class="form-control product_discount_status">
                                                                    <option value="1">Khuyến Mãi</option>
                                                                    <option value="0">Không Khuyến Mãi</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4 class="header-title">Sản Phẩm</h4>
                                                            <table id="scroll-vertical-datatable" class="table dt-responsive nowrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Chọn</th>
                                                                        <th>Ảnh</th>
                                                                        <th>Tên Sản Phẩm</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($all_product as $k=>$value )
                                                                        <tr>
                                                                            <td>
                                                                                <input type="checkbox" name="product_discount_product_id[{{$value->id }}]" id="{{  $value->id }}" value="{{ $value->id  }}" ></input>
                                                                            </td>
                                                                            <td>
                                                                                <a href="javascript: void(0);">
                                                                                    <img src="{{asset('public/uploads/admin/product/'.$value->sanpham_anh)}}" alt="contact-img" title="contact-img" class="rounded-circle avatar-lg img-thumbnail">
                                                                                </a>
                                                                            </td>
                                                                            <td>{{$value->sanpham_ten }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                            <nav>
                                                                <ul class="pagination pagination-rounded mb-3">
                                                                    {{ $all_product->links('layout.paginationlinks') }}
                                                                </ul>
                                                            </nav>
                                                        </div> <!-- end card body-->
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="text-lg-right mt-3 mt-lg-0">
                                                        <button type="submit" value="submit" class=" btn btn-success waves-effect waves-light mt-3"><i class="mdi mdi-content-save mr-1"></i>Lưu</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div> <!-- end card-box -->
                    </div><!-- end col -->
                </div>
                <!-- end row -->
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
