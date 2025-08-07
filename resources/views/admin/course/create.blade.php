@extends('subsystem::layouts.app')

@section('pageTitle', st('addCourse'))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.course.store'))->acceptsFiles()->open() !!}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('name') . ' *', 'name')->class('control-label') }}
                        {{ html()->text('name', old('name'))->class('form-control')->placeholder(st('courseName')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('slug') . ' *', 'slug')->class('control-label') }}
                        {{ html()->text('slug', old('slug'))->class('form-control')->placeholder(st('courseSlug')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('duration') . ' *', 'duration')->class('control-label') }}
                        {{ html()->number('duration', old('duration'))->class('form-control')->placeholder(st('duration')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('type') . ' *', 'type')->class('control-label') }}
                        {{ html()->select('type', $courseTypes)->class('form-control') }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('startDate') . ' *', 'startDate')->class('control-label') }}
                        {{ html()->text('startDate')->class('form-control')
                       ->attributes(['data-jdp','placeholder'=> st('startDate')])}}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('endDate'), 'endDate')->class('control-label') }}
                        {{ html()->text('endDate')->class('form-control')
                       ->attributes(['data-jdp','placeholder'=> st('endDate')])}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('status'), 'status')->class('control-label') }}
                        {{ html()->select('status', $courseStatuses)->class('form-control') }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('level'), 'level')->class('control-label') }}
                        {{ html()->select('level', $courseLevels,'intermediate')->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('participantLimitation'), 'participantLimitation')->class('control-label') }}
                        {{ html()->number('participantLimitation', old('participantLimitation' ,0))->class('form-control')->placeholder(st('participantLimitation')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('description') . ' *', 'description')->class('control-label') }}
                        {{ html()->textarea('description', old('description'))->class('form-control')->rows(3)->placeholder(st('courseDescription')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-multi-select name="courseCategories" :options="$courseCategories"
                                        label="{{st('Add Course Category')}} *"/>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Course teacher') . ' *', 'teacherID')->class('control-label') }}
                        {{ html()->select('teacherID', $teachers,old('teacherID'))->class('form-control')->placeholder(st('Select the option')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('price'), 'price')->class('control-label') }}
                        {{ html()->text('price', old('price' ,0))->class('form-control')->placeholder(st('price'))->attribute('oninput="formatNumber(this)"') }}
                        <small class="form-text text-muted mt-1">{{ st('price_hint') }}</small>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('discountAmount'), 'discountAmount')->class('control-label') }}
                        {{ html()->text('discountAmount', old('discountAmount' ,0))->class('form-control')->placeholder(st('discountAmount'))->attribute('oninput="formatNumber(this)"') }}
                        <small class="form-text text-muted mt-1">{{ st('discount_hint') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-file-preview name="banner" label="{{st('banner')}}"/>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-file-preview name="introVideo" label="{{st('introVideo')}}"/>
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
                    {{ html()->a(route('admin.course.list'), st('Return'))->class('btn btn-secondary') }}
                </div>
            </div>
            {!! html()->form()->close() !!}
        </div>
    </div>
@endsection

@section('js')
    <script>
        jalaliDatepicker.startWatch();

        document.addEventListener('DOMContentLoaded', function () {
            const prizeInputs = ['price', 'discountAmount'];
            prizeInputs.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    formatNumber(input);
                }
            });
        });
    </script>
@endsection
