@extends('subsystem::layouts.app')

@section('pageTitle', st('homeQuickAccesses'))

@section('content')
    {!! html()->form('POST', route('admin.setting.indexPage.homeContent.set'))->acceptsFiles()->open() !!}
    <div class="card">
        <div class="card-body text-center">
            <p class=" badge bg-warning text-wrap">{{ st('Multi field setting sets') }}</p>
            <br>
            <p class=" badge bg-info text-wrap">{{ st('White color selection error') }}</p>
        </div>
    </div>
    @foreach($values as $index => $value)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center control-label">{{ st('Segment', ['number' => $index + 1]) }}</h5>
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ html()->label(st('Title') . ' *', "quickAccesses[$index][title]")->class('control-label') }}
                            {{ html()->text("quickAccesses[$index][title]", $value['title'] ?? '')->class('form-control')->placeholder(st('Title'))}}
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ html()->label(st('Link') . ' *', "quickAccesses[$index][link]")->class('control-label') }}
                            {{ html()->text("quickAccesses[$index][link]", $value['link'] ?? '')->class('form-control')->placeholder(st('Link'))}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <x-file-preview name="quickAccesses[{{ $index }}][icon]" label="{{st('Icon')}} *" filePath="{{$value['iconUrl'] ?? ''}}"/>
                            {{ html()->hidden("quickAccesses[$index][iconSID]", $value['iconSID'] ?? '')}}
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ html()->label(st('Color') . ' *', "quickAccesses[$index][color]")->class('control-label') }}
                            {{ html()->input('color', "quickAccesses[$index][color]", $value['color'] ?? '#ffffff')->class('form-control')->placeholder(st('Color')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="card">
        <div class="card-body">
            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->submit(st('submit'))->class('btn btn-primary') }}
                    {{ html()->a(route('admin.setting.indexPage'), st('Return'))->class('btn btn-secondary') }}
                </div>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}
@endsection
