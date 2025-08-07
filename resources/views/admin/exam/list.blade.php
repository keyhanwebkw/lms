@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('Exams list'))
@section('formFields')
    {{ html()->hidden('courseSectionID', app('request')->input('courseSectionID')) }}
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->text('title', app('request')->input('title'))->class('form-control')->placeholder(st('Title')) }}
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
    {{ html()->a(route('admin.course.section.episode.list', ['courseSectionID' => request('courseSectionID')]) , st('Back'))->class('btn btn-secondary ml-2') }}
@endsection
@section('js')
    <script>
        jalaliDatepicker.startWatch();
    </script>
@endsection
