@extends('frontend.layouts.master')

@section('content')
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			{!! Form::open(['route' => ['frontend.meet.actuallyEdit', $meet]]) !!}
            {!! Form::token() !!}
            Name: {!! Form::input('text', 'meetName', $meet->name) !!}<br>
            Sponsor: {!! Form::input('text', 'meetSponsor', $meet->sponsor) !!}<br>
            Location: {!! Form::input('text', 'meetLocation', $meet->location) !!}<br>
            Date: {!! Form::input('date', 'meetDate', $meet->meet_date->format('Y-m-d')) !!}<br>
            Time: {!! Form::input('time', 'meetTime', $meet->meet_date->format('H:i:s')) !!}<br>
			{!! Form::submit() !!}
			{!! Form::close() !!}
		</div><!-- col-md-10 -->
	</div><!-- row -->
@endsection