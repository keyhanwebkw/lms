@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('Teachers list'))
@section('formFields')
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('name', app('request')->input('name'))->class('form-control')->placeholder(st('Name')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('family', app('request')->input('family'))->class('form-control')->placeholder(st('Family')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('mobile', app('request')->input('mobile'))->class('form-control')->placeholder(st('Mobile')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('email', app('request')->input('email'))->class('form-control')->placeholder(st('Email')) }}
                </div>
            </div>
            <div class="col-auto mb-3">
                <div class="input-group">
                    {{ html()->select('status',$statuses ,app('request')->input('status'))->class('form-control')->placeholder(st('Status')) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
    <a href="{{ route('admin.teacher.create') }}" class="btn btn-secondary ml-2">{{st('Add teacher')}}</a>
@endsection

