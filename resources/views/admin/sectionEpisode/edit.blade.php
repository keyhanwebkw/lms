@extends('subsystem::layouts.app')

@section('pageTitle', st('Edit Section Episode'))

@section('content')
    {!! html()->form('POST', route('admin.course.section.episode.update',compact('sectionEpisode')))->open() !!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Sort Order') . ' *')->class('control-label') }}
                        {{ html()->number('sortOrder', old('sortOrder', $sectionEpisode->sortOrder))->class('form-control')->placeholder(st('Sort Order'))->attributes(['min' => 1]) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Status') . ' *', 'status')->class('control-label') }}
                        {{ html()->select('status', $statuses ,old('status', $sectionEpisode->status))->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3 mt-4">
                    <div class="form-group">
                        {{ html()->label(st('is Mandatory'), 'isMandatory')->class('control-label') }}
                        {{ html()->checkbox('isMandatory', old('isMandatory', $sectionEpisode->isMandatory))->class('form-check-input') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-start">
                    <div class="form-group">
                        {{ html()->submit(st('Submit'))->class('btn btn-primary ml-2') }}
                        {{ html()->a(url()->previous(), st('Back'))->class('btn btn-secondary ml-2') }}
                    </div>
                </div>
                <div class="col-md-4 text-start">
                    <div class="form-group">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}
@endsection
