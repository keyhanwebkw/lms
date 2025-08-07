@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('Course list'))
@section('formFields')
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('name', app('request')->input('name'))->class('form-control')->placeholder(st('Name')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('slug', app('request')->input('slug'))->class('form-control')->placeholder(st('slug')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('startDate', app('request')->input('startDate'))->class('form-control')->attributes(['data-jdp','placeholder'=> st('startDate')]) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('endDate', app('request')->input('endDate'))->class('form-control')->attributes(['data-jdp','placeholder'=> st('endDate')]) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
    {{ html()->a(route('admin.course.create'), st('addCourse'))->class('btn btn-secondary ml-2') }}
    {{ html()->a(route('admin.course.section.list'), st('list Sections'))->class('btn btn-secondary') }}
    {{ html()->a(route('admin.course.section.episode.list'), st('list episode'))->class('btn btn-secondary') }}
@endsection

@section('js')
    <script>
        jalaliDatepicker.startWatch();
    </script>
@endsection
