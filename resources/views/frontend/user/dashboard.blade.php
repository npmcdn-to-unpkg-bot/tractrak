@extends('frontend.layouts.master')

@section('content')
    <div class="row">

        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('navs.dashboard') }}</div>

                <div class="panel-body">
                    <table class="table table-striped table-hover table-bordered dashboard-table">
                        <tr>
                            <th>{{ trans('validation.attributes.name') }}</th>
                            <td>{!! $user->name !!}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('validation.attributes.email') }}</th>
                            <td>{!! $user->email !!}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('validation.attributes.created_at') }}</th>
                            <td>{!! $user->created_at !!} ({!! $user->created_at->diffForHumans() !!})</td>
                        </tr>
                        <tr>
                            <th>{{ trans('validation.attributes.last_updated') }}</th>
                            <td>{!! $user->updated_at !!} ({!! $user->updated_at->diffForHumans() !!})</td>
                        </tr>
                        <tr>
                            <th>{{ trans('validation.attributes.actions') }}</th>
                            <td>
                                <a href="{!!route('frontend.profile.edit')!!}"
                                   class="btn btn-primary btn-sm">{{ trans('labels.edit_information') }}</a>
                                <a href="{!!url('auth/password/change')!!}"
                                   class="btn btn-warning btn-sm">{{ trans('navs.change_password') }}</a>
                            </td>
                        </tr>
                    </table>
                </div><!--panel body-->

            </div><!-- panel -->
            <div class="panel panel-default">
                <div class="panel-heading">Meet Management</div>

                <div class="panel-body">
                    {!! link_to_route('frontend.meet.create', 'Create Meet', null, ['class' => 'btn btn-primary btn-sm']) !!}
                </div><!--panel body-->

            </div><!-- panel -->
            @if ($user->meets)
                <div class="panel panel-default">
                    <div class="panel-heading">Your Meets</div>

                    <div class="panel-body">
                        @foreach($user->meets as $meet)
                            <li>{{ $meet->name }},
                                @if ($meet['location'])
                                    at {{ $meet['location']->name }}
                                @endif
                                on {{ $meet->meet_date }}
                                {!! link_to_route('frontend.meet.modify', 'Modify Meet', ['id' => $meet->id], ['class' => 'btn btn-primary btn-sm']) !!}
                                {!! link_to_route('frontend.meet.run', 'Run Meet', ['id' => $meet->id], ['class' => 'btn btn-success btn-sm']) !!}
                                {!! link_to_route('frontend.meet.live', 'View Meet', ['id' => $meet->id], ['class' => 'btn btn-success btn-sm']) !!}
                            </li>
                        @endforeach
                    </div><!--panel body-->
                </div><!-- panel -->
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">Meet Credits</div>

                <div class="panel-body">
                    You have X credits.
                </div><!--panel body-->

            </div><!-- panel -->
        </div><!-- col-md-10 -->

    </div><!-- row -->
@endsection