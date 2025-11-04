<div class="col-12">
    <div class="banner_slider slider_two">
        <div class="slider_active owl-carousel">
            @php
                $count=1;
            @endphp
            @foreach ($slide_show as $key => $slideshow)
                @if ($count<=5)
                    <div class="single_slider" style="background-image: url({{asset('public/uploads/admin/slideshow/'. $slideshow->slidequangcao_anh)}})">
                        <div class="slider_content">
                            <div class="slider_content_inner">
                                <h1>{{ $slideshow->slidequangcao_tieu_de }}</h1>
                                <p>{{ $slideshow->slidequangcao_noi_dung }}</p>
                                <a href="{{ URL::to('/shop-now') }}">shop now</a>
                            </div>
                        </div>
                    </div>
                    @php
                        $count++;
                    @endphp
                @else
                @break
                @endif
            @endforeach
        </div>
    </div>
</div>
