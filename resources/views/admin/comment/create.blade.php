@extends('subsystem::layouts.app')

@section('pageTitle', st('Replying to') . ':')

@section('content')
    <div class="card mt-2">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <span class="fs-6">"{{$comment->content}}"</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.comment.store',$comment->ID))->open() !!}
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('content') . ' *', 'content')->class('control-label') }}
                        {{ html()->textarea('content', old('content'))->class('form-control')->style('height: auto;')->placeholder(st('content')) }}
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
                    {{ html()->a( url()->previous(), st('Return'))->class('btn btn-secondary') }}
                </div>
            </div>
            {!! html()->form()->close() !!}
        </div>
    </div>
@endsection
