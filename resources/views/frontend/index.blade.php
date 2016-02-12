@extends('frontend.layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="jumbotron" style="background-color:#565656; color:white">
                <h1>Welcome to <span class="tt-green" style="color:#39ff14">TracTrak</span></h1>

                <p>Live results from track (&amp; hopefully field) meets. Tell your coach you want to see results faster
                    using TracTrak.</p>
            </div>
        </div>
        {{--<div class="col-md-10 col-md-offset-1">--}}
            {{--<div class="panel panel-default">--}}
                {{--<div class="panel-heading"><i class="fa fa-search"></i> Find a meet</div>--}}
                {{--<div class="panel-body">--}}
                    {{--<input type="text" id="findEvent" autofocus style="width:100%" title="Find a Meet">--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-clock-o"></i> Current meets</div>
                <div class="panel-body">
                    <em>Nothing happening right now...</em>
                </div>
            </div>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-calendar"></i> Upcoming meets</div>
                <div class="panel-body">
                    @foreach ( $upcomingMeets as $meet )
                        <li><a href="{{ $meet['link'] }}">{{ $meet['name'] }}</a>
                            @if ($meet['location'])
                                at {{ $meet['location']->name }}
                            @endif
                            on {{ $meet['datetime'] }}</li>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-calendar"></i> Recent meets</div>
                <div class="panel-body">
                    @foreach ( $recentMeets as $meet )
                        <li><a href="{{ $meet['link'] }}">{{ $meet['name'] }}</a>
                            @if ($meet['location'])
                                at {{ $meet['location']->name }}
                            @endif
                            at {{ $meet['datetime'] }}</li>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
