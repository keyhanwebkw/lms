@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('list episode'))
@section('formFields')
    {{ html()->hidden('courseSectionID', app('request')->input('courseSectionID')) }}
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('title', app('request')->input('title'))->class('form-control')->placeholder(st('Title')) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
    {{ html()->a(route('admin.course.section.episode.list', ['courseSectionID' => request('courseSectionID')]), st('Back'))->class('btn btn-secondary') }}
@endsection

