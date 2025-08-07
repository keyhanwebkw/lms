@extends('subsystem::layouts.app')

@section('pageTitle', st('Add parent'))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.user.parent.store'))->acceptsFiles()->open() !!}
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
                        {{ html()->label(st('National code'), 'nationalCode')->class('control-label') }}
                        {{ html()->number('nationalCode',old('nationalCode'))->class('form-control')
                        ->placeholder(st('National code')) }}
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
                        {{ html()->label(st('Password') . ' *', 'password')->class('control-label') }}
                        {{ html()->password('password')->class('form-control')->placeholder(st('Password')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Gender') . ' *', 'gender')->class('control-label') }}
                        {{ html()->select('gender',$genders,old('gender'))->class('form-select')}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <x-multi-select name="roles" :options="$roles"
                                        label="{{st('Role')}} *"/>
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
                        <x-file-preview name="picture" label="{{st('Person picture')}}"/>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Biography'), 'socialMedia')->class('control-label') }}
                        {{ html()->textarea('socialMedia', old('socialMedia'))->class('form-control')->placeholder(st('Biography')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <p class=" badge bg-warning text-wrap">{{st('Json entering tip')}}</p>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Social media'), 'socialMedia')->class('control-label') }}
                        {{ html()->textarea('socialMedia', old('socialMedia'))->class('form-control')->placeholder('telegram=@example#') }}

                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Extra information'), 'extraInfo')->class('control-label') }}
                        {{ html()->textarea('extraInfo', old('extraInfo'))->class('form-control')->placeholder('رنگ مو=مشکی#') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->submit(st('Confirm'))->class('btn btn-primary') }}
                </div>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}
@endsection

@section('js')
    <script>
        jalaliDatepicker.startWatch();
        jalaliDatepicker.updateOptions({maxDate: 'today'})
    </script>
@endsection
