@extends('subsystem::layouts.app')

@section('pageTitle', st('Add Question'))

@section('content')
    {!! html()->form('POST', route('admin.question.store'))->acceptsFiles()->open() !!}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Difficulty Level') . ' *', 'difficulty')->class('control-label') }}
                        {{ html()->select('questionDifficultyLevel', $difficultyLevel, old('questionDifficultyLevel', 'medium'))
                            ->class('form-select') }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Time Limit (seconds)') . ' *', 'timeLimit')->class('control-label') }}
                        {{ html()->number('timeLimit', old('timeLimit', 60))
                            ->class('form-control')
                            ->placeholder(st('Enter time limit')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Score') . ' *', 'score')->class('control-label') }}
                        {{ html()->number('score', old('score', 1))
                            ->class('form-control')
                            ->placeholder(st('Enter score')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Sort Order') . ' *', 'sortOrder')->class('control-label') }}
                        {{ html()->number('sortOrder', old('sortOrder', 1))
                            ->class('form-control')
                            ->placeholder(st('Sort Order'))
                             ->attributes(['min'=>1])}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0 control-label">{{ st('Question') }} *</h5>
            <div class="card-body">
                <div class="mb-3">
                    <div class="form-group">
                        {{ html()->textarea('question', old('question'))
                            ->class('form-control')
                            ->placeholder(st('Enter the question'))
                            ->attributes(['rows' => '3']) }}
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-group">
                        <x-subsystem::file-preview name="content" label="{{st('content')}} ({{st('ifExists')}})"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-body">
                <x-multi-answer
                    name="answers"
                    label="{{ st('Answers') }} *"
                />
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->submit(st('Submit'))->class('btn btn-primary') }}
                    {{ html()->a(route('admin.question.list',['examID' => $exam->ID]), st('Back'))->class('btn btn-danger') }}
                </div>
            </div>
        </div>
    </div>
    {{ html()->hidden('examID',$exam->ID)}}
    {!! html()->form()->close() !!}
@endsection
