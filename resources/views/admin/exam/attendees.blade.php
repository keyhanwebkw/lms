@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('Attendees list in exam') . " $examTitle")
@section('formFields')
    <div class="col-lg-12 mb-3">
        {{ html()->hidden('examID',(app('request')->input('examID'))) }}
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('userID', $users, app('request')->input('userID'))->class('form-control')->placeholder(st('User name')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('examStatus',$examStatuses ,app('request')->input('examStatus'))->class('form-control')->placeholder(st('Exam status')) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
    {{ html()->a(route('admin.exam.list',['ID' => request('examID')]), st('Back'))->class('btn btn-secondary ml-2') }}
@endsection
