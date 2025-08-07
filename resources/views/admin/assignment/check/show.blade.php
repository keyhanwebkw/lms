@extends('subsystem::layouts.app')

@section('pageTitle', st('Assignment show',['name' => $userFullName, 'assignment'=>$assignment->title]))

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Sent content'). ':')->class('control-label') }}
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($userAssignment->content as $content)
                    @if($content->text)
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                {{ html()->textarea('text', $content->text)->class('form-control')->style('height: auto;')
                                ->attributes(['disabled','oninput' => 'autoResize(this)'])->id('myTextarea') }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="row">
                @foreach($userAssignment->content as $content)
                    @if($content->contentSID)
                        @if($content->storage->fileType == 'image')
                            <div class="col-md-4 mb-3 text-center">
                                <div class="form-group">
                                    {{ html()->a(route('storage.download',['SID' => $content->contentSID, 'type'=>'original']),st('Show image'))
                                    ->class('btn btn-teal')->attributes(['target' => '_blank']) }}
                                </div>
                            </div>
                        @elseif($content->storage->fileType == 'video')
                            <div class="col-md-4 mb-3 text-center">
                                <div class="form-group">
                                    {{ html()->a(route('storage.download',['SID' => $content->contentSID, 'type'=>'original']),st('Show video'))
                                    ->class('btn btn-flat-teal')->attributes(['target' => '_blank']) }}
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    {!! html()->form('POST', route('admin.assignment.check.update', $userAssignment->ID))->open() !!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Your feedback to this assignment'))->class('control-label') }}
                        {{ html()->textarea('managerResponse', old('managerResponse'))->class('form-control')->style('height: auto;') }}
                    </div>
                </div>
                @if($userAssignment->managerResponse)
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ html()->label(st('Your previous feedback to this assignment'))->class('control-label') }}
                            {{ html()->textarea('previousManagerResponse', $userAssignment->managerResponse )->class('form-control')->style('height: auto;')
                            ->attributes(['disabled','oninput' => 'autoResize(this)'])->id('myTextarea') }}
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Current assignment score') . ' ('. st('MinScoreToPass') . ': ' . $assignment->minScoreToPass . ')')->class('control-label') }}
                        {{ html()->number('receivedScore', old('receivedScore'))->class('form-control')->attributes(['min'=>0])}}
                    </div>
                </div>
                @if($userAssignment->receivedScore)
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ html()->label(st('Previous assignment score'))->class('control-label') }}
                            {{ html()->number('previousReceivedScore', $userAssignment->receivedScore)->class('form-control')->attributes(['min'=>0,'disabled'])}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                {{ html()->hidden('status')->id('status') }}
                <div class="col-md-4 text-end">
                    <div class="form-group">
                        {{ html()->submit(st('Accept'))->class('btn btn-success mx-5')->attributes(['onclick' => "document.getElementById('status').value='accepted'"]) }}
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="form-group">
                        {{ html()->a(route('admin.assignment.check.list',['assignmentID' => $userAssignment->assignmentID]), st('Return'))->class('btn btn-secondary') }}
                    </div>
                </div>
                <div class="col-md-4 text-start">
                    <div class="form-group">
                        {{ html()->submit(st('Reject'))->class('btn btn-danger mx-5')->attributes(['onclick' => "document.getElementById('status').value='rejected'"]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}
@endsection

@section('js')
    <script>
        function autoResize(el) {
            el.style.height = 'auto'; // reset the height
            el.style.height = el.scrollHeight + 'px'; // set height to fit content
        }

        // Optional: Run on page load if textarea already has content
        window.addEventListener('DOMContentLoaded', () => {
            const ta = document.getElementById('myTextarea');
            autoResize(ta);
        });
    </script>
@endsection
