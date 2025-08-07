@extends('subsystem::layouts.app')

@section('pageTitle', st('Add Course Section',['name'=> $course->name]))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.course.section.store',compact('course')))->open() !!}

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Title') . ' *', 'name')->class('control-label') }}
                        {{ html()->text('title', old('title'))->class('form-control')->placeholder(st('Title')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Sort Order') . ' *')->class('control-label') }}
                        {{ html()->number('sortOrder', old('sortOrder'))->class('form-control')->placeholder(st('Sort Order'))
                            ->attributes(['min' => 0])}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->submit(st('submit'))->class('btn btn-primary') }}
                    {{ html()->a(route('admin.course.section.list',['courseID' => $course->ID],compact('course')), st('Return'))->class('btn btn-secondary') }}
                </div>
            </div>
            {!! html()->form()->close() !!}
        </div>
    </div>
@endsection
