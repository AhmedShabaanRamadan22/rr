<div class="modal fade" id="deleteQuestionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- <input type="hidden" name="questionable_type" value="OrganizationService"> --}}
            <div class="modal-header">
                <h4>{{__('Delete question')}}</h4>
            </div>
            <form action="{{route('questions.destroy', $question_id)}}" method="post">
                <input type="hidden" name="question_id" value="{{$this->id}}">
                @csrf
                <div class="modal-body text-center">
                    <h4>{{__('Are you sure?')}}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
