@extends('subsystem::layouts.app')

@section('pageTitle', st('Add teacher'))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.teacher.store'))->acceptsFiles()->open() !!}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Name') . ' *', 'name')->class('control-label') }}
                        {{ html()->text('name', old('name'))->class('form-control')->placeholder(st('Name')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Family') . ' *', 'family')->class('control-label') }}
                        {{ html()->text('family', old('family'))->class('form-control')->placeholder(st('Family')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Mobile') . ' *', 'mobile')->class('control-label') }}
                        {{ html()->number('mobile',old('mobile'))->class('form-control')->placeholder(st('Mobile')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Email') . ' *', 'email')->class('control-label') }}
                        {{ html()->text('email',old('email'))->class('form-control')
                        ->placeholder(st('Email')) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Birth date'), 'birthDate')->class('control-label') }}
                        {{ html()->text('birthDate',old('birthDate'))->class('form-control')
                        ->attributes(['data-jdp','placeholder'=> st('Birth date')])}}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('avatar') . ' *', 'avatar')->class('control-label') }}
                        {{ html()->file('avatar')->class('form-control')->accept('image/*') }}
                    </div>
                </div>
            </div>
            <div class="row">
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Biography'), 'biography')->class('control-label') }}
                        {{ html()->textarea('biography', old('biography'))->class('form-control')
                        ->placeholder(st('Biography'))->attributes(['rows' => '5']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Start education date'), 'startEducationDate')->class('control-label') }}
                        {{ html()->text('startEducationDate', old('startEducationDate'))->class('form-control')
                        ->attributes(['data-jdp','placeholder'=> st('Start education date')]) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Start teaching date'), 'startTeachingDate')->class('control-label') }}
                        {{ html()->text('startTeachingDate', old('startTeachingDate'))->class('form-control')
                        ->attributes(['data-jdp','placeholder'=> st('Start teaching date')]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Linkedin profile'), 'linkedinProfile')->class('control-label') }}
                        {{ html()->text('linkedinProfile', old('linkedinProfile'))->class('form-control')
                        ->placeholder(st('Linkedin profile')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Website'), 'website')->class('control-label') }}
                        {{ html()->text('website', old('website'))->class('form-control')
                        ->placeholder('http://example.host')}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Telegram Username'), 'telegramUsername')->class('control-label') }}
                        {{ html()->text('telegramUsername', old('telegramUsername'))->class('form-control')
                        ->placeholder(st('Telegram Username')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Rating'), 'rating')->class('control-label') }}
                        {{ html()->number('rating', old('rating'))->class('form-control')
                        ->placeholder(st('Rating'))->attributes(['min'=>0,'max'=>10])}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->submit(st('Add teacher'))->class('btn btn-primary') }}
                    {{ html()->a(route('admin.teacher.list'), st('Return'))->class('btn btn-secondary') }}
                </div>
            </div>
            {!! html()->form()->close() !!}
        </div>
    </div>
@endsection

@section('js')
    <script>
        jalaliDatepicker.startWatch();
        jalaliDatepicker.updateOptions({maxDate: 'today'})
    </script>
@endsection
