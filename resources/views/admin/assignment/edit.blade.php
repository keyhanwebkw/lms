@extends('subsystem::layouts.app')

@section('pageTitle', st('Edit assignment'))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.assignment.update', $assignment->ID))->acceptsFiles()->open() !!}
            {{ html()->hidden('previousUrl', url()->previous()) }}
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Title') . ' *', 'title')->class('control-label') }}
                        {{ html()->text('title', old('title', $assignment->title))->class('form-control')->placeholder(st('Title')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('description'), 'description')->class('control-label') }}
                        {{ html()->textarea('description', old('description',$assignment->description))->class('form-control')->placeholder(st('description'))->style('height:auto;') }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Deadline'), 'deadline')->class('control-label') }}
                        {{ html()->number('deadline', old('deadline',$assignment->deadline))->class('form-control')->placeholder(st('Deadline'))->attributes(['min' => 0]) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('MinScoreToPass') . ' *', 'minScoreToPass')->class('control-label') }}
                        {{ html()->number('minScoreToPass', old('minScoreToPass',$assignment->minScoreToPass))->class('form-control')->placeholder(st('MinScoreToPass'))->attributes(['min' => 0]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-file-preview name="content" label="{{st('Content')}}"
                                        filePath="{{ $contentPath }}"
                        />
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Retry count') . ' *', 'retryCount')->class('control-label') }}
                        {{ html()->number('retryCount', old('retryCount',$assignment->retryCount))->class('form-control')->placeholder(st('Retry count'))->attributes(['min' => 1]) }}
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
                    {{ html()->a(url()->previous(), st('Return'))->class('btn btn-secondary') }}
                </div>
            </div>
            {!! html()->form()->close() !!}
        </div>
    </div>
@endsection
