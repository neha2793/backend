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
                        <h3>Slime Tour Edit</h3>
                    </div>
                </div>
                <div class="account-settings-container layout-top-spacing">
                    <form id="general-info" class="section general-info" action="{{route('slime-tour.update', $slimeTour->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="account-content">
                            <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                        <div class="info">
                                            <h6 class="">General Information</h6>
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
                                                                    <input type="file" name="image"  id="input-file-max-fs" class="dropify image" data-default-file="public/assets/img/200x200.jpg" data-max-file-size="2M">
                                                                    <button type="button" class="dropify-clear">
                                                                        <i class="flaticon-close-fill"></i>
                                                                    </button>
                                                                    <div class="dropify-preview" style="display: block;">
                                                                    <span class="dropify-render">
                                                                        <img src="{{$slimeTour->image ? env('API_BASE_URL').$slimeTour->image:''}}" id="category-img-tag">
                                                                    </span>
                                                                    <div class="dropify-infos">
                                                                        <div class="dropify-infos-inner">
                                                                            <p class="dropify-filename">
                                                                                <span class="file-icon"></span> 
                                                                                <span class="dropify-filename-inner">200x200.jpg</span>
                                                                            </p>
                                                                            <p class="dropify-infos-message">Upload or Drag n Drop</p>
                                                                        </div>
                                                                    </div>
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
                                                                            <input type="text" name="name" class="form-control mb-4"  id="name" placeholder="Name"  value="{{$slimeTour->name}}">
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        
                                                                        <div class="form-group">
                                                                            <label for="date">Date</label>
                                                                            <input type="text" name="date" class="form-control mb-4 date"  id="date" placeholder="Date"  value="{{$slimeTour->date ?  \Carbon\Carbon::parse($slimeTour->date)->format('M d Y') : ''}}" >
                                                                            <!-- <div class="dateDiv">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </div> -->
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="Location">Location</label>
                                                                            <textarea  id="Location" name="location"  class="form-control mb-4">{{$slimeTour->location}}</textarea>
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

        $('.image').change(function(){
            
            let reader = new FileReader();
         
            reader.onload = (e) => { 
         
              $('#category-img-tag').attr('src', e.target.result); 
            }
         
            reader.readAsDataURL(this.files[0]); 
           
        });
      
        $('.date').datepicker({    
            format: 'M-d-yyyy'
        });    

           
    });   
</script>