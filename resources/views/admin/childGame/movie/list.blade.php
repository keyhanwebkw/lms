@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('menu.Movies'))
@section('formFields')
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('name', app('request')->input('name'))->class('form-control')->placeholder(st('name')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('type', $types ,app('request')->input('type'))->class('form-control')->placeholder(st('type')) }}
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
    {{ html()->a(route('admin.cg.movie.create'), st('Add movie') )->class('btn btn-secondary ml-2') }}
@endsection
