@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('Course category list'))
@section('formFields')
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('title', app('request')->input('title'))->class('form-control')->placeholder(st('Title')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('slug', app('request')->input('slug'))->class('form-control')->placeholder(st('slug')) }}
                </div>
            </div>
            <div class="col-auto mb-3">
                <div class="input-group">
                    {{ html()->select('status',$courseCategoryStatuses ,app('request')->input('status'))->class('form-control')->placeholder(st('Status')) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
    <a href="{{route('admin.courseCategory.create')}}" class="btn btn-secondary ml-2">{{st('Add Course Category')}}</a>
@endsection

