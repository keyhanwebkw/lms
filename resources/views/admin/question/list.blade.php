@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('menu.Questions list'))
@section('formFields')
    {{ html()->hidden('examID', app('request')->input('examID')) }}
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('question', app('request')->input('question'))->class('form-control')->placeholder(st('Question')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->number('score', app('request')->input('score'))->class('form-control')->placeholder(st('Score'))->attributes(['min'=>1]) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('difficultyLevel',$difficultyLevel, app('request')->input('difficultyLevel'))->class('form-control')->placeholder(st('Difficulty Level')) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
    {{ html()->a(route('admin.exam.list', ['ID' => $exam->ID]), st('Back'))->class('btn btn-danger') }}
    {{ html()->a(route('admin.question.create', $exam), st('Add Question'))->class('btn btn-secondary ml-2') }}
@endsection
