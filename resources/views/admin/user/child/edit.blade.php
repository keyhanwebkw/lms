@extends('subsystem::layouts.app')

@section('pageTitle', st('Edit child'))

@section('content')
    <div class="card">
        <div class="card-body">
            {!! html()->form('POST', route('admin.user.child.update',$child->ID))->open() !!}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Name') . ' *', 'name')->class('control-label') }}
                        {{ html()->text('name', $child->name)->class('form-control')->placeholder(st('Name')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Parent') . ' *', 'parentID')->class('control-label') }}
                        {{ html()->select('parentID',$parents,$child->parentID)->class('form-select') }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Birth date'), 'birthDate')->class('control-label') }}
                        {{ html()->text('birthDate',toJalaliDate($child->birthDate))->class('form-control')
                        ->attributes(['data-jdp','placeholder'=> st('Birth date')])}}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('National code'), 'nationalCode')->class('control-label') }}
                        {{ html()->number('nationalCode',$child->nationalCode)->class('form-control')
                        ->placeholder(st('National code')) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Username'), 'username')->class('control-label') }}
                        {{ html()->text('username', $child->username)->class('form-control')
                        ->placeholder(st('Username')) }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Status') . ' *', 'status')->class('control-label') }}
                        {{ html()->select('status', $statuses,$child->status)->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        {{ html()->label(st('Gender') . ' *', 'gender')->class('control-label') }}
                        {{ html()->select('gender',$genders, $child->gender)->class('form-select')}}
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
    </script>
@endsection
