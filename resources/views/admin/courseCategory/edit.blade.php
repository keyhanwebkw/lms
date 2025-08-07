@extends('subsystem::layouts.app')

@section('pageTitle', st('Edit course category'))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.courseCategory.update',$courseCategory->ID))->acceptsFiles()->open() !!}

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Title') . ' *', 'name')->class('control-label') }}
                        {{ html()->text('title', $courseCategory->title )->class('form-control')->placeholder(st('Title')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('slug') . ' *', 'slug')->class('control-label') }}
                        {{ html()->text('slug', $courseCategory->slug )->class('form-control')->placeholder(st('courseSlug')) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Sort Order'))->class('control-label') }}
                        {{ html()->number('sortOrder', $courseCategory->sortOrder )->class('form-control')->placeholder(st('Sort Order')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-file-preview name="photo"
                                        filePath="{{route('storage.download',['type' => 'thumbnail','SID' => $courseCategoryPhotoSID ?? '-',])}}" label="{{st('Photo')}}"
                        />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('status'), 'status')->class('control-label') }}
                        {{ html()->select('status', $status)->class('form-control') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Description'), 'description')->class('control-label') }}
                        {{ html()->textarea('description', $courseCategory->description )->class('form-control')->placeholder(st('Description'))->attributes(['rows' => '5']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('metaTitle'), 'metaTitle')->class('control-label') }}
                        {{ html()->text('metaTitle', $courseCategory->metaTitle)->class('form-control')->placeholder(st('metaTitle')) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('metaDescription'), 'metaDescription')->class('control-label') }}
                        {{ html()->textarea('metaDescription', $courseCategory->metaDescription)->class('form-control')->rows(3)->placeholder(st('metaDescription')) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('metaKeyword'), 'metaKeyword')->class('control-label') }}
                        {{ html()->text('metaKeyword', $courseCategory->metaKeyword )->class('form-control')->placeholder(st('metaKeyword')) }}
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
                    {{ html()->a(route('admin.courseCategory.list'), st('Return'))->class('btn btn-secondary') }}
                </div>
            </div>
            {!! html()->form()->close() !!}
        </div>
    </div>
@endsection
