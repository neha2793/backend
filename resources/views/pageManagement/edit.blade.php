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
                    <h3>Page Edit</h3>
                </div>
            </div>
            <div class="account-settings-container layout-top-spacing">
                <form id="general-info" class="" action="{{route('page-management.update', $pages->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="account-content">
                        <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                    <div class="section general-info">

                                        <div class="info">
                                            <h6 class="">Page Information</h6>
                                            
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="title">Title</label>
                                                        <input type="text"  class="form-control mb-4" name="title"  id="title" value="{{$pages->title}}" placeholder="Title" >
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="body">Body</label>
                                                        <textarea  class="form-control mb-4"  id="body" name="body">{{$pages->body}}</textarea>
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
<script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
    .create( document.querySelector( '#body' ) )
    .catch( error => {
    console.error( error );
    } );

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