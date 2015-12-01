@extends('frontend.layouts.master')

@section('content')
	<div class="row">

        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading">Meet Readiness</div>
                <div class="panel-body">
                    <ol>
                        @if ( $meet->isDropBoxReady() )
                            <li class="label-success">Dropbox Ready!</li>
                        @else
                            <li class="label-danger"><a href="{!! route('dropbox.start', ['id' => $meet->id]) !!}">DropBox Setup</a></li>
                        @endif
                        @if ($meet->isPaid())
                            <li class="label-success">Paid! Thank you.</li>
                        @else
                            <li class="label-danger">You still need to pay.</li>
                        @endif
                        @if ($meet->ppl === 1)
                            <li class="label-success">Athletes loaded</li>
                        @else
                            <li class="label-danger">Pre-load athletes? lynx.ppl</li>
                        @endif
                        @if ($meet->evt === 1)
                            <li class="label-success">Events loaded</li>
                        @else
                            <li class="label-danger">Pre-load events? lynx.evt (This takes a few seconds...)</li>
                        @endif
                        @if ($meet->sch === 1)
                            <li class="label-success">Schedule loaded</li>
                        @else
                            <li class="label-danger">Pre-load schedule? lynx.sch</li>
                        @endif
                        <li>Print QR codes? Unless you're going to tell people. Over, and over and over and over and over.</li>
                        <li>Run!</li>
                    </ol>
                </div><!--panel body-->

            </div><!-- panel -->

            <div class="panel panel-default">
                <div class="panel-heading">Preload Data</div>
                <div class="panel-body">
                    {!! Form::open(['route' => ['frontend.meet.preLoad', $meet->id], 'files' => true]) !!}
                    {!! Form::file('file') !!}
                    {!! Form::submit() !!}
                    {!! Form::close() !!}
                </div><!--panel body-->

            </div><!-- panel -->

            <div class="panel panel-default">
                <div class="panel-heading">QR Code</div>
                <div class="panel-body">
                    <img alt="QR code" src="{!! $meet->qr()->getDataUri() !!}" />
                </div><!--panel body-->

            </div><!-- panel -->

            <div class="panel panel-primary">
                <div class="panel-heading">Run Meet</div>
                <div class="panel-body">
                    Run.
                </div><!--panel body-->
            </div><!-- panel -->

            <div class="panel panel-default">
                <div class="panel-heading">Meet Statistics</div>
                <div class="panel-body">
                    <ul>
                        {{--<li>Number of athletes: {{ $meet->numberOfAthletes() }}</li>--}}
                        {{--<li>Number of teams: {{ $meet->numberOfTeams() }}</li>--}}
                        {{--<li>Number of events: {{ $meet->numberOfEvents() }}</li>--}}
                    </ul>
                </div><!--panel body-->

            </div><!-- panel -->

        </div>
	</div><!-- row -->
@endsection