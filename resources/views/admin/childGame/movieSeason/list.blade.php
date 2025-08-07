@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('Movie seasons'))
@section('formFields')
    {{ html()->hidden('movieID', app('request')->input('movieID')) }}
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('name', app('request')->input('name'))->class('form-control')->placeholder(st('name')) }}
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
    @if(request('movieID'))
        {{ html()->a(route('admin.cg.movieSeason.create', request('movieID')), st('Add season') )->class('btn btn-primary ml-2') }}
    @endif
    {{ html()->a(route('admin.cg.movie.list'), st('Back'))->class('btn btn-secondary') }}
@endsection
