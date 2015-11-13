@extends('frontend.layouts.master')

@section('content')
	<div class="row">

        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading">Meet Readiness</div>
                <div class="panel-body">
                    <ol>
                        <li>Dropbox setup?</li>
                        <li>Paid?</li>
                        <li>Pre-load events? lynx.evt</li>
                        <li>Pre-load athletes? lynx.ppl</li>
                        <li>Pre-load schedule? lynx.sch</li>
                        <li>Print QR codes?</li>
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

            <div class="col-md-10 col-md-offset-1">
                Run meet
            </div><!-- col-md-10 -->
        </div>
	</div><!-- row -->
@endsection