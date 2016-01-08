@extends('frontend.layouts.master')

@section('title')
    Contact Information | {{  app_name() }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="jumbotron" style="background-color:#565656; color:white">
                <h1>Contact <span class="tt-green" style="color:#39ff14">TracTrak</span></h1>
            </div>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-info-circle"></i> Contact</div>
                <div class="panel-body">
                    <p>Email: <a href="mailto:michael@tractrak.com">michael@tractrak.com</a></p>
                    <p>Voice / Text: 303-351-1533 (leave a message)</p>
                    <p><a href="https://twitter.com/TracTraker">Twitter</a></p>
                    <p><a href="https://www.facebook.com/tractrak">Facebook</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
