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
                                <a href="{{URL::to('/comment')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại Bình Luận</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SHOES</a></li>
                            <li class="breadcrumb-item active">Chi Tiết Bình Luận Đánh Giá</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Đánh Giá Từ:  {{ $comment->binhluan_ten_nguoi_danh_gia }}</h4>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="p-2">
                                        <div class="row style_comment">
                                            <div class="col-md-2">
                                                <img width="100%" src="{{URL::asset('public/backend/images/users/batman-icon.png')}}" class="img img-responsive img-thumbnail">
                                            </div>
                                            <div class="col-md-10">
                                                <p>
                                                    @for($count = 1; $count <=5; $count++)
                                                        @if($count <= $comment->binhluan_diem_danh_gia)
                                                            <i class="fa fa-star ratting_review"></i>
                                                        @else
                                                            <i class="fa fa-star ratting_no_review"></i>
                                                        @endif
                                                    @endfor
                                                </p>
                                                <p style="color:green;">{{ $comment->binhluan_ten_nguoi_danh_gia }}</p>
                                                <p style="color:#000;">{{ $comment->binhluan_ngay_danh_gia }}</p>
                                                <p>{{ $comment->binhluan_noi_dung }}</p>
                                            </div>
                                        </div>
                                        <p></p>
                                        @if ($comment_reply)
                                            @foreach ($comment_reply as $key =>$comment_rep)
                                                @if($comment_rep->admin_id > 0)
                                                    <div class="row style_comment" style="margin:5px 40px;background: aquamarine;">
                                                        <div class="col-md-2">
                                                            <img width="70%" src="{{URL::asset('public/backend/images/users/SHOES.png')}}" class="img img-responsive img-thumbnail">
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p style="color:blue;">@Admin</p>
                                                            <p style="color:#000;">{{ $comment_rep->binhluan_noi_dung }}</p>
                                                        </div>
                                                        <div class="col-md-2"><a href="{{ URL::to('/admin-reply-delete-comment/'.$comment_rep->id)}}" class="btn btn-danger waves-effect waves-light btn-sm comment-admin" onclick="return confirm('Xóa bình luận?')"><i class="mdi mdi-close mr-1"></i></a></div>
                                                    </div>
                                                    <p></p>
                                                @else
                                                    <div class="row style_comment">
                                                        <div class="col-md-2">
                                                            <img width="70%" src="{{URL::asset('public/backend/images/users/batman-icon.png')}}" class="img img-responsive img-thumbnail">
                                                        </div>
                                                        <div class="col-md-10">
                                                            <p style="color:green;">{{ $comment->binhluan_ten_nguoi_danh_gia }}</p>
                                                            <p style="color:#000;">{{ $comment->binhluan_ngay_danh_gia }}</p>
                                                            <p>{{ $comment->binhluan_noi_dung }}</p>
                                                        </div>
                                                    </div>
                                                    <p></p>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- end col-->
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card-box -->
                </div>
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="header-title">Phản Hồi Đánh Giá</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="p-2">
                                    <form action="{{ URL::to('/admin-reply-to-comment')}}" class="form-horizontal"  method="post">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <textarea name="reply_admin_comment" required="" class="form-control" placeholder="Phản hồi"></textarea>
                                                <input type="hidden" name="reply_product_id" value="{{ $comment->sanpham_id }}">
                                                @error('reply_admin_comment')
                                                <p class="alert alert-danger"> {{ $message }}</p>
                                                @enderror
                                                @if ($comment->khachhang_id )
                                                <input type="hidden" name="reply_customer_id" value="{{ $comment->khachhang_id }}">
                                                @endif
                                                <input type="hidden" name="reply_comment_id" value="{{ $comment->id }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <div class="text-lg-left mt-3 mt-lg-0">
                                                    <button type="submit" class="btn btn-success waves-effect waves-light mt-3"><i class="mdi mdi-content-save mr-1"></i>Gửi Phản Hồi</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end col-->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end card-box -->
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
    @include('admin.blocks.footer_admin')
    <!-- end Footer -->
</div>
@endsection
