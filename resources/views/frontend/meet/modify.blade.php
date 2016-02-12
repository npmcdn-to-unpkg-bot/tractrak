@extends('frontend.layouts.master')

@section('content')
    @if (session('status'))
        <div class="col-md-offset-2 col-md-9 alert alert-success">
            {{ session('status') }}
        </div>
    @endif
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			{!! Form::open(['route' => ['frontend.meet.actuallyEdit', $meet], 'class' => 'form-horizontal']) !!}

            <div class="form-group required">
                {!! Form::label('meetName', 'Name', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('text', 'meetName', $meet->name, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('meetSponsor', 'Sponsor', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('text', 'meetSponsor', $meet->sponsor ? $meet->sponsor : null, ['class' => 'form-control', 'placeholder' => '(optional)']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('stadium', 'Stadium ', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::select('stadium', $stadiums, $meet->stadium ? $meet->stadium->id : null, ['class' => 'form-control', 'placeholder' => 'Select a stadium ...']) !!}
                    <p class="help-block"><i class="fa fa-question-circle"></i> Need to create one? Please {!! link_to_route( 'contact', 'contact us') !!}.</p>
                </div>
            </div>
            <div class="form-group required">
                {!! Form::label('date', 'Date', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('date', 'meetDate', $meet->meet_date->format('Y-m-d'), ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
            </div>
            <div class="form-group required">
                {!! Form::label('time', 'Time', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('time', 'meetTime', $meet->meet_date->format('H:i:s'), ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    {!! Form::submit('Modify', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
			{!! Form::close() !!}
		</div><!-- col-md-10 -->
	</div><!-- row -->
@endsection