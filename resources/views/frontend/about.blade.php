@extends('frontend.layouts.master')

@section('title')
    About | {{  app_name() }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="jumbotron" style="background-color:#565656; color:white">
                <h1>About <span class="tt-green" style="color:#39ff14">TracTrak</span></h1>
            </div>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-info-circle"></i> About This Site</div>
                <div class="panel-body">
                    <p>Launched January 1, 2016 for the 2016 Spring Track Season.</p>
                    <p>The purpose of this site is to disseminate the results (times) to coaches, fans, parents, athlets and everyone faster. Typically, results must be completely gathered by the meet management software, then printed, then posted, then athletes or coaches must search through printed pages for times. We want to get results to interested parties as soon as they are available. Scoreboard and other display systems are expensive (in the thousands of dollars!) and only show the information for a short term.</p>
                    <p>Therefore, as soon as the times are saved, we grab that file and process the information and push the information out to anyone who has the the meet page loaded. <em>You don't even need to refresh your browser to get the results!</em></p>
                    <p>While the exact availability of the results is impossible to predict, since it depends upon the timer operator, once the file is saved, it takes approximately 1 second before you have the results in your browser on your phone or tablet or computer.</p>
                    <p>HEY! WHAT ABOUT FIELD EVENTS?!?! When I developed the site, I only had access to FinishLynx .LIF files. If you have FieldLynx .LIF files to share so I can develop this, I would love to do so. Please {!! link_to('/contact', 'contact') !!} me.</p>
                    <p>HEY! WHAT ABOUT NON LYNX!? I plan to expand to cover all systems. If you have access to the datafiles that a timing system (or field system) outputs, please {!! link_to('/contact', 'contact') !!} me.</p>
                </div>
            </div>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-users"></i> About Us</div>
                <div class="panel-body">
                    <ul>
                        <li><strong>Michael Hoppes</strong> &mdash; Owner / Creator &mdash; I started timing high school track meets in 2009 using FinishLynx. Since I sit in the timing tent, I often get asked "Hey, can I get the time for athlete X / lane #?" Instead of helping one coach or athlete, I figured everyone would want to have the data sooner. I also created <a href="https://rockyprep.com">RockyPrep</a></li>
                        <li><strong>You?</strong> &mdash; ? &mdash; Want to help? Contact me. Mostly spreading the word and retro-data-validation is needed.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
