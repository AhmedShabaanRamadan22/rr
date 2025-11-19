@component('components.nav-pills.tab-pane', ['id' => $key, 'padding' => 'p-0 p-md-4'])
@php
    $disabled = null;
    $disabled_message = '';
    if($support->assigned_quantity >= $support->quantity){
        $disabled = 'disabled';
        $disabled_message = trans('translation.all-quantity-assigned');
    }
    if(in_array($support->status->id, $closed_statuses)){
        $disabled = 'disabled';
        $disabled_message = trans('translation.support-status-is-closed');
    }
@endphp
@component('components.section-header',[
    'title'=>$key,
    'disabled' => $disabled,
    'disabled_message'=> $disabled_message
])@endcomponent
    <div class="row mt-3">
        @forelse ($assists as $assist)
            <div class="">
                <div class="card rounded">
                    <div class="card-header bg-light pb-2">
                        <div class="card-title text-primary fw-bold">
                            <div class="row d-flex justify-content-between">
                                <div class="col-lg-6">
                                    {{ trans('translation.assists') }} {{ ++$loop->index }}:
                                </div>
                                <div class="col-lg-6 d-flex justify-content-end">
                                    <button class="btn btn-outline-primary btn-sm m-1 on-default m-r-5 edit-button"
                                        data-bs-target="#editAssist" data-bs-toggle="modal" data-assist-id="{{$assist->id}}"
                                        data-assist-from-id="{{$assist->assist_sector_id}}" data-assistant-id="{{$assist->assistant_id}}" data-quantity="{{$assist->quantity}}">
                                            <i class="mdi mdi-clipboard-edit-outline"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-3 row">
                        <div class="col-lg-6">
                            @component('components.data-row', ['id'=>'assigner']){{$assist->assigner->name}}@endcomponent
                            {{-- @component('components.data-row', ['id'=>'assigner_bravo']){{$assist->assigner->bravo->number ?? ''}}@endcomponent --}}
                            @component('components.data-row', ['id'=>'assistant']){{$assist->assistant->name}}@endcomponent
                            {{-- @component('components.data-row', ['id'=>'assistant_bravo']){{$assist->assigner->bravo->number ?? ''}}@endcomponent --}}
                            @component('components.data-row', ['id'=>'quantity']){{$assist->quantity}}@endcomponent
                            @component('components.data-row', ['id'=>'from_sector']){{$assist->assist_from}}@endcomponent
                            @component('components.data-row', ['id'=>'created_at']){{$assist->created_at}}@endcomponent
                            @component('components.data-row', ['id'=>'updated_at']){{$assist->updated_at}}@endcomponent
                            @component('components.data-row', ['id'=>'assist-status'])
                                    <div class="badge p-2" style="background: {{$assist->status->color}}; max-height:25px">{{$assist->status->name}}</div>
                            @endcomponent
                            @component('components.data-row', ['id'=>'sign'])
                                @if ($delivered_assist == $assist->status->id)
                                    @if (isset($assist->signature_attachment))
                                    <a href="{{ $assist->signature_attachment->url }}"
                                        target="_blank">{{ trans('translation.view') }}</a>
                                    @endif
                                @else
                                    {{ trans('translation.no-data') }}
                                @endif
                            @endcomponent
                            @foreach ($assist->answers as $answer)
                                @component('components.data-answer-row', 
                                    [
                                        'id'=>($question = $answer->question)->question_bank_organization->question_bank->id,
                                        'question_content'=>$question->question_bank_organization->question_bank->content,
                                    ])
                                    {!! $answer_service->generateAnswerValue($answer, $question, null, null, 100) !!}
                                @endcomponent
                            @endforeach
                        </div>
                        <div class="col-lg-6">
                            <h6 class="m-2">{{trans('translation.attachment')}}</h6>
                            @if(count($assist->assist_attachments) > 0)
                                <div id="assist_carousel_{{$assist->id}}" class="carousel slide carousel-dark" data-bs-ride="carousel" style="min-height: 400px; max-height: 400px">
                                    <div class="carousel-inner" role="listbox">
                                        @foreach ($assist->assist_attachments as $attachment)
                                            <div class="carousel-item {{$loop->index == 0 ? 'active' : ''}}" style="min-height: 400px; max-height: 400px">
                                                @if ($attachment->type == 'IMAGE')
                                                <div style="height: 400px; overflow: auto;">
                                                    <img class="d-block img-fluid mx-auto rounded" src="{{$attachment->url}}" style="object-fit: contain; width: 100%;">
                                                </div>
                                                @endif
                                                @if ($attachment->type == 'VIDEO')
                                                <div style="height: 400px; overflow: auto;">
                                                    <video autoplay loop muted controls class="d-block img-fluid mx-auto rounded" src="{{$attachment->url}}" style="object-fit: contain; width: 100%;"></video>
                                                </div>
                                                @endif
                                                <div class="carousel-caption text-white bg-light bg-opacity-75" style="left: 0%; right: 0%; padding-top: 0.5rem; padding-bottom: 0.5rem; bottom: 0">
                                                    <a href="{{$attachment->url}}" download="{{$assist->id . '_' . $attachment->attachment_label->placeholder}}" class="btn btn-primary">{{trans('translation.download')}}</a>
                                                    <a href="{{$attachment->url}}" type="video/quicktime" target="_blank" class="btn btn-secondary">{{trans('translation.view')}}</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <a class="carousel-control-next" href="#assist_carousel_{{$assist->id}}" role="button" data-bs-slide="next">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">{{trans('translation.next')}}</span>
                                    </a>
                                    <a class="carousel-control-prev" href="#assist_carousel_{{$assist->id}}" role="button" data-bs-slide="prev">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">{{trans('translation.previous')}}</span>
                                    </a>
                                </div>
                            @else
                                <div class="text-center align-center py-2">{{trans('translation.no-data')}}</div>
                            @endif
                        </div>

                        @if ($assist->status->id == $in_progress_assist)
                        <div class="border-dashed border-top mx-2 p-2"></div>
                        <div class="text-center">
                            <button class="btn btn-subtle-danger cancel-assist" data-assist-id="{{$assist->id}}">{{trans('translation.cancel-assist')}}</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="">
                {{ trans('translation.no-data') }}
            </div>
        @endforelse
    </div>
@endcomponent