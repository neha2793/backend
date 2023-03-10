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
                        <h3>User Details</h3>
                    </div>
                </div>
                <div class="account-settings-container layout-top-spacing">
                    <form id="general-info" class="section general-info" method="POST" action="{{route('user-varification-request.update',$account_varification_data->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="account-content">
                            <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                        <div class="info">
                                            <div class="row" >
                                                <div class="col-md-6">
                                                    <h6 class="">General Information</h6>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check verify_radio">
                                                        <label for="verify">Verify
                                                            <input type="radio" class="form-input"  id="verify" name="verify_status" value="verify"checked>
                                                        </label>
                                                        <label for="reject">Reject
                                                            <input type="radio" class="form-input"  id="reject" name="verify_status" value="reject">
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                            
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
                                                                    <!-- <input type="file" name="profile_image"  id="input-file-max-fs" class="dropify profile_image" data-default-file="assets/img/200x200.jpg" data-max-file-size="2M"> -->
                                                                    <button type="button" class="dropify-clear">
                                                                        <i class="flaticon-close-fill"></i>
                                                                    </button>
                                                                    <div class="dropify-preview" style="display: block;">
                                                                        <span class="dropify-render">
                                                                            <img src="{{$account_varification_data->profile_image ? env('API_BASE_URL').$account_varification_data->profile_image:''}}" id="category-img-tag">
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
                                                            <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i> Upload Picture</p>
                                                        </div>
                                                        </div>
                                                        <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                            <div class="form">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="name">First Name</label>
                                                                            <input type="text" class="form-control mb-4"  id="name" disabled placeholder="First Name" name="name" value="{{$account_varification_data->name}}">
                                                                        </div>
                                                                      
                                                                        <div class="form-group">
                                                                            <label for="email">Email</label>
                                                                            <input type="text" class="form-control mb-4"  id="email" disabled placeholder="Email" name="email" value="{{$account_varification_data->email}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="city">City</label>
                                                                            <input type="text" class="form-control mb-4"  id="city" disabled placeholder="City" name="city" value="{{$account_varification_data->city}}">
                                                                        </div> <div class="form-group">
                                                                            <label for="country">Country</label>
                                                                            <input type="text" class="form-control mb-4"  id="country" disabled placeholder="Country" name="country" value="{{$account_varification_data->country}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="facebook_link">Facebook Link</label>
                                                                            <input type="text" class="form-control mb-4"  id="facebook_link" disabled placeholder="Facebook Link" name="facebook_link" value="{{$account_varification_data->facebook_link}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="youtube_link">Youtube Link</label>
                                                                            <input type="text" class="form-control mb-4"  id="youtube_link" disabled placeholder="Youtube Link" name="youtube_link" value="{{$account_varification_data->youtube_link}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="lastname">Last Name</label>
                                                                            <input type="text" class="form-control mb-4"  id="lastname" disabled placeholder="Last Name" name="lastname" value="{{$account_varification_data->lastname}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="phone_number">Phone Number</label>
                                                                            <input type="text" class="form-control mb-4"  id="phone_number" name="phone_number" disabled placeholder="Phone Number" value="{{$account_varification_data->phone_number}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="state">State</label>
                                                                            <input type="text" class="form-control mb-4"  id="state" disabled placeholder="State" name="state" value="{{$account_varification_data->state}}">
                                                                        </div> <div class="form-group">
                                                                            <label for="pincode">Pincode</label>
                                                                            <input type="text" class="form-control mb-4"  id="pincode" disabled placeholder="Pincode" name="pincode" value="{{$account_varification_data->pincode}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="twitter_link">Twitter Link</label>
                                                                            <input type="text" class="form-control mb-4"  id="twitter_link" disabled placeholder="Twitter Link" name="twitter_link" value="{{$account_varification_data->twitter_link}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="wallet_address">Wallet Address</label>
                                                                            <input type="text" class="form-control mb-4"  id="wallet_address" name="wallet_address" disabled placeholder="Wallet Address" value="{{$account_varification_data->wallet_address}}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label for="bio">Bio</label>
                                                                    <input type="text" class="form-control mb-4" disabled id="bio" name="bio" placeholder="Bio" value="{{$account_varification_data->bio}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="address">Address</label>
                                                                    <input type="text" class="form-control mb-4" disabled id="address" name="address" placeholder="Address" value="{{$account_varification_data->address}}">
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
                        <div class="account-settings-footer">
                            <div class="as-footer-container">
                                <!-- <button id="multiple-reset" type="button" class="btn btn-warning">Reset All</button> -->
                                <button id="multiple-messages" type="submit" class="btn btn-primary">Save Changes</button>
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