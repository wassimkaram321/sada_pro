@extends('layouts.back-end.app')

@section('content')
<div class="content container-fluid">
    <div class="row pb-3" >
        <div class="col-md-6" >
            <div class="card" style="height: 200px;">
                <div class="card-header text-capitalize">
                    <h5 class="text-center"><i class="tio-settings-outlined"></i>
                         {{\App\CPU\translate('shipping_responsibility')}}
                    </h5>
        
                </div>
                @php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 pl-8 text-capitalize">
                            <div class="row">
                                <div class="form-check">
                                    <input onclick="shipping_responsibility(this.value);" class="form-check-input" type="radio" name="shipping_res" value="inhouse_shipping" id="inhouse_shipping" {{ $shippingMethod=='inhouse_shipping'?'checked':'' }}>
                                    <label class="form-check-label" for="inhouse_shipping">
                                        {{\App\CPU\translate('inhouse_shipping')}}
                                    </label>
                                  </div>
                            </div>
                            <div class="row">
                                <div class="form-check">
                                    <input onclick="shipping_responsibility(this.value);" class="form-check-input" type="radio" name="shipping_res" value="sellerwise_shipping" id="sellerwise_shipping" {{ $shippingMethod=='sellerwise_shipping'?'checked':'' }}>
                                    <label class="form-check-label" for="sellerwise_shipping">
                                        {{\App\CPU\translate('seller_wise_shipping')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php($admin_shipping = \App\Model\ShippingType::where('seller_id',0)->first())
        @php($shippingType =isset($admin_shipping)==true?$admin_shipping->shipping_type:'order_wise')
        <div class="col-md-6 mt-3 mt-sm-0">
            <div class="card" style="height: 200px;">
                <div class="card-header text-capitalize">
                    <h4>{{\App\CPU\translate('choose_shipping_method')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-capitalize" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            <label for="" id="for_inhouse_deliver" >{{\App\CPU\translate('for_inhouse_deliver')}}</label>
                            <select class="form-control text-capitalize" name="shippingCategory" onchange="shipping_type(this.value);"
                                        style="width: 100%">
                                <option value="0" selected disabled>---{{\App\CPU\translate('select')}}---</option>
                                <option value="order_wise" {{$shippingType=='order_wise'?'selected':'' }} >{{\App\CPU\translate('order_wise')}} </option>
                                <option  value="category_wise" {{$shippingType=='category_wise'?'selected':'' }} >{{\App\CPU\translate('category_wise')}}</option>
                                <option  value="product_wise" {{$shippingType=='product_wise'?'selected':'' }}>{{\App\CPU\translate('product_wise')}}</option>
                            </select>
                        </div>
                        <div class="col-12 mt-2" id="product_wise_note">
                            <p class="m-2" style="color: red;">{{\App\CPU\translate('note')}}: {{\App\CPU\translate("Please_make_sure_all_the product's_delivery_charges_are_up_to_date.")}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php($categories = App\Model\Category::where(['position' => 0])->get())
    <div class="card" id="update_category_shipping_cost">
        <div class="card-header text-capitalize">
            <h4>{{\App\CPU\translate('update_category_shipping_cost')}}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="col-12">
                    <table class="table table-bordered" width="100%" cellspacing="0"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <thead>
                            <tr>
                                <th scope="col">{{\App\CPU\translate('sl#')}}</th>
                                <th scope="col">{{\App\CPU\translate('category_name')}}</th>
                                <th scope="col">{{\App\CPU\translate('cost_per_product')}}</th>
                                <th scope="col">{{\App\CPU\translate('multiply_with_QTY')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form action="{{route('admin.business-settings.category-shipping-cost.store')}}" method="POST">
                                @csrf
                                @foreach ($all_category_shipping_cost as $key=>$item)
                                    <tr>
                                        <td>
                                            {{$key+1}}
                                        </td>
                                        <td>
                                            {{$item->category!=null?$item->category->name:\App\CPU\translate('not_found')}}
                                        </td>
                                        <td>
                                            <input type="hidden" class="form-control" name="ids[]" value="{{$item->id}}">
                                            <input type="number" class="form-control" min="0" step="0.01" name="cost[]" value="{{\App\CPU\BackEndHelper::usd_to_currency($item->cost)}}">
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" name="multiplyQTY[]"
                                                       id="" value="{{$item->id}}" {{$item->multiply_qty == 1?'checked':''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4">
                                        <button type="submit" class="btn btn-primary ">{{\App\CPU\translate('Update')}}</button>
                                    </td>
                                </tr>
                            </form>
                        </tbody>
                        
                    </table>
                    
                    
                </div>
            </div>
        </div>
    </div>
    <div id="order_wise_shipping">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-capitalize">
                        <h4>{{\App\CPU\translate('add_order_wise_shipping')}}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.business-settings.shipping-method.add')}}"
                              style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                              method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <label for="title">{{\App\CPU\translate('title')}}</label>
                                        <input type="text" name="title" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <label for="duration">{{\App\CPU\translate('duration')}}</label>
                                        <input type="text" name="duration" class="form-control"
                                               placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('4 to 6 days')}}">
                                    </div>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <label for="cost">{{\App\CPU\translate('cost')}}</label>
                                        <input type="number" min="0" max="1000000" name="cost" class="form-control"
                                               placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('10')}} ">
                                    </div>
                                </div>
                            </div>
    
                            <div class="card-footer">
                                <button type="submit"
                                        class="btn btn-primary ">{{\App\CPU\translate('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-capitalize">
                        <h4>{{\App\CPU\translate('order_wise_shipping_method')}} <span style="color: red;">({{ $shipping_methods->count() }})</span></h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0"
                                   style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                <thead>
                                <tr>
                                    <th scope="col">{{\App\CPU\translate('sl#')}}</th>
                                    <th scope="col">{{\App\CPU\translate('title')}}</th>
                                    <th scope="col">{{\App\CPU\translate('duration')}}</th>
                                    <th scope="col">{{\App\CPU\translate('cost')}}</th>
                                    <th scope="col">{{\App\CPU\translate('status')}}</th>
                                    <th scope="col" style="width: 50px">{{\App\CPU\translate('action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shipping_methods as $k=>$method)
                                    <tr>
                                        <th scope="row">{{$k+1}}</th>
                                        <td>{{$method['title']}}</td>
                                        <td>
                                            {{$method['duration']}}
                                        </td>
                                        <td>
                                            {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($method['cost']))}}
                                        </td>
    
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="status"
                                                       id="{{$method['id']}}" {{$method->status == 1?'checked':''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
    
                                        <td>
                                            <div class="dropdown float-right">
                                                <button class="btn btn-seconary btn-sm dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false">
                                                    <i class="tio-settings"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item"
                                                       href="{{route('admin.business-settings.shipping-method.edit',[$method['id']])}}">{{\App\CPU\translate('Edit')}}</a>
                                                    <a class="dropdown-item delete" style="cursor: pointer;"
                                                       id="{{ $method['id'] }}">{{\App\CPU\translate('Delete')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    $( document ).ready(function() {
        let shipping_responsibility ='{{$shippingMethod}}';
        console.log(shipping_responsibility);
        if(shipping_responsibility === 'sellerwise_shipping')
        {
            $("#for_inhouse_deliver").show();
        }else{
            $("#for_inhouse_deliver").hide();
        }
        let shipping_type = '{{$shippingType}}';

        if(shipping_type==='category_wise')
        {
            $('#product_wise_note').hide();
            $('#order_wise_shipping').hide();
            $('#update_category_shipping_cost').show();

        }else if(shipping_type==='order_wise'){
            $('#product_wise_note').hide();
            $('#update_category_shipping_cost').hide();
            $('#order_wise_shipping').show();
        }else{
            
            $('#update_category_shipping_cost').hide();
            $('#order_wise_shipping').hide();
            $('#product_wise_note').show();
        }
    });
</script>
<script>
    function shipping_responsibility(val){
        if(val=== 'inhouse_shipping'){
            $( "#sellerwise_shipping" ).prop( "checked", false );
            $("#for_inhouse_deliver").hide();
        }else{
            $( "#inhouse_shipping" ).prop( "checked", false );
            $("#for_inhouse_deliver").show();
        }
        console.log(val);
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.shipping-method.shipping-store')}}",
                method: 'POST',
                data: {
                    shippingMethod: val
                },
                success: function (data) {
                    
                        
                        //window.location.reload();
                        toastr.success("{{\App\CPU\translate('shipping_responsibility_updated_successfully!!')}}");
                    
                }
            });
    }
</script>
<script>
    function shipping_type(val)
    {
        console.log(val);
        if(val==='category_wise')
        {
            $('#product_wise_note').hide();
            $('#order_wise_shipping').hide();
            $('#update_category_shipping_cost').show();
        }else if(val==='order_wise'){
            $('#product_wise_note').hide();
            $('#update_category_shipping_cost').hide();
            $('#order_wise_shipping').show();
        }else{
            $('#update_category_shipping_cost').hide();
            $('#order_wise_shipping').hide();
            $('#product_wise_note').show(); 
        }
        
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.shipping-type.store')}}",
                method: 'POST',
                data: {
                    shippingType: val
                },
                success: function (data) {
                    toastr.success("{{\App\CPU\translate('shipping_method_updated_successfully!!')}}");
                }
            });
    }
</script>
<script>
    // Call the dataTables jQuery plugin
    $(document).on('change', '.status', function () {
        var id = $(this).attr("id");
        if ($(this).prop("checked") == true) {
            var status = 1;
        } else if ($(this).prop("checked") == false) {
            var status = 0;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('admin.business-settings.shipping-method.status-update')}}",
            method: 'POST',
            data: {
                id: id,
                status: status
            },
            success: function () {
                toastr.success('{{\App\CPU\translate('order wise shipping method Status updated successfully')}}');
            }
        });
    });
    $(document).on('click', '.delete', function () {
        var id = $(this).attr("id");
        Swal.fire({
            title: '{{\App\CPU\translate('Are you sure delete this')}} ?',
            text: "{{\App\CPU\translate('You will not be able to revert this')}}!",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{\App\CPU\translate('Yes, delete it')}}!'
        }).then((result) => {
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.business-settings.shipping-method.delete')}}",
                    method: 'POST',
                    data: {id: id},
                    success: function () {
                        toastr.success('{{\App\CPU\translate('Order Wise Shipping Method deleted successfully')}}');
                        location.reload();
                    }
                });
            }
        })
    });
</script>
@endpush