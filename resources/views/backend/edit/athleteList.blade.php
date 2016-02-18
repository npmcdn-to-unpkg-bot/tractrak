@extends('backend.layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Athlete Selection</div>
                <div class="panel-body">
                    <div id="az-nav" class="listNav"></div>
                    <ul id="az" class="az">
                        @foreach ($athletes as $athlete)
                            <li>{{ $athlete->lastname }}, {{ $athlete->firstname }} {!! link_to_route('admin.edit.athlete', 'Edit', $athlete->id, ['class' => 'btn btn-primary btn-sm']) !!}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after-scripts-end')
    <script src="/js/jquery-listnav.min.js"></script>
    <script>
        $('#az').listnav({
            initLetter: '',							// filter the list to a specific letter on init ('a'-'z', '-' [numbers 0-9], '_' [other])
            includeAll: true,					// Include the ALL button
            includeOther: false,			// Include a '...' option to filter non-english characters by
            includeNums: false,				// Include a '0-9' option to filter by
            flagDisabled: true,				// Add a class of 'ln-disabled' to nav items with no content to show
            removeDisabled: false,		// Remove those 'ln-disabled' nav items (flagDisabled must be set to true for this to function)
            showCounts: true,				// Show the number of list items that match that letter above the mouse
            dontCount: '',						// A comma separated list of selectors you want to exclude from the count function (numbers on top of navigation)
            cookieName: null,				// Set this to a string to remember the last clicked navigation item requires jQuery Cookie Plugin ('myCookieName')
            onClick: null,							// Set a function that fires when you click a nav item. see Demo 5
            prefixes: [],								// Set an array of prefixes that should be counted for the prefix and the first word after the prefix ex: ['the', 'a', 'my']
            filterSelector: '',					// Set the filter to a CSS selector rather than the first text letter for each item
            noMatchText: 'Nothing matched your filter, please click another letter.'
        });
    </script>
@endsection

@section('after-styles-end')
    {!! HTML::style('css/listNav.min.css') !!}
@stop