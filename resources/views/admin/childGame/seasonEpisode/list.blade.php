@extends('subsystem::layouts.dataTable')
@if(request('movieSeasonID'))
    @section('pageTitle', st('Episodes'))
@endif
@section('btn')
    @if(request('movieSeasonID'))
        {{ html()->a(route('admin.cg.seasonEpisode.create', ['movieSeasonID' => request('movieSeasonID')]), st('Add episode') )->class('btn btn-primary ml-2') }}
    @elseif($isAllowedToCreateFilm)
        {{ html()->a(route('admin.cg.seasonEpisode.create', ['movieID' => request('movieID')]), st('Add video') )->class('btn btn-primary ml-2') }}
    @endif
    {{ html()->a(route('admin.cg.movie.list'), st('Movies list'))->class('btn btn-secondary') }}
@endsection
