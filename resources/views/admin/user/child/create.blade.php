@extends('subsystem::layouts.app')

@section('pageTitle', st('Add child'))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.user.child.store'))->open() !!}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Name') . ' *', 'name')->class('control-label') }}
                        {{ html()->text('name', old('name'))->class('form-control')->placeholder(st('Name')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Parent') . ' *', 'parentID')->class('control-label') }}
                        {{ html()->select('parentID',['' => st('Choose parent')] + $parents,old('parentID'))->class('form-select') }}
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
                        {{ html()->label(st('National code'), 'nationalCode')->class('control-label') }}
                        {{ html()->number('nationalCode',old('nationalCode'))->class('form-control')
                        ->placeholder(st('National code')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Username'), 'username')->class('control-label') }}
                        {{ html()->text('username', old('username'))->class('form-control')
                        ->placeholder(st('Username')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Gender') . ' *', 'gender')->class('control-label') }}
                        {{ html()->select('gender',$genders, old('genders'))->class('form-select')}}
                    </div>
                </div>
            </div>
            <div class="text-right mt-2">
                <div class="btn-group gap-2" role="group">
                    {{ html()->submit(st('Confirm'))->class('btn btn-primary') }}
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

        document.querySelector('select[name="parentID"] option[value=""]').disabled = true;
    </script>
@endsection
