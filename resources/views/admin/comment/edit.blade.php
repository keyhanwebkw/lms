@php use App\Enums\CommentStatuses; @endphp
@extends('subsystem::layouts.app')

@section('pageTitle')
    @if($comment->status ==  App\Enums\CommentStatuses::PENDING->value)
        {{st('Comment review')}}
    @else
        {{st('Comment edit')}}
    @endif
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.comment.update', $comment->ID))->open() !!}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        @php
                            $title = empty($managerName) ? st('User name') : st('Manager name');
                            $value = empty($userName) ? $managerName : $userName ;
                        @endphp
                        {{ html()->label($title, 'commenter')->class('control-label') }}
                        {{ html()->text('commenter', $value )->class('form-control')->attribute('disabled') }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Replied to'), 'repliedTo')->class('control-label') }}
                        <br>
                        @if($comment->parentID)
                            {{ html()->a(route('admin.comment.list') . '?ID=' . $comment->parentID , st('Show comment'))->class('btn btn-indigo') }}
                        @else
                            {{ html()->button(st('This comment is not a reply on another comment'))->class('btn btn-secondary')->attribute('disabled') }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('content') . ' *', 'content')->class('control-label') }}
                        {{ html()->textarea('content',$comment->content)->class('form-control')->style('height: auto;')}}
                    </div>
                </div>
            </div>
            @if($comment->status ==  CommentStatuses::PENDING->value)
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            {{ html()->hidden('status')->id('status') }}
            <div class="row">
                <div class="col-md-4 text-end">
                    <div class="form-group">
                        {{ html()->submit(st('Approve'))->class('btn btn-success ml-2')->attributes(['onclick' => "document.getElementById('status').value='approved'"]) }}
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="form-group">
                        {{ html()->a(url()->previous(), st('Return'))->class('btn btn-secondary') }}
                    </div>
                </div>
                <div class="col-md-4 text-start">
                    <div class="form-group">
                        {{ html()->submit(st('Reject'))->class('btn btn-danger ml-2')->attributes(['onclick' => "document.getElementById('status').value='rejected'"]) }}
                    </div>
                </div>
            </div>
            @else
                <div class="row">
                    <div class="col-auto mb-2">
                        {{ html()->label(st('status') . ' *', 'status')->class('control-label') }}
                        {{ html()->select('status', $statuses ,[$comment->status])->class('form-control') }}
                    </div>
                </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            {{ html()->submit(st('submit'))->class('btn btn-primary') }}
            {{ html()->a(url()->previous(), st('Return'))->class('btn btn-secondary') }}
            @endif
            {!! html()->form()->close() !!}
        </div>
    </div>
@endsection
