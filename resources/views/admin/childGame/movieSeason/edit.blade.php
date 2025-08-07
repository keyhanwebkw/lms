@extends('subsystem::layouts.app')

@section('pageTitle', st('Edit season'))

@section('content')
    {!! html()->form('POST', route('admin.cg.movieSeason.update', $movieSeason->ID))->open() !!}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('name') . ' *', 'name')->class('control-label') }}
                        {{ html()->text('name', old('name', $movieSeason->name))->class('form-control') }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Sort Order') . ' *', 'sortOrder')->class('control-label') }}
                        {{ html()->number('sortOrder', old('sortOrder', $movieSeason->sortOrder))
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
            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->submit(st('Submit'))->class('btn btn-primary') }}
                    {{ html()->a(route('admin.cg.movieSeason.list', ['movieID' => $movieSeason->movieID]), st('Back'))->class('btn btn-secondary') }}
                </div>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}
@endsection
