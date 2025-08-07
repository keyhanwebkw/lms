@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('menu.Movie Categories'))
@section('formFields')
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('title', app('request')->input('title'))->class('form-control')->placeholder(st('Title')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->number('sortOrder', app('request')->input('sortOrder'))->class('form-control')->placeholder(st('SortOrder')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('status', [
                        'unarchived' => st('Unarchived'),
                        'archived' => st('Archived'),
                        'all' => st('All'),
                    ] ,app('request')->input('status'))->class('form-control') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
        {{ html()->a(route('admin.cg.movieCategory.create'), st('Add movie categories') )->class('btn btn-secondary ml-2') }}
@endsection
