@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('list episode'))
@section('formFields')
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('courseSectionID', $courseSectionFilter,app('request')->input('courseSectionID'))->placeholder(st('Sections'))->class('form-control') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
    @isset($courseSection->title)
        {{ html()->a(route('admin.course.section.episode.create', compact('courseSection')), st('Add Section Episode',['name'=> $courseSection->title]))->class('btn btn-secondary ml-2') }}
    @endisset
    {{ html()->a(route('admin.course.section.list',['courseID' => $courseSection->courseID ?? null]), st('Return to list',['name' => st('Sections')]))->class('btn btn-secondary') }}
    {{ html()->a(route('admin.course.list'), st('Return to list',['name' => st('menu.Courses')]))->class('btn btn-secondary') }}
@endsection

