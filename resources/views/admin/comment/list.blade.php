@extends('subsystem::layouts.dataTable')
@section('pageTitle',st('menu.Comments list'))
@section('formFields')
    <div class="col-lg-12 mb-3">
        <div class="form-group row">
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('relatedTo', [
                        'Article' => st('article'),
                        'Course' => st('course')
                    ], app('request')->input('relatedTo'))->class('form-control')->id('relatedToSelect')->placeholder(st('Related to')) }}
                </div>
            </div>
            <div class="col-auto mb-2" id="relatedNameContainer" style="display: none;">
                <div class="input-group">
                    {{ html()->text('relatedName', app('request')->input('relatedName'))->class('form-control')
                       ->id('relatedNameInput')->placeholder(st('Name'))->data('base-placeholder', st('Name')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('status', $statuses ,app('request')->input('status'))->class('form-control')->placeholder(st('status')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('userID', $users ,app('request')->input('userID'))->class('form-control')->placeholder(st('User name')) }}
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="input-group">
                    {{ html()->select('replyStatus', [
                        'answered' => st('Answered'),
                        'unanswered' => st('Unanswered'),
                    ],app('request')->input('replyStatus'))->class('form-control')->placeholder(st('Reply status')) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btn')
    {{ html()->a( url()->previous() , st('Back'))->class('btn btn-secondary ml-2') }}
@endsection

@section('js')
    <script>
        document.getElementById('relatedToSelect').addEventListener('change', function () {
            const container = document.getElementById('relatedNameContainer');
            const input = document.getElementById('relatedNameInput');

            if (this.value) {
                container.style.display = 'block';
                const selectedText = this.options[this.selectedIndex].text;
                input.placeholder = `${input.dataset.basePlaceholder} ${selectedText}`;
            } else {
                container.style.display = 'none';
                input.placeholder = input.dataset.basePlaceholder;
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('relatedToSelect');
            const input = document.getElementById('relatedNameInput');

            if (select.value) {
                const selectedText = select.options[select.selectedIndex].text;
                document.getElementById('relatedNameContainer').style.display = 'block';
                input.placeholder = `${input.dataset.basePlaceholder} ${selectedText}`;
            }
        });
    </script>
@endsection
