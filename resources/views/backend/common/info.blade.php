@section('info.message')
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade in shadowed alert-dismissible mt-1 mb-1">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <p><i class="fa fa-check"></i> {{ session('message') }}</p>
    </div>
    @endif 
    @if(session()->has('exception'))
    <div class="alert alert-success alert-dismissible fade in shadowed alert-dismissible mt-1 mb-1">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <p><i class="fa fa-times"></i> {{ session('exception') }}</p>
    </div>
    @endif 
@stop

@section('info.language')
    {{ Form::select('lang', [ 'en' => 'English', 'ar' => 'العَرَبِيَّة', 'tr' => 'Türkçe', 'bn' => 'বাংলা', 'es' => 'Español', 'fr'=>'Français', 'pt'=>'Português', 'te'=>'తెలుగు', 'th' => 'ภาษาไทย', 'vi'=> 'Tiếng Việt' ],  \Session::get('locale') , ['id' => 'lang-select', 'class'=>'select2 form-control']) }}
@stop

@section('info.powered-by')
    Powered by <a href="https://lpulaguna.edu.ph" target="_blank">LPU-MIS</a> All rights reserved.
@stop

@section('info.version')
    <span class="label label-primary hidden-xs"></span>
@stop

@if (strtolower(env('APP_ENV'))=='demo')
    @section('info.buy-now')
        <div class="cm-flex"> <a class="btn btn-block btn-lg btn-success" href="#">Buy now</a></div>
    @stop
    
    @section('info.login-credentials')
    <div class="col-xs-12" style="margin-top:10px;z-index:999999">
        <div class="table-responsive mt-1">
            <table style="cursor:pointer;font-size:12px;z-index:999999" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Pass</th> 
                        <th>Role</th>
                    </tr>
                </thead>
            </table> 
        </div>
    </div>

    <script type="text/javascript">
    if(window.self !== window.top | window.location !== window.parent.location) { 
        var info = document.createElement("h4");
        info.style.background = "skyblue";
        info.style.color   = "red";
        info.style.padding = "10px";
        info.style.lineHeight = "25px";              
        document.querySelector('.text-center').appendChild(info);
    }
    </script>
    @endsection
@endif

