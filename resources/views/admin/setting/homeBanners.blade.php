@extends('subsystem::layouts.app')

@section('pageTitle', st('homeBanners'))

@section('content')
    {!! html()->form('POST', route('admin.setting.indexPage.homeBanners.set'))->acceptsFiles()->open() !!}
    <div class="card">
        <div class="card-body text-center">
            <p class=" badge bg-warning text-wrap">{{ st('Multi field setting sets') }}</p>
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
                            {{ html()->label(st('startDisplayDate') . ' *', "homeBanners[$index][startDisplayDate]")->class('control-label') }}
                            {{ html()->text("homeBanners[$index][startDisplayDate]", toJalaliDate($value['startDisplayDate'] ?? ''))->class('form-control')
                           ->attributes(['data-jdp','placeholder'=> st('startDisplayDate')])}}
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ html()->label(st('endDisplayDate') . ' *', "homeBanners[$index][endDisplayDate]")->class('control-label') }}
                            {{ html()->text("homeBanners[$index][endDisplayDate]",toJalaliDate($value['endDisplayDate']?? '') )->class('form-control')
                           ->attributes(['data-jdp','placeholder'=> st('endDisplayDate')])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <x-file-preview name="homeBanners[{{ $index }}][banner]" label="{{st('banner')}} *" filePath="{{$value['bannerUrl'] ?? ''}}"/>
                            {{ html()->hidden("homeBanners[$index][bannerSID]", $value['bannerSID'] ?? '')}}
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ html()->label(st('Link') . ' *', "homeBanners[$index][link]")->class('control-label') }}
                            {{ html()->text("homeBanners[$index][link]", $value['link'] ?? '')->class('form-control')->placeholder(st('Link')) }}
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
@section('js')
    <script>
        jalaliDatepicker.startWatch();
    </script>
@endsection
