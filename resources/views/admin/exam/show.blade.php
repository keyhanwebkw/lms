@extends('subsystem::layouts.app')

@section('pageTitle', st('Exam Info'))

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($exam)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        {{ html()->label(st('Title'))->class('form-label fw-semibold') }}
                        {{ html()->span($exam->title)->class('form-control-plaintext') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        {{ html()->label(st('Description'))->class('form-label fw-semibold') }}
                        {{ html()->span($exam->description)->class('form-control-plaintext') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        {{ html()->label(st('startDate'))->class('form-label fw-semibold') }}
                        {{ html()->span(toJalaliDate($exam->startDate))->class('form-control-plaintext') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        {{ html()->label(st('endDate'))->class('form-label fw-semibold') }}
                        {{ html()->span(toJalaliDate($exam->endDate))->class('form-control-plaintext') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        {{ html()->label(st('duration to minutes'))->class('form-label fw-semibold') }}
                        {{ html()->span($exam->duration)->class('form-control-plaintext') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        {{ html()->label(st('MinScoreToPass'))->class('form-label fw-semibold') }}
                        {{ html()->span($exam->minScoreToPass)->class('form-control-plaintext') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        {{ html()->label(st('RetryAttempts'))->class('form-label fw-semibold') }}
                        {{ html()->span($exam->retryAttempts)->class('form-control-plaintext') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        {{ html()->label(st('status'))->class('form-label fw-semibold') }}
                        {{ html()->span(st($exam->status))->class('form-control-plaintext') }}
                    </div>
                </div>

                <div class="d-flex justify-content-start gap-2 mt-3">
                    {{
                        html()
                        ->a(route('admin.course.list'),st('Return to list' ,['name' => st('course')]))
                        ->class('btn btn-success')
                    }}
{{--                    {{--}}
{{--                        html()--}}
{{--                        ->a(route('admin.exam.edit', [$exam->ID,'relationName' => $relationName, 'ID' => $relationID,'returnUrl' => $returnUrl]), st('Edit Exam'))--}}
{{--                        ->class('btn btn-primary')--}}
{{--                    }}--}}
                    {{
                        html()
                        ->a(route('admin.exam.store', $exam->ID),st('Delete Exam'))
                        ->class('btn btn-danger')
                        ->attribute('onclick','return confirm("'.st('Are you sure you want to delete this exam?').'")')
                    }}
                    {{
                        html()
                        ->a(route('admin.question.list', [$exam->ID,'returnUrl' => $returnUrl]),st('Questions'))
                        ->class('btn btn-secondary')
                    }}
                    {{
                        html()
                        ->a(route('admin.exam.attendees', ['examID' => $exam->ID]),st('Attendees list'))
                        ->class('btn btn-warning')
                    }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-circle fs-1 text-warning mb-3"></i>
                    <p class="fs-5 mb-4 text-muted">{{ st('No exam found for this item.') }}</p>
                    <div class="d-flex justify-content-center gap-3">
                        {{
                            html()
                            ->a(route('admin.course.list'),st('Return to list' ,['name' => st('course')]))
                            ->class('btn btn-success')
                        }}
                        {{
                            html()
                            ->a(route('admin.exam.create', ['relationName' => $relationName, 'ID' => $relationID,'returnUrl' => $returnUrl]),st('Create Exam'))
                            ->class('btn btn-success')
                        }}
                        {{
                            html()
                            ->a(null,st('Edit Exam'))
                            ->class('btn btn-secondary disabled')
                        }}
                        {{
                            html()
                            ->a(null,st('Delete Exam'))
                            ->class('btn btn-outline-danger disabled')
                        }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
