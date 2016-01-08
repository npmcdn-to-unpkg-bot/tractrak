@extends('frontend.layouts.master')

@section('title')
{{  app_name() }} | {{ $meet->name }}
@if ($meet->sponsor)
 | {{ $meet->sponsor }}
@endif
 | {{ $meet->stadium->name }}, {{ $meet->stadium->city }}, {{ $meet->stadium->state }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="center-block">
                    <h1>{{ $meet->name }}</h1>
                    @if ($meet->sponsor)
                        <h2>{{ $meet->sponsor }}</h2>
                    @endif
                    <h3>{{ $meet->stadium->name }}, {{ $meet->stadium->city }}, {{ $meet->stadium->state }}</h3>

                    <h3>{{ $meet->meet_date->format('l, F d, Y, g:ia') }}</h3>
                </div>
            </div>
        </div>
        @if ($meet->ready())
            <div class="row" id="vue">
                <div>
                    <events-event :meet="{{$meet->id}}"></events-event>
                </div>
            </div>

            <template id="event-template">
                <div class="col-sm-3" role="navigation">
                    <ul class="nav nav-pills nav-stacked">
                        <li role="presentation" v-for="event in events" :class="{ 'active': !$index }">
                            <a class="panel-title collapsed" role="tab" data-toggle="tab"
                               data-event="@{{ event.id }}" href="#event-@{{ event.id }}">
                                @{{ event.name }}
                                <span class="hide update-icon" id="updated-icon-@{{ event.id }}">
                                    <i class="fa fa-star"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-9">
                    <div class="tab-content">
                        <div role="tabpanel"  :class="{ 'tab-pane': true, 'active': !$index }" v-for="event in events" id="event-@{{ event.id }}">
                            <h2>@{{ event.name }}</h2>

                            <div v-for="round in event.round">
                                <h3>Round @{{ round.id }}</h3>

                                <div v-for="heat in round.heat">
                                    <h4>Heat @{{ heat.id }}</h4>
                                    <h4 v-if="heat.wind">Wind @{{{ heat.wind }}}</h4>
                                    <table id="event-table-@{{ event.id }}-@{{ round.id }}-@{{ heat.id }}"
                                           class="table table-responsive table-striped table-hover table-condensed">
                                        <thead>
                                        <tr>
                                            <th>Lane</th>
                                            <th>Name</th>
                                            <th>Team</th>
                                            <th>Position</th>
                                            <th>Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="lane in heat.lane | orderBy 'result'" :class="{
                                        'success': lane.place == 1,
                                        'danger':  lane.place == 'DQ',
                                        'info':    lane.place == 'SCR' || lane.place == 'DNS',
                                        'warning': lane.place == 'DNF'
                                         }">
                                            <td>@{{ lane.lane }}</td>
                                            <td>@{{ lane.name }}</td>
                                            <td>@{{ lane.teamAbbr }}</td>
                                            <td>@{{ lane.place }}</td>
                                            <td>@{{ lane.result }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        @else
            <div class="alert alert-info" role="alert">We're glad you're pumped for the meet. It's not quite ready yet.
                Please check back closer to the start time listed above.
            </div>
        @endif
    </div>
@endsection

@section('after-scripts-end')
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/1.0.13/vue.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue-resource/0.6.0/vue-resource.min.js"></script>
    <script src="//js.pusher.com/3.0/pusher.min.js"></script>
    <script src="/js/meet.js"></script>
    <script>
        $(document).ready(function () {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var $target = $(e.target);
                var event = $target.data();
                $('#updated-icon-' + event['event']).addClass('hide');
            });

        });

        var pusher = new Pusher("{{env("PUSHER_KEY")}}");
        var channel = pusher.subscribe('meet-{{ $meet->id }}');
        channel.bind('update', function (data) {
            console.log('Ooooh, an update!');
            console.log(data);
            var eventId = data['data']['event'];
            var roundId = data['data']['round'];
            vm.update(eventId, roundId);
            $('#updated-icon-' + eventId).addClass('hide');
        });
    </script>
@stop
