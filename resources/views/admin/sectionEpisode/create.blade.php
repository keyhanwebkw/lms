@extends('subsystem::layouts.app')

@section('pageTitle', st('Add Section Episode',['name'=> $courseSection->title]))

@section('content')
    {!! html()->form('POST', route('admin.course.section.episode.store',compact('courseSection')))->open() !!}
    {{html()->hidden('type')->id('type')}}
    {{html()->hidden('courseSectionID', $courseSection->ID)}}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Sort Order') . ' *')->class('control-label') }}
                        {{ html()->number('sortOrder', old('sortOrder'))->class('form-control')->placeholder(st('Sort Order'))->attributes(['min' => 1]) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3 mt-4">
                    <div class="form-group">
                        {{ html()->label(st('is Mandatory'), 'isMandatory')->class('control-label') }}
                        {{ html()->checkbox('isMandatory', old('isMandatory'))->class('form-check-input') }}
                    </div>
                </div>
            </div>
            <div class="row">
                {{ html()->label(st('Episode type'))->class('control-label') }}
                <div class="col-md-4 mb-3 text-end">
                    <div class="form-group">
                        {{ html()->submit(st('content'))->class('btn btn-flat-info')->attributes(['onclick' => "document.getElementById('type').value='content'"]) }}
                    </div>
                </div>
                <div class="col-md-4 mb-3 text-center">
                    <div class="form-group">
                        {{ html()->submit(st('Assignment'))->class('btn btn-flat-warning')->attributes(['onclick' => "document.getElementById('type').value='assignment'"]) }}
                    </div>
                </div>
                <div class="col-md-4 mb-3 text-start">
                    <div class="form-group">
                        {{ html()->submit(st('Exam'))->class('btn btn-flat-indigo')->attributes(['onclick' => "document.getElementById('type').value='exam'"]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-start">
                    <div class="form-group">
                        {{ html()->a(url()->previous(), st('Back'))->class('btn btn-secondary ml-2') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
