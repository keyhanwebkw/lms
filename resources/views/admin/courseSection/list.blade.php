@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('list Sections'))
@section('formFields')
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('courseID', $courseFilter,app('request')->input('courseID'))->placeholder(st('courseName'))->class('form-control') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
    @isset($course->name)
        {{ html()->a(route('admin.course.section.create',compact('course')), st('Add Course Section',['name'=> $course->name]))->class('btn btn-secondary ml-2') }}
    @endisset
    {{ html()->a(route('admin.course.list'), st('Return to list',['name' => st('menu.Courses')]))->class('btn btn-secondary') }}
    {{ html()->a(route('admin.course.section.episode.list'), st('list episode'))->class('btn btn-secondary') }}
@endsection

