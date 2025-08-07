@extends('subsystem::layouts.app')

@section('pageTitle', st('homeLatestCourses'))

@section('content')
    {!! html()->form('POST', route('admin.setting.indexPage.homeLatestCourses.set'))->open() !!}
    @foreach($values as $index => $value)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center control-label">{{ st('Segment', ['number' => $index + 1]) }}</h5>
                <hr>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            {{ html()->label(st('course'), "homeLatestCourses[$index][articleID]")->class('control-label') }}
                            {{ html()->select("homeLatestCourses[$index]", $courses, $value)->class('form-control')->placeholder(st('Select an item')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="card">
        <div class="card-body">
            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->submit(st('submit'))->class('btn btn-primary') }}
                    {{ html()->a(route('admin.setting.indexPage'), st('Return'))->class('btn btn-secondary') }}
                </div>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}
@endsection
