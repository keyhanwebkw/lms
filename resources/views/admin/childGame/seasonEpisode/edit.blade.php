@extends('subsystem::layouts.app')

@section('pageTitle')
    @if($movieSeason)
        {{ st('Edit episode') }}
    @elseif($movie)
        {{ st('Edit film') }}
    @endif
@endsection
@php
$seasonEpisode = $movie ?? $movieSeason
    @endphp
@section('content')
    <div class="card">
        <div class="card-body">
            <p class=" badge bg-warning text-wrap">{{ st('Movie deletion notice') }}</p>
        </div>
    </div>
    {!! html()->form('POST', route('admin.cg.seasonEpisode.update', $seasonEpisode->ID))->acceptsFiles()->open() !!}
    {{ html()->hidden('seasonID', $movieSeason?->ID) }}
    {{ html()->hidden('movieID', $movie?->ID) }}
    {{ html()->hidden('returnUrl', url()->previous()) }}
    <div class="card shadow-sm">
        <div class="card-body">
            @if($movieSeason)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ html()->label(st('Title') . ' *', 'title')->class('control-label') }}
                            {{ html()->text('title', old('title', $seasonEpisode->title))->class('form-control') }}
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ html()->label(st('Sort Order') . ' *', 'sortOrder')->class('control-label') }}
                            {{ html()->number('sortOrder', old('sortOrder', $seasonEpisode->sortOrder))
                                ->class('form-control')
                                ->placeholder(st('Sort Order'))
                                 ->attributes(['min'=>1])}}
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-subsystem::heavy-uploader name="video" label="{{ st('video') }}" modelName="SeasonEpisode"
                                                     acceptMimes="mp4" maxSize="256" fileUrl="{{ $videoUrl }}" SID="{{ $seasonEpisode->videoSID }}" />
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('videoUrl'), 'videoUrl')->class('control-label') }}
                        {{ html()->text('videoUrl', old('videoUrl', $seasonEpisode->videoUrl))->class('form-control')->placeholder('https://example.host/video/route') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->submit(st('Submit'))->class('btn btn-primary') }}
                    {{ html()->a(url()->previous(), st('Back'))->class('btn btn-secondary') }}
                </div>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}
@endsection
