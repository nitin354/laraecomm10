@extends('front.layouts.app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="row">            
                <div class="col-md-3 sidebar">
                    <div class="sub-title">
                        <h2>Categories</h3>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">

                            @if($categories->isNotEmpty())
                                @foreach($categories as $key => $category)
                               
                                <div class="accordion-item">
                                @if($category->sub_category->isNotEmpty())
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{$key}}" aria-expanded="false" aria-controls="collapseOne-{{$key}}">
                                            {{$category->name}}
                                        </button>
                                    </h2>
                                    @else
                                    <a href="{{route("front.shop", $category->slug)}}" class="nav-item nav-link {{($categoryselected == $category->id) ? 'text-primary' : ''}}">{{$category->name }}</a>
                                    @endif


                                    @if($category->sub_category->isNotEmpty())
                                    <div id="collapseOne-{{$key}}" class="accordion-collapse collapse {{($categoryselected == $category->id) ? 'show' : ''}}" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                        <div class="accordion-body">
                                            <div class="navbar-nav">
                                            @foreach($category->sub_category as $sub_category)
                                                <a href="{{route("front.shop", [$category->slug, $sub_category->slug])}}" class="nav-item nav-link {{($subcategoryselected == $sub_category->id) ? 'text-primary' : ''}}">{{$sub_category->name }}</a>
                                               
                                            @endforeach                                         
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                              

                                @endforeach
                            @endif
                                              
                            </div>
                        </div>
                    </div>

                    <!-- <div class="sub-title mt-5">
                        <h2>Brand</h3>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Canon
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                <label class="form-check-label" for="flexCheckChecked">
                                    Sony
                                </label>
                            </div>                 
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                <label class="form-check-label" for="flexCheckChecked">
                                    Oppo
                                </label>
                            </div> 
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                <label class="form-check-label" for="flexCheckChecked">
                                    Vivo
                                </label>
                            </div>                 
                        </div>
                    </div> -->

                    <div class="sub-title mt-5">
                        <h2>Price</h3>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                        <input type="text" class="js-range-slider" name="my_range" value=""/>
                        
    
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row pb-3">
                        <div class="col-12 pb-1">
                            <div class="d-flex align-items-center justify-content-end mb-4">
                                <div class="ml-2">
                                    <!-- <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Sorting</button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#">Latest</a>
                                            <a class="dropdown-item" href="#">Price High</a>
                                            <a class="dropdown-item" href="#">Price Low</a>
                                        </div>
                                    </div>-->
                                    <select name="sort" id="sort" class="form-control">
                                        <option {{ ($sort == '' || $sort == 'latest') ? 'selected' : '' }} value="latest">Latest</option>
                                        <option {{ ($sort == 'price_desc') ? 'selected' : '' }} value="price_desc">Price High</option>
                                        <option {{ ($sort == 'price_asc') ? 'selected' : '' }} value="price_asc">Price Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if($products->isNotEmpty())
                        @foreach($products as $product)
                        @php

        $productimg = $product->product_images->first();
        //dd($productimg->image);

                        @endphp
                        <div class="col-md-4">
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="{{route('front.product', [$product->slug])}}" class="product-img">
                                    @if(!empty ($productimg->image))    
                                    <img class="card-img-top" src="{{asset('uploads/product/small/' . $productimg->image)}}" alt="">
                                    @else
                                    <img class="card-img-top" src="{{asset('admin-assets/img/default-150x150.png')}}" alt="">
                                    @endif
                                
                                    </a>
                                    
                                    
                                    
                                    <a class="whishlist" href="222"><i class="far fa-heart"></i></a>                            

                                    <div class="product-action">
                                        <a class="btn btn-dark" href="javascript:void(0);" onClick="addToCart( {{ $product->id }} );">
                                            <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </a>                            
                                    </div>
                                </div>                        
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="{{route('front.product', [$product->slug])}}">{{$product->title}}</a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>${{$product->price}}</strong></span>
                                        @if($product->compare_price > $product->price)
                                        <span class="h6 text-underline"><del>${{$product->compare_price}}</del></span>
                                        @endif
                                    </div>
                                </div>                        
                            </div>                                               
                        </div>  
                        @endforeach
                        @endif
               
                        
                        

                       

                    
                      
                      
                     

                     

                        <div class="col-md-12 pt-5">
                            {{ $products->withQueryString()->links()}}
                            <!-- <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection

@section('custom_js')
<script>
 rangeslider = $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: 0,
        max: 1000,
        from: {{$pricemin}},
        to: {{$pricemax}},
        step:10,
        skin:"round",
        max_postfix:"+",
        prefix:"$",
        onFinish:function(){
            apply_filter();
        }
       
    });

var slider = $(".js-range-slider").data("ionRangeSlider");

$("#sort").change(function(){
    apply_filter();
});

    function apply_filter(){
        var brands =[];
        var url = '{{ url()->current() }}?';

        //Prince Range Filter
        url +='&price_min='+slider.result.from+'&price_max='+slider.result.to;

        //sorting Filter

        url +='&sort='+$('#sort').val();

        window.location.href = url;
    }
    
</script>
@endsection
