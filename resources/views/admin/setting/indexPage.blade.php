@extends('subsystem::layouts.app')

@section('pageTitle', st('Index page segment settings'))

@section('content')
    <div class="card">
        <div class="card-body">
            @foreach(collect($settings)->chunk(2) as $chunk)
                <div class="row mb-3">
                    @foreach($chunk as $setting)
                        <div class="col-md-6">
                            {{ html()->a(route($setting['routeName']),$setting['name'])->class('btn btn-gradient w-100') }}
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection
