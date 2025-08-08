@extends('subsystem::layouts.app')

@section('pageTitle', st('homePageContent'))

@section('content')
    {!! html()->form('POST', route('admin.setting.indexPage.homeContent.set'))->acceptsFiles()->open() !!}

    {{-- کارت 1 - عنوان --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ st('Main Title') }}</h5>
        </div>
        <div class="card-body">
            {{ html()->label(st('Title') . ' *', 'title')->class('control-label') }}
            {{ html()->text('title', $values['title'] ?? '')->class('form-control')->placeholder(st('Title')) }}
        </div>
    </div>

    {{-- کارت 2 - عکس اول --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ st('First Image') }}</h5>
        </div>
        <div class="card-body">
            <x-file-preview name="firstImage" label="{{ st('First Image') }} *" filePath="{{ $values['url-firstImageSID'] ?? '' }}" />
            {{ html()->hidden('firstImageSID', $values['firstImageSID'] ?? '') }}
        </div>
    </div>

    {{-- کارت 3 - توضیحات --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ st('Description') }}</h5>
        </div>
        <div class="card-body">
            {{ html()->label(st('Description') . ' *', 'description')->class('control-label') }}
            {{ html()->textarea('description', $values['description'] ?? '')->class('form-control')->placeholder(st('Description'))->rows(4) }}
        </div>
    </div>

    {{-- کارت 4 - عکس کنار توضیحات --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ st('Side Image') }}</h5>
        </div>
        <div class="card-body">
            <x-file-preview name="sideImage" label="{{ st('Side Image') }} *" filePath="{{ $values['url-sideImageSID'] ?? '' }}" />
            {{ html()->hidden('sideImageSID', $values['sideImageSID'] ?? '') }}
        </div>
    </div>

    {{-- کارت 5 - عنوان دوم --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ st('Second Title') }}</h5>
        </div>
        <div class="card-body">
            {{ html()->label(st('Second Title') . ' *', 'secondTitle')->class('control-label') }}
            {{ html()->text('secondTitle', $values['secondTitle'] ?? '')->class('form-control')->placeholder(st('Second Title')) }}
        </div>
    </div>

    {{-- کارت 6 - ویدیو معرفی --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ st('Intro Video') }}</h5>
        </div>
        <div class="card-body">
            <x-file-preview name="introVideo" label="{{ st('Intro Video') }} *" filePath="{{ $values['url-introVideoSID'] ?? '' }}" />
            {{ html()->hidden('introVideoSID', $values['introVideoSID'] ?? '') }}
        </div>
    </div>

    {{-- دکمه‌ها --}}
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
