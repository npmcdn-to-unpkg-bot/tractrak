@extends('backend.layouts.master')

@section('content')
    @if (session('status'))
    <div class="col-md-offset-2 col-md-9 alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    <div class="row">
        <div class="col-xs-12">
            {!! Form::model($team, ['route' => ['admin.edit.saveTeam', $team->id], 'class' => 'form-horizontal']) !!}
            <div class="form-group">
                {!! Form::label('id', 'ID', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('number', 'id', null, ['class' => 'form-control', 'readonly' => '']) !!}
                </div>
            </div>
            <div class="form-group required">
                {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('text', 'name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
            </div>
            <div class="form-group required">
                {!! Form::label('abbr', 'Abbreviation', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('text', 'abbr', null, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('stateid', 'State ', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {{-- TODO Update list of States if country changes --}}
                    {!! Form::select('stateid', \App\Models\State::where('countryid', $team->state->countryid)->get()->lists('name', 'id'), null, ['class' => 'form-control', 'placeholder' => 'Select a state ...']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('countryid', 'Country ', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::select('countryid', \App\Models\Country::all()->lists('name', 'id'), null, ['class' => 'form-control', 'placeholder' => 'Select a country ...']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
