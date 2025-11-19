@component('components.nav-pills.tab-pane', ['id' => $key, 'title' => $key])
    @if (in_array($support->status_id, $support->canceled_statuses()->pluck('id')->toArray()))
        <div class="alert alert-primary alert-dismissible alert-label-icon label-arrow fade show my-3" role="alert">
            <i class="ri-error-warning-line label-icon"></i>{{trans('translation.assist-status')}}: <strong> {{$support->status->name}}</strong>
        </div>
    @else
    <div class="position-relative mx-lg-4 my-5 py-4">
        <div class="progress" style="height: 1px;">
            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="25"
                aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="d-flex position-absolute translate-middle start-50 col-12 justify-content-between mb-4">
            @foreach ($support->progress_statuses() as $status)
                <div class="mt-4 text-center">
                    {{-- <button class="btn btn-sm mb-2 rounded-pill {{$status->id <= $support->status_id ? 'btn-primary' : 'btn-light'}}" style="width: 2rem; height:2rem;">{{++$loop->index}}</button> --}}
                    <button
                        class="btn btn-sm mb-2 rounded-pill {{ $status->id < $support->status_id || ($status->id == $support->status_id && $status->name_ar == 'مغلق') ? 'btn-primary' : 'btn-light' }} {{ $status->id == $support->status_id ? 'border border-primary border-2' : '' }}"
                        style="width: 2rem; height:2rem;">{{ ++$loop->index }}</button>
                    <div>{{ $status->name }}</div>
                </div>
                @if ($status->id < $support->status_id)
                    @php
                        $i = $loop->index;
                    @endphp
                @endif
            @endforeach
        </div>
    </div>
    @endif
    <div class="row flex flex-column-reverse flex-lg-row">
        <div class="col-xl-8">
            <div class="row">
                <div class="col-lg-6">
                    @component('components.data-row', ['id' => 'sector'])
                        {{ $support->order_sector->sector->label }}
                    @endcomponent
                    @component('components.data-row', ['id' => 'type'])
                        {{ $support->type_name }}
                    @endcomponent
                    @component('components.data-row', ['id' => 'period'])
                        <span class="badge bg-primary">
                            {{ $support->period->name }}
                        </span>
                    @endcomponent
                    @component('components.data-row', ['id'=>'created_at']){{$support->created_at}}@endcomponent
                    @component('components.data-row', ['id'=>'updated_at']){{$support->updated_at}}@endcomponent
                </div>
                <div class="col-lg-6">
                    @component('components.data-row', ['id' => 'monitor-name'])
                        <div id="monitor_name">{{ implode(', ', $support->order_sector->monitors_name) }}</div>
                    @endcomponent
                    @component('components.data-row', ['id' => 'support-reporter-name'])
                        <div id="user_id">{{ $support->user->name ?? '-'}}</div>
                    @endcomponent
                    @component('components.data-row', ['id' => 'support-quantity'])
                        <div id="total_quantity">{{ $support->quantity }}</div>
                    @endcomponent
                    @component('components.data-row', ['id' => 'given-quantity'])
                        <div id="submitted_quantity">{{ $support->delivered_quantity }}</div>
                    @endcomponent
                    @component('components.data-row', ['id' => 'has-enough'])
                        @if ($support->has_enough == 0)
                            <i class="ri-close-fill text-danger"></i>
                        @else
                            {{ $support->has_enough_quantity }}
                        @endif
                    @endcomponent
                    @component('components.data-row', ['id' => 'meal'])
                    @if ($support->meal_id)
                    <a href="{{route( 'meals.show', $support->meal_id )}}">{{ trans('translation.click-here') }}</a>
                    @else
                    {{ trans('translation.no-related-meal') }}
                    @endif
                    @endcomponent
                </div>
                @component('components.data-row', ['id' => 'note', 'label_col' => 'col-lg-2 col-4', 'content_col' => 'col-lg-10 col-8'])
                    @component('components.notes', ['id' => 'support', 'model' => $support])
                    @endcomponent
                @endcomponent
            </div>
        </div>
        <div class="col-xl-4">
            @component('components.charts.pie-chart', [
                'chartId' => 'supports_pie',
                'colors' => [ '#CAB272','#D3D3D3'],
                'Label'=> trans("translation.support-quantity"),
                'totalData'=> ($support->quantity),
                'size' => 250
            ])
            @endcomponent
        </div>
    </div>

@endcomponent

@push('after-scripts')
    <script>
        $(document).ready(function()
        {
            $('.progress-bar').css('width', ({{ $i }} / ({{ count($statuses) }}) * 100 + '%'));
        })
    </script>
@endpush
