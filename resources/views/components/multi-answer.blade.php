@props([
    'name' => '',
    'label' => '',
    'answers' => [], // Accepts: ['answer 1', 'answer 2', ...]
    'correct' => null // Correct answer ID
])
 {{-- $index is same as ID in edit page --}}
<div class="form-group">
    <h5 class="card-title control-label">{{$label}}</h5>
    <div id="{{ $name }}-answers-container">
        @foreach($answers as $index => $answer)
            <div class="answer-group d-flex align-items-center mb-2">
                <input type="text" name="{{ $name }}[{{ $index }}]" class="form-control me-2" value="{{ $answer }}">
                <div class="form-check form-check-inline me-2">
                    <input type="radio" name="correct" value="{{ $index }}" class="form-check-input" {{ ($correct == $index) ? 'checked' : '' }}>
                    <label class="form-check-label">{{st('Correct answer')}}</label>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-answer">-</button>
            </div>
        @endforeach
    </div>

    <button type="button" class="btn btn-primary mt-2" id="add-answer">{{ st('Add option') }} </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addAnswerButton = document.getElementById('add-answer');
        const container = document.getElementById('{{ $name }}-answers-container');

        addAnswerButton.addEventListener('click', function () {
            const answerIndex = container.children.length;

            const answerGroup = document.createElement('div');
            answerGroup.classList.add('answer-group', 'd-flex', 'align-items-center', 'mb-2');

            answerGroup.innerHTML = `
                <input type="text" name="{{ $name }}[${answerIndex}]" class="form-control me-2">
                <div class="form-check form-check-inline me-2">
                    <input type="radio" name="correct" value="${answerIndex}" class="form-check-input">
                    <label class="form-check-label">{{st('Correct answer')}}</label>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-answer">-</button>
            `;

            container.appendChild(answerGroup);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-answer')) {
                e.target.closest('.answer-group').remove();
            }
        });
    });
</script>
