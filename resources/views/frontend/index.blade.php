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
        <div class="col-md-10 col-md-offset-1" id="current">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-clock-o"></i> Current meets</div>
                <div class="panel-body">
                    @if (count($currentMeets))
                        <ul class="list-unstyled">
                        @foreach ( $currentMeets as $meet )
                            <li style="font-size: x-large">{!! link_to_route('frontend.meet.live', $meet->name, $meet->id) !!}
                                @if ($meet->stadium)
                                        at {!! link_to_route('frontend.stadium.view', $meet->stadium->name, $meet->stadium->id) !!}
                                    @endif
                                on {{ $meet->meet_date->format('l, F d, Y, g:ia') }}</li>
                        @endforeach
                        </ul>
                    @else
                        <em>Nothing happening right now...</em>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-10 col-md-offset-1" id="upcoming">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-calendar"></i> Upcoming meets</div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                    @foreach ( $upcomingMeets as $meet )
                        <li style="font-size: x-large">{!! link_to_route('frontend.meet.live', $meet->name, $meet->id) !!}
                            @if ($meet->stadium)
                                at {!! link_to_route('frontend.stadium.view', $meet->stadium->name, $meet->stadium->id) !!}
                            @endif
                            on {{ $meet->meet_date->format('l, F d, Y, g:ia') }}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-10 col-md-offset-1" id="past">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-calendar"></i> Past meets</div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                    @foreach ( $recentMeets as $meet )
                        <li style="font-size: x-large">{!! link_to_route('frontend.meet.live', $meet->name, $meet->id) !!}
                            @if ($meet->stadium)
                                at {!! link_to_route('frontend.stadium.view', $meet->stadium->name, $meet->stadium->id) !!}
                            @endif
                            on {{ $meet->meet_date->format('l, F d, Y, g:ia') }}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
