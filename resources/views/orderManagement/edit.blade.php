@include('admin_layout.header')
<link rel="stylesheet" type="text/css" href="{{asset('public/plugins/dropify/dropify.min.css')}}">
<!-- BEGIN LOADER -->
<div id="load_screen"> <div class="loader"> <div class="loader-content">
    <div class="spinner-grow align-self-center"></div>
</div></div></div>
<!--  END LOADER -->
<!--  BEGIN CONTENT AREA  -->
<div class="main-container" id="container">
    <div class="overlay"></div>
    <div class="search-overlay"></div>

    <!--  BEGIN SIDEBAR  -->
    @include('admin_layout.sidebar')          
    <!--  END SIDEBAR  -->
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="page-header">
                <div class="page-title">
                    <h3>Orders</h3>
                </div>
            </div>
            <div class="account-settings-container layout-top-spacing">
                <form id="general-info" class="">
                    <!-- @csrf
                    @method('PUT') -->
                    <div class="account-content">
                        <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                    <div class="section general-info">

                                        <div class="info">
                                            <h6 class="">Product Information</h6>
                                            <div class="row">
                                                <div class="col-lg-11 mx-auto">
                                                    <div class="row">
                                                        <div class="col-xl-2 col-lg-12 col-md-4">
                                                            <div class="upload mt-4 pr-md-4">
                                                                <div class="dropify-wrapper has-preview">
                                                                    <div class="dropify-message">
                                                                        <span class="file-icon"></span> 
                                                                        <p>Click to Upload or Drag n Drop</p>
                                                                        <p class="dropify-error">Ooops, something wrong appended.</p>
                                                                    </div>
                                                                    <div class="dropify-loader" style="display: none;">
                                                                    </div>
                                                                    <div class="dropify-errors-container">
                                                                        <ul></ul>
                                                                    </div>
                                                                    <!-- <input type="file" name="profile_image"  id="input-file-max-fs" class="dropify profile_image" data-default-file="public/assets/img/200x200.jpg" data-max-file-size="2M"> -->
                                                                    <button type="button" class="dropify-clear">
                                                                        <i class="flaticon-close-fill"></i>
                                                                    </button>
                                                                    <div class="dropify-preview" style="display: block;">
                                                                    <span class="dropify-render">
                                                                        <img src="{{$transaction->image ? $transaction->image:''}}" id="category-img-tag">
                                                                    </span>
                                                                    <!-- <div class="dropify-infos">
                                                                        <div class="dropify-infos-inner">
                                                                            <p class="dropify-filename">
                                                                                <span class="file-icon"></span> 
                                                                                <span class="dropify-filename-inner">200x200.jpg</span>
                                                                            </p>
                                                                            <p class="dropify-infos-message">Upload or Drag n Drop</p>
                                                                        </div>
                                                                    </div> -->
                                                                </div>
                                                            </div>
                                                            <label for="" style="font-size: 11px;">Uploaded Picture</label>
                                                            <!-- <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i> Upload Picture</p> -->
                                                        </div>
                                                        </div>
                                                        <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                            <div class="form">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="name">Name</label>
                                                                            <input type="text" readonly class="form-control mb-4"  id="name" placeholder="Name"  value="{{$transaction->name}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="UsdPrice">Usd Price</label>
                                                                            <input type="text" readonly class="form-control mb-4"  id="UsdPrice" placeholder="Last Name" value="{{$transaction->UsdPrice}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="Price">Matic Price</label>
                                                                            <input type="text" readonly class="form-control mb-4"  id="Price" placeholder="Price"  value="{{$transaction->Price}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        
                                                                        <div class="form-group">
                                                                            <label for="Quantity">Quantity</label>
                                                                            <input type="text" readonly class="form-control mb-4"  id="Quantity"  placeholder="Quantity" value="{{$transaction->Quantity}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="Order_total">Total Amount</label>
                                                                            <input type="text" readonly class="form-control mb-4"  id="Order_total" placeholder="Order_total" value="{{$transaction->Order_total}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="Order_date">Order Date</label>
                                                                            <input type="text" readonly class="form-control mb-4"  id="Order_date" placeholder="Order Date" value="{{$transaction->Order_date ?  \Carbon\Carbon::parse($transaction->Order_date)->format('M d Y') : ''}}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                    <form id="contact" class="section contact">
                                        <div class="section general-info">
                                            <div class="info">
                                                <h5 class="">Shipping Details</h5>
                                                <div class="row">
                                                    <div class="col-md-11 mx-auto">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="Shipping_FirstName">First Name</label>
                                                                    <input type="text" readonly class="form-control mb-4" id="Shipping_FirstName" placeholder="First Name" value="{{$transaction->Shipping_FirstName}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="Shipping_lastName">Last Name</label>
                                                                    <input type="text" readonly class="form-control mb-4" id="Shipping_lastName" placeholder="Last Name" value="{{$transaction->Shipping_lastName}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="Shipping_address1">Address1</label>
                                                                    <input type="text" readonly class="form-control mb-4" id="Shipping_address1" placeholder="Address1" value="{{$transaction->Shipping_address1}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="Shipping_address1">Address2</label>
                                                                    <input type="text" readonly class="form-control mb-4" id="Shipping_address1" placeholder="Address2" value="{{$transaction->Shipping_address2}}">
                                                                </div>
                                                            </div>                                    
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="Shipping_city">City</label>
                                                                    <input type="text" readonly class="form-control mb-4" id="Shipping_city" placeholder="City" value="{{$transaction->Shipping_city}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="Shipping_state">State</label>
                                                                    <input type="text" readonly class="form-control mb-4" id="Shipping_state" placeholder="State" value="{{$transaction->Shipping_state}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="Shipping_Zipcode">Zipcode</label>
                                                                    <input type="text" readonly class="form-control mb-4" id="Shipping_Zipcode" placeholder="Zipcode" value="{{$transaction->Shipping_Zipcode}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                    <div class="account-settings-footer">
                        <div class="as-footer-container">
                            <!-- <button id="multiple-reset" type="button" class="btn btn-warning">Reset All</button>
                            <button id="multiple-messages" type="submit" class="btn btn-primary">Save Changes</button> -->
                            <!-- <div class="blockui-growl-message" style="cursor: default;">
                                <i class="flaticon-double-check"></i>&nbsp; Settings Saved Successfully
                            </div> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('admin_layout.footer')
    </div>
</div>
<!--  END CONTENT AREA  -->

<script>
    $(document).ready(function(e) {
        toastr.options.timeOut = 10000;
        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}');
        @elseif(Session::has('success'))
            toastr.success('{{ Session::get('success') }}');
        @endif

        $('.profile_image').change(function(){
            
            let reader = new FileReader();
         
            reader.onload = (e) => { 
         
              $('#category-img-tag').attr('src', e.target.result); 
            }
         
            reader.readAsDataURL(this.files[0]); 
           
        });
           
    });   
</script>