@extends('master')
@section('content')
    <div class="inner-header">
            <div class="container">
                <div class="pull-left">
                    <h6 class="inner-title">Sản phẩm {{$sanpham->name}} </h6>
                </div>
                <div class="pull-right">
                    <div class="beta-breadcrumb font-large">
                        <a href="{{route('trangchu')}}">Trang chủ</a> / <span>Thông tin chi tiết sản phẩm</span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
    </div>

    <div class="container">
            <div id="content">
                <div class="row">
                    <div class="col-sm-9">

                        <div class="row">
                            <div class="col-sm-4">
                                <img src="source/image/product/{{$sanpham->image}}" alt="" height="250px">
                            </div>
                            <div class="col-sm-8">
                                <div class="single-item-body">
                                    <p class="single-item-title"><h2>{{$sanpham->name}}</h2></p>
                                    <p class="single-item-price" style="font-size:18px">
											@if($sanpham->promotion_price ==0)
												<span class="flash-del">{{number_format($sanpham->unit_price)}} đồng</span>
											@else
												<span>{{number_format($sanpham->unit_price)}} đồng</span>
                                                <span class="flash-sale">{{number_format($sanpham->promotion_price)}} đồng</span>

											@endif
                                    </p>
                                </div>

                                <div class="clearfix"></div>
                                <div class="space20">&nbsp;</div>

                                <div class="single-item-desc">
                                    <p>{{$sanpham->description}}</p>

                                </div>
                                <div class="space20">&nbsp;</div>
                                <p>Số lượng:</p>
                                <div class="single-item-options">
                                   
                                    <select class="wc-select" name="color">
                                        <option>Số lượng</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                    <a class="add-to-cart" href="{{route('themgiohang',$sanpham->id)}}"><i class="fa fa-shopping-cart"></i></a>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>

                        <div class="space40">&nbsp;</div>
                        <div class="woocommerce-tabs">
                            <ul class="tabs">
                                <li><a href="#tab-description">Mô tả</a></li>
                                <li><a href="#tab-reviews">Reviews (0)</a></li>
                            </ul>

                            <div class="panel" id="tab-description">
                                <p>{{$sanpham->description}}</p>
                            </div>
                            <!-- <div class="panel" id="tab-reviews">
                                <p>No Reviews</p>
                            </div> -->
                        </div>
                        <div class="space50">&nbsp;</div>
                        <div class="beta-products-list">
                            <h4>Sản phẩm tương tự</h4>

                            <div class="row">
                                @foreach($sp_tuongtu as $sptt)
                                <div class="col-sm-4">
                                    <div class="single-item">
                                        <div class="single-item-header">
                                            <a href="{{route('chitietsanpham',$sptt->id)}}"><img src="source/image/product/{{$sptt->image}}" alt="" height="150px"></a>
                                        </div>
                                        <div class="single-item-body">
                                            <p class="single-item-title">{{$sptt->name}}</p>
                                            <p class="single-item-price" style="font-size=18px">
                                                @if($sptt->promotion_price != 0)
                                                    <span class="flash-del">{{number_format($sptt->unit_price)}} đồng</span>
                                                @else
                                                    <span class="flash-del">{{number_format($sptt->unit_price)}} đồng</span>
                                                    <span class="flash-sale">{{number_format($sptt->promotion_price)}} đồng</span>

                                                @endif
                                            </p>
                                        </div>
                                        <div class="single-item-caption">
                                            <a class="add-to-cart pull-left" href="{{route('chitietsanpham',$sptt->id)}}"><i class="fa fa-shopping-cart"></i></a>
                                            <a class="beta-btn primary" href="{{route('chitietsanpham',$sptt->id)}}">Details <i class="fa fa-chevron-right"></i></a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="row">{{$sp_tuongtu->links()}}</div>
                        </div> <!-- .beta-products-list -->
                    </div>
                    <div class="col-sm-3 aside">
                        <div class="widget">
                            <h3 class="widget-title">Best Sellers</h3>
                            <div class="widget-body">
                                <div class="beta-sales beta-lists">
                                    @foreach($sanpham_khuyenmai as $spkm)
                                    <div class="media beta-sales-item">
                                        <a class="pull-left" href="{{route('chitietsanpham',$spkm->id)}}"><img src="source/image/product/{{$spkm->image}}" alt=""></a>
                                        <div class="media-body">
                                                {{$spkm->name}}
                                            <span class="beta-sales-price">{{number_format($spkm->promotion_price)}} đồng</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div> <!-- best sellers widget -->
                        <div class="widget">
                            <h3 class="widget-title">New Products</h3>
                            <div class="widget-body">
                                <div class="beta-sales beta-lists">
                                    @foreach($new_product as $new))
                                    <div class="media beta-sales-item">
                                        <a class="pull-left" href="{{route('chitietsanpham',$new->id)}}"><img src="source/image/product/{{$new->image}}" alt=""></a>
                                        <div class="media-body">
                                            {{$new->name}}
                                            <span class="beta-sales-price">{{number_format($spkm->promotion_price)}} đồng</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div> <!-- best sellers widget -->
                    </div>
                </div>
            </div> <!-- #content -->
    </div> <!-- .container -->
@endsection