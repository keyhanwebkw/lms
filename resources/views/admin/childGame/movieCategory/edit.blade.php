@extends('subsystem::layouts.app')

@section('pageTitle', st('Add movie categories'))

@section('content')
    {!! html()->form('POST', route('admin.cg.movieCategory.update', $movieCategory->ID))->acceptsFiles()->open() !!}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Title') . ' *', 'title')->class('control-label') }}
                        {{ html()->text('title', old('title', $movieCategory->title))->class('form-control') }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('slug') . ' *', 'slug')->class('control-label') }}
                        {{ html()->text('slug', old('slug', $movieCategory->slug))->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('description'), 'description')->class('control-label') }}
                        {{ html()->textarea('description', old('description', $movieCategory->description))->class('form-control')->placeholder(st('description')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-subsystem::file-preview name="photo" label="{{st('Photo')}} *" filePath="{{ $photoUrl }}" />
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Sort Order') . ' *', 'sortOrder')->class('control-label') }}
                        {{ html()->number('sortOrder', old('sortOrder', $movieCategory->sortOrder))
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
                    {{ html()->a(route('admin.cg.movieCategory.list'), st('Back'))->class('btn btn-secondary') }}
                </div>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}
@endsection
