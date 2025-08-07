@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('Children list'))
@section('formFields')
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('name', app('request')->input('name'))->class('form-control')->placeholder(st('Name')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('username', app('request')->input('username'))->class('form-control')->placeholder(st('Username')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->number('nationalCode', app('request')->input('nationalCode'))->class('form-control')->placeholder(st('National code'))->attributes(['min'=>'0']) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->number('birthYear', app('request')->input('birthYear'))->class('form-control')->placeholder(st('Birth year'))->attributes(['min'=>'0']) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('status',$statuses, app('request')->input('status'))->class('form-control')->placeholder(st('Status')) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
    <a href="{{route('admin.user.child.create')}}" class="btn btn-secondary ml-2">{{st('menu.Add child')}}</a>
@endsection
