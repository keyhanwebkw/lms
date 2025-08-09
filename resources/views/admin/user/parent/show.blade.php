@extends('subsystem::layouts.app')

@section('pageTitle', st('Show parent'))

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Name') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span($parent->name)->class('form-control-plaintext fs-6') }}
                </div>
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Family') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span($parent->family)->class('form-control-plaintext fs-6') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Mobile') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span(str_replace('+', '', $parent->mobile))->class('form-control-plaintext fs-6') }}
                </div>
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('National code') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span($parent->nationalCode)->class('form-control-plaintext fs-6') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Birth date') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span(toJalaliDate($parent->birthDate))->class('form-control-plaintext fs-6') }}
                </div>
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Register date') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span(toJalaliDate($parent->registerDate))->class('form-control-plaintext fs-6') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Last activity') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span(toJalaliDate($parent->lastActivity , 'Y/n/j - H:i:s'))->class('form-control-plaintext fs-6') }}
                </div>
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Gender') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span(st($parent->gender))->class('form-control-plaintext fs-6') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Role') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span($roles ?? '-')->class('form-control-plaintext fs-6') }}
                </div>
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Status') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span(st($parent->status))->class('form-control-plaintext fs-6') }}
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->a(url()->previous(), st('Return'))->class('btn btn-secondary') }}
                </div>
            </div>
        </div>
    </div>
@endsection
