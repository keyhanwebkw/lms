@extends('subsystem::layouts.app')

@section('pageTitle', st('Add teacher'))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.teacher.update', $teacher->ID))->acceptsFiles()->open() !!}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Name') . ' *', 'name')->class('control-label') }}
                        {{ html()->text('name', $teacher->name )->class('form-control')->placeholder(st('Name')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Family') . ' *', 'family')->class('control-label') }}
                        {{ html()->text('family', $teacher->family )->class('form-control')->placeholder(st('Family')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Mobile') . ' *', 'mobile')->class('control-label') }}
                        {{ html()->number('mobile',str_replace('+','',$teacher->mobile))->class('form-control')->placeholder(st('Mobile')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Email') . ' *', 'email')->class('control-label') }}
                        {{ html()->text('email',$teacher->email)->class('form-control')
                        ->placeholder(st('Email')) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Birth date'), 'birthDate')->class('control-label') }}
                        {{ html()->text('birthDate', toJalaliDate($teacher->birthDate) )->class('form-control')
                        ->attributes(['data-jdp','placeholder'=> st('Birth date')])}}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('avatar'), 'avatar')->class('control-label') }}
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
                        {{ html()->textarea('biography', $teacher->biography)->class('form-control')
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
                        {{ html()->text('startEducationDate', toJalaliDate($teacher->startEducationDate))->class('form-control')
                        ->attributes(['data-jdp','placeholder'=> st('Start education date')]) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Start teaching date'), 'startTeachingDate')->class('control-label') }}
                        {{ html()->text('startTeachingDate', toJalaliDate($teacher->startTeachingDate))->class('form-control')
                        ->attributes(['data-jdp','placeholder'=> st('Start teaching date')]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Linkedin profile'), 'linkedinProfile')->class('control-label') }}
                        {{ html()->text('linkedinProfile', $teacher->linkedinProfile)->class('form-control')
                        ->placeholder(st('Linkedin profile')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Website'), 'website')->class('control-label') }}
                        {{ html()->text('website', $teacher->website)->class('form-control')
                        ->placeholder('http://example.host')}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Telegram Username'), 'telegramUsername')->class('control-label') }}
                        {{ html()->text('telegramUsername', $teacher->telegramUsername)->class('form-control')
                        ->placeholder(st('Telegram Username')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Rating'), 'rating')->class('control-label') }}
                        {{ html()->number('rating', $teacher->rating)->class('form-control')
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
                    {{ html()->submit(st('Submit'))->class('btn btn-primary') }}
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
