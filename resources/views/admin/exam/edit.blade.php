@extends('subsystem::layouts.app')

@section('pageTitle', st('Edit Exam'))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.exam.update',$exam->ID))->open() !!}

            <div class="row">
                <!-- Title -->
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Title') . ' *', 'title')->class('control-label') }}
                        {{ html()->text('title', old('title', $exam->title))->class('form-control')->placeholder(st('Title')) }}
                    </div>
                </div>

                <!-- Description -->
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Description') . ' *', 'description')->class('control-label') }}
                        {{ html()->textarea('description', old('description', $exam->description))->class('form-control')->rows(3)->placeholder(st('Description')) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Start Date -->
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('startDate') . ' *', 'startDate')->class('control-label') }}
                        {{ html()->text('startDate', old('startDate', toJalaliDate($exam->startDate)))->class('form-control')->attributes(['data-jdp', 'placeholder' => st('startDate')]) }}
                    </div>
                </div>

                <!-- End Date -->
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('endDate') . ' *', 'endDate')->class('control-label') }}
                        {{ html()->text('endDate', old('endDate', toJalaliDate($exam->endDate)))->class('form-control')->attributes(['data-jdp', 'placeholder' => st('endDate')]) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Duration -->
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('duration to minutes') . ' *', 'duration')->class('control-label') }}
                        {{ html()->number('duration', old('duration', $exam->duration))->class('form-control')->id('episode-duration') }}
                    </div>
                </div>

                <!-- Minimum Score to Pass -->
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('MinScoreToPass') . ' *', 'minScoreToPass')->class('control-label') }}
                        {{ html()->number('minScoreToPass', old('minScoreToPass', $exam->minScoreToPass))->class('form-control') }}
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Retry Attempts -->
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('RetryAttempts') . ' *', 'retryAttempts')->class('control-label') }}
                        {{ html()->number('retryAttempts', old('retryAttempts', $exam->retryAttempts))->class('form-control') }}
                    </div>
                </div>
            </div>

            {{ html()->hidden('returnUrl', url()->previous()) }}

            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->submit(st('submit'))->class('btn btn-primary') }}
                    {{ html()->a(url()->previous(), st('Back'))->class('btn btn-secondary') }}
                </div>
            </div>

            {!! html()->form()->close() !!}
        </div>
    </div>
@endsection

@section('js')
    <script>
        jalaliDatepicker.startWatch();
    </script>
@endsection
