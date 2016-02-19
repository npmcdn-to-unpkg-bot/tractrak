@extends('frontend.layouts.master')

@section('title')
{{ $stadium->name }}, {{ $stadium->city }}, {{ $stadium->state->abbr }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 text-center">
                @if($stadium->lat && $stadium->lng)
                    <div style="float:right">
                        @if($stadium->googlename)
                            <a href="https://www.google.com/maps/search/{{ str_replace(' ', '+', $stadium->googlename) }},+{{ str_replace(' ', '+', $stadium->address) }},+{{  $stadium->city }},+{{ $stadium->state->name }}@if($stadium->zip),+{{ $stadium->zip }}@endif">
                        @endif
                        <img src="https://maps.googleapis.com/maps/api/staticmap?center={!! $stadium->lat !!},{!! $stadium->lng !!}&zoom=15&size=100x100&scale=2&maptype=satellite&key={{ env('GOOGLE_MAPS_API_KEY') }}" height="200px" width="200px">
                        @if($stadium->googlename)
                            </a>
                        @endif
                    </div>
                @endif
                <div class="center-block">
                    <h1>{{ $stadium->name }}</h1>
                    <h3>@if($stadium->address){{ $stadium->address }}<br>@endif{{ $stadium->city }}, {{ $stadium->state->name }}@if($stadium->zip) {{ $stadium->zip }}@endif</h3>
                </div>
            </div>
        </div>

        <div class="grid" data-masonry='{ "itemSelector": ".grid-item", "columnWidth": 320 }'>
            @foreach($records as $record)
               <div class="grid-item">
                   <div class="panel panel-primary">
                       <div class="panel-heading"><i class="fa fa-certificate"></i> {{ $races[$record->race_id]->type->name }}</div>
                       <div class="panel-body">
                           {{ $races[$record->race_id]->firstPlace()->getName() }}
                           <span class="badge">{{ $record->result }}</span>
                       </div>
                       <div class="panel-footer">at {!! link_to_route('frontend.meet.live', $races[$record->race_id]->meet->name, $races[$record->race_id]->meet->id) !!} on {{ $races[$record->race_id]->meet->meet_date->format('F d, Y') }}</div>
                   </div>
               </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-xs-12">
                Meets held at this stadium:
                <ul>
                    @foreach($meets as $meet)
                        <li>{!! link_to_route('frontend.meet.live', $meet->name, $meet->id) !!} on {{ $meet->meet_date->format('l, F d, Y, g:ia') }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('after-scripts-end')
    <script src="//npmcdn.com/masonry-layout@4.0/dist/masonry.pkgd.min.js"></script>
@endsection