@extends('layouts.app')

@section('title','Home')

@section('styles')
    <style>
        .margins
        {
            margin-top: 15%;
            margin-bottom: 16%;
        }
        .width-height
        {
            width: 60%;
            height: 50%;
        }
    </style>
@endsection

@section('content')

    <div class="col col-md-12 margins text-center">
        <div class="col-md-offset-3">
            <img src="{{asset('gentelella/production/images/Logo_Romina.png')}}" alt="" class="img-responsive width-height">
        </div>
    </div>
@endsection
