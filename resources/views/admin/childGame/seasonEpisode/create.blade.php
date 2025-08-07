@extends('subsystem::layouts.app')

@section('pageTitle')
    @if($movieSeason)
        {{ st('Add episode') }}
    @elseif($movie)
        {{ st('Add film') }}
    @endif
@endsection

@section('content')
    {!! html()->form('POST', route('admin.cg.seasonEpisode.store'))->acceptsFiles()->open() !!}
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
                            {{ html()->text('title', old('title'))->class('form-control') }}
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
            @endif
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-subsystem::heavy-uploader name="video" label="{{ st('video') }}" modelName="SeasonEpisode"
                            maxSize="256"/>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('videoUrl'), 'videoUrl')->class('control-label') }}
                        {{ html()->text('videoUrl', old('videoUrl'))->class('form-control')->placeholder('https://example.host/video/route') }}
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
