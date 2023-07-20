@extends('layouts.backend')
@section('title', trans('app.dashboard'))

@section('content')
<div class="card">
    <div class="card-header bg-danger text-white">
        <h3 class="card-title">{{ trans('app.dashboard') }}</h3>
    </div>
    <div class="card-body">
        <!-- panel content goes here -->
    
        <div class="row">
    <div class="col-sm-12 shadowed">
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 mb-1">
            <div class="card bg-danger text-white rounded">
                <div class="card-body d-flex align-items-center">
                    <i class="fa fa-cubes fa-2x mr-2"></i>
                    <div>
                        <h5 class="card-title">{{ isset($infobox->department) ? $infobox->department : 0 }}</h5>
                        <p class="card-text">{{ trans('app.department') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 mb-1">
            <div class="card bg-danger text-white rounded">
                <div class="card-body d-flex align-items-center">
                    <i class="fa fa-star-o fa-2x mr-2"></i>
                    <div>
                        <h5 class="card-title">{{ isset($infobox->counter) ? $infobox->counter : 0 }}</h5>
                        <p class="card-text">{{ trans('app.counter') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 mb-1">
            <div class="card bg-danger text-white rounded">
                <div class="card-body d-flex align-items-center">
                    <i class="fa fa-users fa-2x mr-2"></i>
                    <div>
                        <h5 class="card-title">{{ isset($infobox->user) ? $infobox->user : 0 }}</h5>
                        <p class="card-text">{{ trans('app.users') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 mb-1">
            <div class="card bg-danger text-white rounded">
                <div class="card-body d-flex align-items-center">
                    <i class="fa fa-ticket fa-2x mr-2"></i>
                    <div>
                        <h5 class="card-title">{{ isset($infobox->token->total) ? $infobox->token->total : 0 }}</h5>
                        <p class="card-text">{{ trans('app.token') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 mb-1">
            <div class="card bg-danger text-white rounded">
                <div class="card-body d-flex align-items-center">
                    <i class="fa fa-ticket fa-2x mr-2"></i>
                    <div>
                        <h5 class="card-title">{{ isset($infobox->token->pending) ? $infobox->token->pending : 0 }}</h5>
                        <p class="card-text">{{ trans('app.pending') }} {{ trans('app.token') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 mb-1">
            <div class="card bg-danger text-white rounded">
                <div class="card-body d-flex align-items-center">
                <i class="fa fa-ticket fa-2x mr-2"></i>
                    <div>
                        <h5 class="card-title">{{ isset($infobox->token->complete) ? $infobox->token->complete : 0 }}</h5>
                        <p class="card-text">{{ trans('app.complete') }} {{ trans('app.token') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                {{ trans('app.this_month') }}
            </div>
            <div class="card-body">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                {{ trans('app.this_year') }}
            </div>
            <div class="card-body">
                <canvas id="singelBarChart" style="height: 200px;"></canvas>
            </div>
        </div>
    </div>


            <div class="col-sm-6">
                <div class="panel panel-primary shadowed">
                    <div class="panel-heading">{{ trans('app.today_user_performance') }}</div>
                    <div class="panel-body">
                    @if (!empty($performance))   
                    @foreach($performance as $user)
                    <?php
                    $pending = number_format(((($user->pending?$user->pending:0)/($user->total?$user->total:1))*100),1);
                    $complete = number_format(((($user->complete?$user->complete:0)/($user->total?$user->total:1))*100),1);
                    $stop = number_format(((($user->stop?$user->stop:0)/($user->total?$user->total:1))*100),1);
                    ?>
                        <div class="row">
                            <label class="col-sm-3 col-xs-12">{{ $user->username }}</label>
                            <div class="col-sm-9 col-xs-12"> 
                                <div class="progress"> 
                                  <div class="progress-bar progress-bar-danger" style="width: {{ $stop }}%">
                                    <span>{{ $stop }}% {{trans("app.stop")}} (Total {{ $user->stop }}) </span>
                                  </div>
                                  <div class="progress-bar progress-bar-success" style="width: {{ $complete }}%">
                                    <span>{{ $complete }}% {{trans("app.complete")}} (Total {{ $user->complete }}) </span>
                                  </div>
                                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{{ $pending }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $pending }}%">
                                    <span>{{ $pending }}% {{trans("app.pending")}} (Total {{ $user->pending }}) </span>
                                  </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @endif                 
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="panel panel-primary shadowed mb-3">
                    <div class="panel-heading">{{ trans('app.from_the_begining') }}</div>
                    <div class="panel-body"><canvas id="pieChart" style="height:200px"></canvas></div>
                </div>
            </div> 
        </div> 
    </div>
</div> 
@endsection
 
@push("scripts")
<script src="{{ asset('public/assets/js/Chart.min.js') }}"></script>
<script type="text/javascript"> 
$(window).on('load', function(){

    //line chart
    var ctx = document.getElementById("lineChart");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                <?php 
                if (!empty($month)) {
                    for ($i=0; $i < sizeof($month) ; $i++) { 
                       echo (!empty($month[$i])?$month[$i]->date:0).", ";
                    }
                }
                ?>
            ],
            datasets: [
                {
                    label: "Total",
                    borderColor: "rgba(24, 97, 142, .9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(24, 97, 142, .09)",
                    pointHighlightStroke: "rgba(168, 50, 50, 1)",
                    data: [
                        <?php 
                        if (!empty($month)) {
                            for ($i=0; $i < sizeof($month) ; $i++) { 
                               echo (!empty($month[$i])?$month[$i]->total:0).", ";
                            }
                        }
                        ?>
                    ]
                },
                {
                    label: "Success",
                    borderColor: "rgba(225, 48, 91, 0.9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(225, 48, 91, 0.09)",
                    pointHighlightStroke: "rgba(26,179,148,1)",
                    data: [
                        <?php 
                        if (!empty($month)) {
                            for ($i=0; $i < sizeof($month) ; $i++) { 
                               echo (!empty($month[$i])?$month[$i]->success:0).", ";
                            }
                        }
                        ?>
                    ]
                },
                {
                    label: "Pending",
                    borderColor: "rgba(0,0,0, 0.9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(0,0,0, 0.09)",
                    pointHighlightStroke: "rgba(26,179,148,1)",
                    data: [
                        <?php 
                        if (!empty($month)) {
                            for ($i=0; $i < sizeof($month) ; $i++) { 
                               echo (!empty($month[$i])?$month[$i]->pending:0).", ";
                            }
                        }
                        ?>
                    ]
                }
            ]
        },
        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
                intersect: false
            },
            hover: {
                mode: 'nearest',
                intersect: true
            } 
        }
    });


    // single bar chart
    var ctx = document.getElementById("singelBarChart");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                <?php 
                if (!empty($year)) {
                    for ($i=0; $i < sizeof($year) ; $i++) { 
                       echo "'".(!empty($year[$i])?$year[$i]->month:0)."', ";
                    }
                }
                ?>
            ],
            datasets: [
                {
                    label: "Total",
                    borderColor: "rgba(24, 97, 142, 0.9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(24, 97, 142, 0.5)",
                    data: [
                        <?php 
                        if (!empty($year)) {
                            for ($i=0; $i < sizeof($year) ; $i++) { 
                               echo (!empty($year[$i])?$year[$i]->total:0).", ";
                            }
                        }
                        ?>
                    ]
                },
                {
                    label: "Success",
                    borderColor: "rgba(225, 48, 91, 0.9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(225, 48, 91, 0.5)",
                    data: [
                        <?php 
                        if (!empty($year)) {
                            for ($i=0; $i < sizeof($year) ; $i++) { 
                               echo (!empty($year[$i])?$year[$i]->success:0).", ";
                            }
                        }
                        ?>
                    ]
                },
                {
                    label: "Pending",
                    borderColor: "rgba(0,0,0, 0.9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(0,0,0, 0.5)",
                    data: [
                        <?php 
                        if (!empty($year)) {
                            for ($i=0; $i < sizeof($year) ; $i++) { 
                               echo (!empty($year[$i])?$year[$i]->pending:0).", ";
                            }
                        }
                        ?>
                    ]
                }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
            }             
        }
    });



    // pie chart
    var ctx = document.getElementById("pieChart"); 
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            datasets: [{
                    data: [
                        <?php 
                        if (!empty($begin) && is_array($begin)) { 
                               echo (!empty($begin[0])?$begin[0]->total:0).", ";
                               echo (!empty($begin[0])?$begin[0]->success:0).", ";
                               echo (!empty($begin[0])?$begin[0]->pending:0); 
                        }
                        ?>
                    ],
                    backgroundColor: [
                        "rgba(168, 50, 50, 1)",
                        "rgba(225, 48, 91,0.7)",
                        "rgba(0,0,0,0.5)",
                        "rgba(0,0,0,0.07)"
                    ],
                    hoverBackgroundColor: [
                        "rgba(24, 97, 142,0.9)",
                        "rgba(207, 81, 81, 1)",
                        "rgba(0,0,0,0.5)",
                        "rgba(0,0,0,0.07)"
                    ]

                }],
            labels: [
                "Total",
                "Success",
                "Pending"
            ]
        },
        options: {
            responsive: true
        }
    });
 
});
</script>
@endpush