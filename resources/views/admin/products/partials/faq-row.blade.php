{{-- One FAQ row. $i = index (or "__INDEX__" inside the <template>), $faq = ['question' => .., 'answer' => ..] --}}
<div class="faq-row" data-repeater-row>
    <div class="faq-row-head">
        <input class="input @if(is_int($i)) @error("faqs.$i.question") invalid @enderror @endif" name="faqs[{{ $i }}][question]"
            value="{{ is_int($i) ? old("faqs.$i.question", $faq['question'] ?? '') : '' }}" placeholder="Question – e.g. What is the warranty period?">
        <button type="button" class="icon-btn del" title="Remove FAQ" data-repeater-remove><x-icon name="trash" /></button>
    </div>
    @if(is_int($i))
        @error("faqs.$i.question")<div class="error">{{ $message }}</div>@enderror
    @endif
    <div class="field full">
        <div class="editor-wrap @if(is_int($i)) @error("faqs.$i.answer") invalid @enderror @endif">
            <textarea name="faqs[{{ $i }}][answer]" data-editor="basic" rows="3" class="input" placeholder="Answer">{{ is_int($i) ? old("faqs.$i.answer", $faq['answer'] ?? '') : '' }}</textarea>
        </div>
        @if(is_int($i))
            @error("faqs.$i.answer")<div class="error">{{ $message }}</div>@enderror
        @endif
    </div>
</div>
