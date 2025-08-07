@extends('subsystem::layouts.app')

@section('pageTitle', st('Show child'))

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Name') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span($child->name)->class('form-control-plaintext fs-6') }}
                </div>
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Parent') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span($child->parent->fullname)->class('form-control-plaintext fs-6') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Status') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span(st($child->status))->class('form-control-plaintext fs-6') }}
                </div>
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('National code') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span($child->nationalCode)->class('form-control-plaintext fs-6') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Birth date') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span(toJalaliDate($child->birthDate))->class('form-control-plaintext fs-6') }}
                </div>
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Register date') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span(toJalaliDate($child->registerDate))->class('form-control-plaintext fs-6') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Last activity') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span(toJalaliDate($child->lastActivity , 'Y/n/j - H:i:s'))->class('form-control-plaintext fs-6') }}
                </div>
                <div class="col-md-6 mb-3">
                    {{ html()->label(st('Gender') . ':')->class('control-label fw-bold fs-7') }}
                    {{ html()->span(st($child->gender))->class('form-control-plaintext fs-6') }}
                </div>
            </div>
        </div>
    </div>
@endsection
