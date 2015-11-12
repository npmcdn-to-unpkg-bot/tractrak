@extends('frontend.layouts.master')

@section('content')
	<div class="row">

		<div class="col-md-10 col-md-offset-1">
			{!! Form::open(['route' => ['frontend.meet.actuallyCreate', $user->id]]) !!}
            {!! Form::token() !!}
            Name: {!! Form::input('text', 'meetName') !!}<br>
            Sponsor: {!! Form::input('text', 'meetSponsor') !!}<br>
            Location: {!! Form::input('text', 'meetLocation') !!}<br>
            Date: {!! Form::input('date', 'meetDate', \Carbon\Carbon::now()) !!}<br>
            Time: {!! Form::input('time', 'meetTime', '08:00 AM') !!}<br>
			{!! Form::submit() !!}
			{!! Form::close() !!}
		</div><!-- col-md-10 -->
	</div><!-- row -->
@endsection