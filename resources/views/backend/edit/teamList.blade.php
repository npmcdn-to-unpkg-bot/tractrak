@extends('backend.layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Team Selection</div>
                <div class="panel-body">
                    <ul>
                        @foreach ($teams as $team)
                            <li>{{ $team->name }} {!! link_to_route('admin.edit.team', 'Edit', $team->id, ['class' => 'btn btn-primary btn-sm']) !!}</li>
                        @endforeach
                    </ul>
                </div><!--panel body-->
            </div>
        </div>
    </div>
@endsection