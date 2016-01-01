@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <div class="center-block">
                    <h1>{{ $meet->name }}</h1>
                    <h3>{{ $meet->location }}</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2" role="navigation">
                <ul class="nav nav-pills nav-stacked">
                    @foreach ( $meet->races()->orderBy('schedule')->groupBy('event')->get() as $race )
                        <li role="presentation"@if ($race->schedule === 1) class="active"@endif>
                            <a class="panel-title collapsed" role="tab" data-toggle="tab"
                               data-event="{{ $race->event }}" href="#event-{{ $race->event }}">
                                {{ $race->type()->first()->name }}
                                <span class="hide update-icon" id="updated-icon-{{$race->event}}">
                                    <i class="fa fa-star"></i>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-md-10">
                <div class="alert alert-success alert-dismissable hide">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>Alert!</h4>
                    <strong>Warning!</strong>
                    Best check yo self, you're not looking too good. <a href="#" class="alert-link">alert link</a>
                </div>

                <div class="tab-content">
                    @foreach ( $meet->races()->orderBy('schedule')->groupBy('event')->get() as $race )
                        <div role="tabpanel" class="tab-pane @if ($race->schedule === 1) active @endif" id="event-{{ $race->event }}">
                            <h2>{{ $race->type()->first()->name }}</h2>
                            <table id="event-table-{{ $race->event }}" class="table table-responsive display"
                                   data-ajax="/api/meet-event/{{ $meet->id }}/{{ $race->event }}"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Round</th>
                                    <th>Heat</th>
                                    <th>Lane</th>
                                    <th>Name</th>
                                    <th>Team</th>
                                    <th>Position</th>
                                    <th>Time</th>
                                    <th>Wind</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Round</th>
                                    <th>Heat</th>
                                    <th>Lane</th>
                                    <th>Name</th>
                                    <th>Team</th>
                                    <th>Position</th>
                                    <th>Time</th>
                                    <th>Wind</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after-scripts-end')
    <script src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
    <script src="//js.pusher.com/3.0/pusher.min.js"></script>
    <script>
        $(document).ready(function () {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var $target = $(e.target);
                var event = $target.data();
                console.log(event['event']);
                $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
                $('#updated-icon-' + event['event']).addClass('hide');
            });

            $('table.table').DataTable();
        });

        var pusher = new Pusher("{{env("PUSHER_KEY")}}");
        var channel = pusher.subscribe('meet-{{ $meet->id }}');
        channel.bind('update', function(data) {
            var eventId = data['data']['event'];
            $('#event-table-' + eventId).DataTable().ajax.reload();
            $('#updated-icon-' + eventId).removeClass('hide');
        });
    </script>
@stop

@section('after-styles-end')
    <link href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
@stop
