@extends('subsystem::layouts.app')

@section('pageTitle', st('Add movie'))

@section('content')
    {!! html()->form('POST', route('admin.cg.movie.store'))->acceptsFiles()->open() !!}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('name') . ' *', 'name')->class('control-label') }}
                        {{ html()->text('name', old('name'))->class('form-control') }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('slug') . ' *', 'slug')->class('control-label') }}
                        {{ html()->text('slug', old('slug'))->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('description'), 'description')->class('control-label') }}
                        {{ html()->textarea('description', old('description'))->class('form-control')->placeholder(st('description')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('type') . ' *', 'type')->class('control-label') }}
                        {{ html()->select('type', $types,old('type'))->class('form-control')->placeholder(st('type')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-subsystem::multi-select name="movieCategories" :options="$categories"
                                        label="{{st('Add movie categories')}} *"/>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-subsystem::file-preview name="poster" label="{{st('Poster')}} *" />
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
                    {{ html()->a(route('admin.cg.movie.list'), st('Back'))->class('btn btn-secondary') }}
                </div>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}
@endsection
