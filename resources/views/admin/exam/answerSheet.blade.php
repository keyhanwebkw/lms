@php use App\Enums\UserExamStatuses; @endphp
@extends('subsystem::layouts.app')

@section('pageTitle', st('User answerSheet', ['name' => $userName]))

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <div class="row fs-6 text-center">
                <div class="col-md-4">
                    {{ html()->label(st('User name') . ': ')->class('form-label fw-semibold') }}
                    {{ html()->span($userName)->class('form-control-plaintext') }}
                </div>
                <div class="col-md-4">
                    {{ html()->label(st('Participate date') . ': ')->class('form-label fw-semibold') }}
                    {{ html()->span(toJalaliDate($userExam->created))->class('form-control-plaintext') }}
                </div>
                <div class="col-md-4">
                    {{ html()->label(st('Retry count') . ': ')->class('form-label fw-semibold') }}
                    {{ html()->span($userExam->retryCount)->class('form-control-plaintext') }}
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row ">
                <div class="col-md-4">
                    {{ html()->label(st('True answers') . ': ')->class('form-label fw-semibold') }}
                    {{ html()->span($userExam->trueAnswers)->class('text-success') }}
                </div>
                <div class="col-md-4">
                    {{ html()->label(st('False answers') . ': ')->class('form-label fw-semibold') }}
                    {{ html()->span($userExam->falseAnswers)->class('text-danger') }}
                </div>
                <div class="col-md-4">
                    {{ html()->label(st('Skipped answers') . ': ')->class('form-label fw-semibold') }}
                    {{ html()->span($userExam->skippedAnswers)->class('text-warning') }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    {{ html()->label(st('Received score') . ': ')->class('form-label fw-semibold') }}
                    {{ html()->span($userExam->score) }}
                </div>
                <div class="col-md-4">
                    {{ html()->label(st('Minimum score to pass') . ': ')->class('form-label fw-semibold') }}
                    {{ html()->span($exam->minScoreToPass) }}
                </div>
                <div class="col-md-4">
                    {{ html()->label(st('Acceptance status') . ': ')->class('form-label fw-semibold') }}
                    @php
                        $class = ($userExam->examStatus == UserExamStatuses::Passed) ? 'text-success' : 'text-danger';
                    @endphp
                    {{ html()->span(st($userExam->examStatus))->class($class) }}
                </div>
            </div>
        </div>
    </div>
    <hr>

    <div class="w-75 mx-auto">
        @foreach($questionData as $key => $value)
            <div class="card">
                <div class="card-body">
                    @if($value['contentSID'])
                        <div class="row">
                            {{ html()->label(st('Content') . ': ')->class('form-label fw-semibold') }}
                            {{ html()->img(route('storage.download',['type' => 'original', 'SID' => $value['contentSID']]))
                            ->class('form-control-plaintext mx-auto mb-3')->style("height: 200px; width: auto; border-radius: 10px;") }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            {{ html()->label(st('Question') . ': ')->class('form-label fw-semibold') }}
                            {{ html()->span($value['question'])->class('') }}
                        </div>

                        @if($value['answer']) @endif
                        <br>
                        <div class="col-md-6">
                            {{ html()->label(st('Answer') . ': ')->class('form-label fw-semibold') }}
                            @if(isset($value['answer']))
                                {{ html()->span($value['answer']) }}
                                @if($value['isCorrect'])
                                    {{ html()->span('(' . st('True') . ')')->class('text-success') }}
                                @else
                                    {{ html()->span('(' . st('False') . ')')->class('text-danger') }}
                                @endif
                            @else
                                {{ html()->span('(' . st('Skipped answers') . ')')->class('text-warning') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="card">
        <div class="card-body">
            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->a(url()->previous(), st('Return'))->class('btn btn-secondary') }}
                </div>
            </div>
        </div>
    </div>
@endsection
