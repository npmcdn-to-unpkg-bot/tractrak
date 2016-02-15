@extends('frontend.layouts.master')

@section('title')
    Requirements | {{ config('app.name') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="jumbotron" style="background-color:#565656; color:white">
                <h1><span class="tt-green" style="color:#39ff14">TracTrak</span> Requirements</h1>
            </div>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-list-ul"></i> Requirements to use <span class="tt-green" style="color:#39ff14">TracTrak</span> at a meet:</div>
                <div class="panel-body">
                    <ul>
                        <li>Internet connection (at race location)</li>
                        <li><a href="https://www.dropbox.com">DropBox</a> installed on a computer that has access to the .LIF files.</li>
                        <li>DropBox access (some IT departments block this, contact your IT department for help, some will unblock for coaches, teachers, administrators for a specific need.)</li>
                        <li><a href="http://www.finishlynx.com/">FinishLynx</a> &mdash; or a timing system that can output <strong>.LIF</strong> files. We plan to support more file formats and systems in the future.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
