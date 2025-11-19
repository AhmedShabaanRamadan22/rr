@push('styles')
    <style>
        .tooltip:after {
            direction: rtl !important;
        }
    </style>
@endpush
<div class="row mt-2">
    <h5 class="py-3">{{trans('translation.contract-org') . ' ' . $name}}</h5>


    <div class="col-xl-12 mb-3" id="card-none3">
        <button type="button" class="btn form-control d-flex align-items-between text-secondary p-3 bg-primary-subtle collapser border" id="{{$template_type}}" onclick="this.blur();">
            <div class="flex-grow-1">
                <h6 class="card-title text-start text-primary">{{trans('translation.dictionary')}}</h6>
            </div>
            <div class="flex-shrink-0">
                <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                    <li class="list-inline-item">
                        <a class="align-middle minimize-card collapsed collapse-toggle" data-bs-toggle="collapse" id="toggler-{{$template_type}}" href="#dictionary-{{$template_type}}" role="button" aria-expanded="true" aria-controls="dictionary">
                            <i class="mdi mdi-chevron-up align-middle minus icon-bigger"></i>
                            <i class="mdi mdi-chevron-down align-middle plus icon-bigger"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </button>
        <div class="collapse dictionary" id="dictionary-{{$template_type}}">
            <p class="text-muted py-3">
               {{ trans('translation.select-abbr') }}
                <code>PDF</code>
               {{ trans('translation.auto') }}
            </p>
            <div class="">
                <!-- Bordered Tables -->
                <table class="table table-bordered table-nowrap">
                    <thead>
                        <tr>
                            <th class="text-center align-middle" scope="col">{{ trans('translation.id') }}
                            </th>
                            <th class="text-center align-middle" scope="col">{{ trans('translation.name') }}</th>
                            <th class="text-center align-middle" scope="col">{{ trans('translation.abbr') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dictionaries as $dictionary)
                        <tr>
                            <th class="text-center align-middle" scope="row">{{$dictionary->id}}</th>
                            <td class="text-center align-middle">{{$dictionary->name}}</td>
                            <td class="text-center align-middle"><span id="dictionary-{{$dictionary->id}}"
                                    class="badge bg-primary-subtle text-primary">{{ $dictionary->wrapped_value }}</span>
                                    <button class="btn" data-bs-toggle="tooltip" data-bs-placement="top" title="{{trans("translation.copy-to-clipboard")}}" onclick="copyToClipboard('#dictionary-{{$dictionary->id}}',this)"><i class="mdi mdi-content-copy"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-2"></div>
    <div class="col-lg-5">
        <!-- Bordered Tables -->
        {{-- <table class="table table-bordered table-nowrap">
            <thead>
                <tr>
                    <th class="text-center" scope="col">{{ trans('translation.id') }}
                    </th>
                    <th class="text-center" scope="col">المسمى</th>
                    <th class="text-center" scope="col">الاختصار</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="text-center" scope="row">1</th>
                    <td class="text-center">المنظمة</td>
                    <td class="text-center">
                        <span class="badge bg-primary-subtle text-primary">@{{ organization##name }}</span>
                    </td>
                </tr>
                <tr>
                    <th class="text-center" scope="row">1</th>
                    <td class="text-center">القطاع</td>
                    <td class="text-center">
                        <span class="badge bg-primary-subtle text-primary">@{{ sector##label }}</span>
                    </td>
                </tr>
                <tr>
                    <th class="text-center" scope="row">1</th>
                    <td class="text-center">القئة</td>
                    <td class="text-center">
                        <span class="badge bg-primary-subtle text-primary">@{{ sector##classification##code }}</span>
                    </td>
                </tr>
            </tbody>
        </table> --}}
    </div>
</div>
<div class="mt-2">
    <form class="form-horizontal" action="{{ route('organizations.update', $organization->id) }}" method="post"
        enctype="multipart/form-data" onsubmit="formSubmitted()">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="{{$template_type}}">
        <input type="hidden" name="organization_id" value="{{$organization->id}}">
        <div class="row mb-3">
            <div class="col">
                <textarea id="content" name="content">{{$organization->contract_template($template_type)->content ?? ''}}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <button class="btn btn-primary col-6">{{ trans('translation.update') }}</button>
            </div>
        </div>
    </form>
</div>

@push('after-scripts')
    <script>
        $('.collapser').on('click', function(){
            let id = $(this).attr('id');
            let chevron = document.getElementById(`toggler-${id}`).click();
        });
        function copyToClipboard(element,btn) {
            let $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
            let btnText = $(btn).attr('data-bs-original-title');
            $(btn).attr('data-bs-original-title','{{trans("translation.copied")}}');
            $(btn).tooltip('show');
            $(btn).attr('data-bs-original-title',btnText);
        }
    </script>
@endpush
        
