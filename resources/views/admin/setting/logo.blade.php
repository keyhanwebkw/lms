@extends('subsystem::layouts.app')


@section('content')
    {!! html()->form('POST', route('admin.setting.logo.set'))->acceptsFiles()->open() !!}

    <div class="card mb-4">
       
        <div class="card-body">
            <x-file-preview name="logo" label="{{ st('logo') }} *" filePath="{{ $logoUrl ?? '' }}" accept="image/png"/>
        </div>
    </div>

    <div class="card">
        <div class="card-body text-right">
            <div class="btn-group gap-2" role="group">
                {{ html()->submit(st('Submit'))->class('btn btn-primary') }}
                {{ html()->a(route('admin.setting.indexPage'), st('Return'))->class('btn btn-secondary') }}
            </div>
        </div>
    </div>

    {!! html()->form()->close() !!}
@endsection
