@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('Sent assignments for') . ' ' . $assignment->title)
@section('formFields')
    {{ html()->hidden('assignmentID',app('request')->input('assignmentID')) }}
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('userID', $users ,app('request')->input('userID'))->class('form-control')->placeholder(st('User name')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('status',$statuses ,app('request')->input('status'))->class('form-control')->placeholder(st('status')) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
    {{ html()->a(url()->previous(), st('Back'))->class('btn btn-secondary ml-2') }}
@endsection
