@extends('subsystem::layouts.app')

@section('pageTitle', st('Add assignment'))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.assignment.store', $sectionEpisode->ID))->acceptsFiles()->open() !!}
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Title') . ' *', 'title')->class('control-label') }}
                        {{ html()->text('title', old('title'))->class('form-control')->placeholder(st('Title')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('description'), 'description')->class('control-label') }}
                        {{ html()->textarea('description', old('description'))->class('form-control')->placeholder(st('description'))->style('height:auto;') }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Deadline'), 'deadline')->class('control-label') }}
                        {{ html()->number('deadline', old('deadline'))->class('form-control')->placeholder(st('Deadline'))->attributes(['min' => 0]) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('MinScoreToPass') . ' *', 'minScoreToPass')->class('control-label') }}
                        {{ html()->number('minScoreToPass', old('minScoreToPass'))->class('form-control')->placeholder(st('MinScoreToPass'))->attributes(['min' => 0]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-subsystem::file-preview name="content" label="{{st('Content')}}"/>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Retry count') . ' *', 'retryCount')->class('control-label') }}
                        {{ html()->number('retryCount', old('retryCount',1))->class('form-control')->placeholder(st('Retry count'))->attributes(['min' => 1]) }}
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
                    {{ html()->a(route('admin.course.section.episode.list',['courseSectionID' => $sectionEpisode->courseSectionID]), st('Return'))->class('btn btn-secondary') }}
                </div>
            </div>
            {!! html()->form()->close() !!}
        </div>
    </div>
@endsection
