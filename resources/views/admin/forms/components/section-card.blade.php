<div class="card square-card-body shadow border text-center card-section col-4 position-relative">
    <div class="card-header">
        <h6>
            {{ $section->name }}
            <div class="position-absolute bi bi-eye{{ $section->is_visible ? '' : '-slash' }}-fill text-primary fs-5" style="top:2%; left:5%"/>
        </h6>
    </div>

    <div class="card-body">
        <a href="{{ route('forms.sections.show', [$form->id,$section->id]) }}" class="link">{{trans('translation.all-question')}}</a>
    </div>
    <div class="card-footer">
        <a class="btn btn-secondary editSectionBtn" data-bs-toggle="modal" data-bs-target="#editSectionModal"
            data-section-id="{{ $section->id }}" data-section-name="{{ $section->name }}"
            data-section-id="{{ $section->id }}" data-section-arrangement="{{ $section->arrangement }}"
            data-section-visible="{{ $section->is_visible }}">{{trans('translation.edit')}}</a>

        

        <button class="btn btn-danger deleteSectionBtn" data-section-id="{{ $section->id }}" data-form-id="{{ $form->id }}">{{trans('translation.delete')}}</button>
    </div>
</div>
