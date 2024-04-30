<!DOCTYPE html>
<html lang="fr">
<head> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \Session::get('app.title') }} :: {{ trans('app.signin') }}</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ \Session::get('app.favicon') }}" type="image/x-icon" />
    <!-- font-awesome -->
    <link href="{{ asset('public/assets/css/font-awesome.min.css') }}" rel='stylesheet'>
    <!-- template bootstrap -->
    <link href="{{ asset('public/assets/css/template.min.css') }}" rel='stylesheet prefetch'> 
    <!-- select2 -->
    <link href="{{ asset('public/assets/css/select2.min.css') }}" rel='stylesheet'>
    <!-- Jquery  -->
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
</head>
<body class="cm-login">
    <div class="loader">
        <div>
            <img src="public/assets/img/icons/lpu.png" width="150px" height="150px">
        </div>
    </div>

    <div class="text-center" style="padding:35px 0 30px 0;background:#fff;border-bottom:1px solid #ddd;">
        <h2 class="text-primary text-center text-uppercase" style="color:#A3202B;">{{ \Session::get('app.title') }}</h2>
        <img src="{{ asset('public/assets/img/icons/lpu.png') }}" width="300" >
    </div>
    
    <div class="col-sm-6 col-md-4 col-lg-3" style="margin:30px auto; float:none;">
        @include('backend.common.info')
        <!-- Starts of Message -->
        <div class="col-xs-12">
            @yield('info.message')

        </div>

        {{ Form::open(['url' => 'login', 'class'=>'']) }}
        <div class="col-xs-12">
            <div class="form-group">
                <label for="email" class="control-label sr-only">{{ trans('app.email') }}</label>
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></div>
                    <input type="text" name="email" class="form-control" id="email" placeholder="{{ trans('app.email') }}"  value="{{ old('email') }}" autocomplete="off">
                </div>
                <span class="text-danger">{{ $errors->first('email') }}</span>
            </div>
            <div class="form-group">
                <label for="password" class="control-label sr-only">{{ trans('app.password') }}</label>
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-fw fa-lock"></i></div>
                    <input type="password" name="password" id="password" class="form-control" placeholder="{{ trans('app.password') }}" value="{{ old('password') }}" autocomplete="off">
                </div>
                <span class="text-danger">{{ $errors->first('password') }}</span>
            </div>
        </div>
        <!-- <div class="col-xs-6">
            @yield('info.language')
        </div> -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-block btn-primary" style="background-color:#A3202B;">{{ trans('app.signin')}}</button>
        </div> 
</div>
        {{ Form::close() }}   
        
        @yield('info.login-credentials')
    </div>  

    <footer class="cm-footer">
        <span class="col-sm-8 col-xs-12 text-left">@yield('info.powered-by') @yield('info.version')</span>
        <span class="col-sm-4 col-xs-12 text-right hidden-xs"> {{ \Session::get('app.copyright_text') }}</span>
    </footer> 

    <!-- Jquery  -->
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
    <!-- bootstrp -->
    <script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
    <!-- select2 -->
    <script src="{{ asset('public/assets/js/select2.min.js') }}"></script>

    <script type="text/javascript">

    $(function() { 
        $('table tbody tr').on('click', function() {
            $("input[name=email]").val($(this).children().first().text());
            $("input[name=password]").val($(this).children().first().next().text());
        }); 

        // select2
        $("select").select2();

        //language switch
        $("#lang-select").on('change', function() {
            var x = $(this).val();
            $.ajax({
               type:'GET',
               url:'{{ URL::to("common/language/") }}',
               data: {
                  'locale' : x, 
                  '_token' : '<?php echo csrf_token() ?>'
               },
               success:function(data){
                  history.go(0);
               }, error: function() {
                alert('failed');
               }
            });       
        }); 
    }(jQuery));

    //preloader
    $(window).load(function() {
        $(".loader").fadeOut("slow");;
    });

    // Wait for the page to load
    window.addEventListener('load', function() {
        // Check if the URL contains the ID of the button
        if (window.location.hash === '#signin-button') {
            // Simulate a click on the button
            document.getElementById('signin-button').click();
        }
    });
    </script> 
</body>
</html>
