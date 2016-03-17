@extends('backend.layouts.master')

@section('content')
    @if (session('status'))
    <div class="col-md-offset-2 col-md-9 alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    <div class="row">
        <div class="col-xs-12">
            {!! Form::model($athlete, ['route' => ['admin.edit.saveAthlete', $athlete->id], 'class' => 'form-horizontal']) !!}
            <div class="form-group">
                {!! Form::label('id', 'ID', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('number', 'id', null, ['class' => 'form-control', 'readonly' => '']) !!}
                </div>
            </div>
            <div class="form-group required">
                {!! Form::label('firstname', 'First Name', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('text', 'firstname', null, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
            </div>
            <div class="form-group required">
                {!! Form::label('lastname', 'Last Name', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('text', 'lastname', null, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('gender', 'Gender ', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::select('gender', [0 => 'male', 1 => 'female'], null, ['class' => 'form-control', 'placeholder' => 'Select a gender ...']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('height', 'Height', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('number', 'height', null, ['class' => 'form-control']) !!}
                    <p class="help-block"><i class="fa fa-question-circle"></i> Inches</p>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('weight', 'Weight', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::input('number', 'weight', null, ['class' => 'form-control']) !!}
                    <p class="help-block"><i class="fa fa-question-circle"></i> Pounds</p>
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
