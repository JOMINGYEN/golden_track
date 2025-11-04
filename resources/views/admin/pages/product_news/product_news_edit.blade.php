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
                                <a href="{{URL::to('/product-news')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Back</a>
                                <a href="{{URL::to('/product-news-add')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Add New</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SHOES</a></li>
                            <li class="breadcrumb-item active">News Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">News Information</h4>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="p-2">
                                        <form action="{{ URL::to('/product-news-edit-save/'.$product_news->id) }}" enctype="multipart/form-data" class="form-horizontal" role="form"  method="post" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                                           {{ csrf_field() }}
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" >News Information</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Title</label>
                                                    <input type="text" name="product_news_title" value="{{ $product_news->baiviet_tieu_de }}" required="" class="form-control" placeholder="SHOES Store">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Content</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Content</label>
                                                    <textarea class="form-control" name="product_news_content" required="" placeholder="Content..">{{ $product_news->baiviet_noi_dung }}</textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Status</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Status</label>
                                                    <select name="product_news_status" class="form-control">
                                                        @if( $product_news->baiviet_trang_thai ==1)
                                                            <option selected value="1">Show</option>
                                                            <option value="0">Hide</option>
                                                        @else
                                                            <option value="1">Show</option>
                                                            <option selected value="0">Hide</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Images</label>
                                                <div class="col-sm-10">
                                                    <div class="fileupload btn btn-primary waves-effect mt-1">
                                                        <span><i class="mdi mdi-cloud-upload mr-1"></i>Upload</span>
                                                        <input type="file" value="{{ $product_news->baiviet_anh }}" class="upload" name="product_news_img" multiple="" id="files">
                                                    </div>
                                                    <img width="100px" height="100px" id="image" src="{{asset('public/uploads/admin/productnews/'.$product_news->baiviet_anh)}}"/>
                                                </div>
                                            </div>
                                            <hr>

                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="text-lg-right mt-3 mt-lg-0">
                                                        <button type="submit" name="submit" value="Upload" class="btn btn-success waves-effect waves-light mt-3"><i class="mdi mdi-content-save mr-1"></i>Save</button>
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
