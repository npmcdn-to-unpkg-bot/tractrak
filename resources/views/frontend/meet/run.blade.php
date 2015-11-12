@extends('frontend.layouts.master')

@section('content')
	<div class="row">

        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading">Meet Readiness</div>

                <div class="panel-body">
                    Dropbox setup?
                    Paid?
                    Pre-load events? lynx.evt
                    Pre-load athletes? lynx.ppl
                    Pre-load schedule? lynx.sch
                    Print QR codes?
                </div><!--panel body-->

            </div><!-- panel -->

            <div class="panel panel-default">
                <div class="panel-heading">Preload Data</div>
                {!! Form::open(['route' => ['frontend.meet.preLoad', $meet->id], 'files' => true]) !!}
                {!! Form::file('file') !!}
                {!! Form::submit() !!}
                {!! Form::close() !!}
                <div class="panel-body">

                </div><!--panel body-->

            </div><!-- panel -->
            <div class="col-md-10 col-md-offset-1">
                Run meet
            </div><!-- col-md-10 -->
        </div>
	</div><!-- row -->
@endsection