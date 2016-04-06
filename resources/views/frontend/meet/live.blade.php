@extends('frontend.layouts.master')

@section('title')
{{ config('app.name') }} | {{ $meet->name }}
@if ($meet->sponsor)
 | {{ $meet->sponsor }}
@endif
 | {{ $meet->stadium->name }}, {{ $meet->stadium->city }}, {{ $meet->stadium->state->abbr }}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="text-center">
                <h1>{{ $meet->name }}</h1>
                @if ($meet->sponsor)
                    <h2>{{ $meet->sponsor }}</h2>
                @endif
                <h3>{!! link_to_route('frontend.stadium.view', $meet->stadium->name, $meet->stadium->id) !!}, {{ $meet->stadium->city }}, {{ $meet->stadium->state->name }}</h3>
                <h3>{{ $meet->meet_date->format('l, F d, Y, g:ia') }}</h3>
            </div>
        </div>
    </div>
    @if ($meet->ready())
        <div class="row" id="vue">
            <events-event :meet="{{$meet->id}}"></events-event>
        </div>

        <template id="event-template">
            <div class="col-sm-3" role="navigation">
                <button type="button" class="btn btn-default xs-toggle" data-toggle="collapse" data-target="#pills">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="fa fa-bars"></span> List of Events
                </button>
                <ul class="nav nav-pills nav-stacked xs-collapse" id="pills">
                    <li role="presentation" v-for="event in events" :class="{ 'active': !$index }">
                        <a class="collapsed" role="tab" data-toggle="tab"
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
                                <div class="table-responsive ">
                                    <table id="event-table-@{{ event.id }}-@{{ round.id }}-@{{ heat.id }}"
                                           class="table table-striped table-hover table-condensed">
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
                                        <tr v-for="lane in heat.lane" :class="{
                                        'success': lane.place == 1,
                                        'danger':  lane.place == 'DQ' || lane.place == 'FS',
                                        'info':    lane.place == 'SCR' || lane.place == 'DNS',
                                        'warning': lane.place == 'DNF'
                                         }">
                                            <td>@{{ lane.lane }}</td>
                                            <td>@{{ lane.name }}</td>
                                            <td v-if="lane.teamName" class="visible-lg">@{{ lane.teamName }}</td>
                                            <td v-if="lane.teamName"><abbr title="@{{ lane.teamName }}">@{{ lane.teamAbbr }}</abbr></td>
                                            <td v-else>@{{ lane.teamAbbr }}</td>
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
            </div>
        </template>
    @else
        <div class="alert alert-info" role="alert">We're glad you're pumped for the meet. It's not quite ready yet.
            Please check back closer to the start time listed above.
        </div>
    @endif
@endsection

@section('after-styles-end')
    <style>
        .xs-collapse {
            display: none;
        }
        @media (min-width: 769px) {
            .xs-toggle {
                display: none;
                visibility: hidden;
            }
            .xs-collapse {
                display: block;
            }
        }
    </style>
@stop
@section('after-scripts-end')
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/1.0.20/vue.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js"></script>
    <script src="//js.pusher.com/3.0/pusher.min.js"></script>
    <script src="/js/meet.js"></script>
    <script>
        $(document).ready(function () {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var $target = $(e.target);
                var event = $target.data();
                $('#updated-icon-' + event['event']).addClass('hide');
            });
            $(document).on('click', '.nav a', function() {
                $('button.xs-toggle').click();
            });
        });

        var pusher = new Pusher("{{env("PUSHER_KEY")}}");
        var channel = pusher.subscribe('meet-{{ $meet->id }}');
        channel.bind('update', function (data) {
            var eventId = data['data']['event'];
            var roundId = data['data']['round'];
            console.log('Update: ' + eventId);
            vm.update(eventId, roundId);
            $('#updated-icon-' + eventId).removeClass('hide');
        });
    </script>
@stop
