@extends('layouts.master')
@section('title', trans('translation.Questions'))


@section('content')
<x-breadcrumb :pageTitle="__('Questions')">
    <li class="breadcrumb-item"><a href="{{ route('meals.show',$meal_organization_stage->meal_id) }}">{{ trans('translation.Meal Stage') }}</a></li>

</x-breadcrumb>

<div class="row">
    <div class="col-md-12  col-xl-12">
        <div class="card">
            <div class="card-body">
                @component('components.data-row', ['id'=>'stage']){{$meal_organization_stage->organization_stage->stage_bank->name}}@endcomponent
                @component('components.data-row', ['id'=>'arrangement']){{$meal_organization_stage->organization_stage->arrangement}}@endcomponent
                @component('components.data-row', ['id'=>'done_at']){{$meal_organization_stage->done_at ?? '-'}}@endcomponent
                @component('components.data-row', ['id'=>'done_by']){{$meal_organization_stage->user->name ?? '-'}}@endcomponent
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12  col-xl-12">
        <div class="card card-collapsed">

            <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
                <div class="row">
                    <h3 class="card-title col-6">{{ trans('translation.Questions') }} </h3>
                </div>
            </div>

            <div class="card-body">
                <div class="col-xl-12">
                    <div class="my-3">
                        <div class="row">
                            @forelse ($questions as $question)
                                <div class="col-6">
                                    @component('components.row-info', ['id'=> $question->id,'label' => $question->question_bank_organization->question_bank->content , 'label_col' => 'col-lg-5 col-6', 'content_col' => 'col-lg-7 col-6'])
                                        @if (($answer = $question->meal_stage_answer($meal_organization_stage->id)->first())->actual_value == 'not-answered')
                                            {{trans('translation.not-answered')}}
                                        @else
                                            @php
                                                $type = $question->question_bank_organization->question_bank->question_type
                                            @endphp
                                            @if ($type->has_option)
                                                @foreach ($answer->actual_value as $value)
                                                    {{ $value->content }}
                                                @endforeach

                                            @elseif ($type->name == 'file' || $type->name == 'files' || $type->name == 'signature')
                                                {{-- files --}}

                                                @foreach ($answer->actual_value as $value)
                                                    <a href="{{ $value['url'] }}" target="_blank">
                                                        عرض @if(count($value??[]) > 1) [{{ $loop->iteration }}] @endif
                                                    </a>
                                                    <br>
                                                @endforeach

                                            @elseif(in_array($question->question_type_id, $answer?->specialQuestions()))
                                                <p>{{  trans('translation.' . $answer?->actual_value ) }}</p>

                                            @else
                                                {{-- string --}}
                                                <p>{{ $answer?->actual_value }}</p>
                                            @endif

                                        @endif
                                    @endcomponent
                                </div>
                            @empty
                            <div class="text-center">
                                {{trans('translation.no-data')}}
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button class="btn btn-secondary px-5 mx-auto" type="button" onclick="goBack()" id="backButton">
                    {{ trans('translation.back') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection




{{-- add new question_type --}}
{{-- <x-crud-model tableName="meal_organization_stages" :columns="$columns" :pageTitle="$pageTitle" :columnInputs="false" /> --}}
{{-- <x-data-table id="meal-organization-stages-datatable" :columns="$columns"/> --}}

@push('after-scripts')
<script>
    function goBack() {
        location.href=localStorage.getItem('goBackHrefShow');
    }
</script>
@endpush
