@extends('subsystem::layouts.app')

@section('pageTitle', st('Add content'))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.course.section.episode.content.store',compact('sectionEpisode')))->acceptsFiles()->open() !!}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Title') . ' *', 'name')->class('control-label') }}
                        {{ html()->text('title', old('title'))->class('form-control')->placeholder(st('Title')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('duration') . ' *', 'duration')->class('control-label') }}
                        {{ html()->time('duration')->class('form-control')->id('episode-duration')->attributes(['step'=> 60])}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('description') . ' *', 'description')->class('control-label') }}
                        {{ html()->textarea('description', old('description'))->class('form-control')->rows(3)->placeholder(st('courseDescription')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-subsystem::heavy-uploader name="content" label="{{ st('video') }}" modelName="EpisodeContent"
                                                     maxSize="256"/>                    </div>
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
@section('js')
    <link rel="stylesheet" href="{{ asset('css/flatpicker.css') }}">
    <script src="{{ asset('js/flatpicker.js') }}"></script>
    <script>
        flatpickr("#episode-duration", {
            enableTime: true,       // Enable time selection
            noCalendar: true,       // Hide the calendar
            dateFormat: "H:i",      // 24-hour format (hours:minutes)
            time_24hr: true,        // Force 24-hour format
            minuteIncrement: 1      // Set minute increment (optional)
        });
    </script>
@endsection
