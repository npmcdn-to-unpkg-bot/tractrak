@extends('frontend.layouts.master')

@section('content')
    <div class="row">

        <div class="col-md-10 col-md-offset-1">
            {!! Form::open(['route' => ['frontend.meet.actuallyCreate', $user->id]]) !!}
            {!! Form::token() !!}
            Name: {!! Form::input('text', 'meetName') !!}<br>
            Sponsor: {!! Form::input('text', 'meetSponsor') !!}<br>
            Stadium: {!! Form::select('stadium', $stadiums) !!}<br>
            Date: {!! Form::input('date', 'meetDate', date('Y-m-d')) !!}<br>
            Time: {!! Form::input('time', 'meetTime', '08:00:00') !!}<br>
            {!! Form::submit() !!}
            {!! Form::close() !!}
        </div><!-- col-md-10 -->
    </div><!-- row -->
@endsection