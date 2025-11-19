@component('modals.modal-template',[
"modalId"=>"new_supports_modal",
"modalRoute"=>'dashboard',
"modalSize"=>'modal-xl',
"modalMaxHeight" => '',
])
    <!-- ================================================ -->
    @slot('modalHeader')
        <h5 class="modal-title text-white">{{trans('translation.new-supports')}}<span id="supports-label"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                id="close-modal"></button>
    @endslot
    <!-- ================================================ -->
    @slot('modalBody')
        <div class="modal-body py-4" id="new_supports_body">
            <span class="spinner-border spinner-border-sm  text-center" role="status" aria-hidden="true"></span>
        </div>
    @endslot
    <!-- ================================================ -->
    @slot('modalFooter')
    @endslot

@endcomponent

@push('after-scripts')
    <script>

        {{--    template for the whole supports table    --}}
        const supportContainerTemplate = (supports) =>
        {
            return `
                <div class="table-responsive rounded-4 border">
                    <table class="table w-100 text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{trans('translation.code')}}</th>
                                <th>{{trans('translation.status')}}</th>
                                <th>{{trans('translation.type')}}</th>
                                <th>{{trans('translation.period')}}</th>
                                <th>{{trans('translation.reason')}}</th>
                                <th>{{trans('translation.quantity')}}</th>
                                <th>{{trans('translation.reporter-name')}}</th>
                                <th>{{trans('translation.create-time')}}</th>
                                <th>{{trans('translation.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${supports.map(support => supportTemplate(support)).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        {{--    template for the support row    --}}
        const supportTemplate = (support) => {
            return `
                    <tr>
                        <td>${support.code}</td>
                        <td><span class="text-white rounded-3 px-2 py-1 fs-xs" style="background-color: ${support.status_color};">${support.status}</span></td>
                        <td>${support.type}</td>
                        <td>${support.period}</td>
                        <td><span class="text-white rounded-3 px-2 py-1 fs-xs" style="background-color: ${support.level_color};">${support.reason}</span></td>
                        <td>${support.quantity}</td>
                        <td>${support.reporter_name}</td>
                        <td>
                            <span class="${support.is_today ? '' : 'text-white rounded-3 px-2 py-1 fs-xs bg-info'}">${support.created_at}</span>
                        </td>
                        <td>
                            <a href="${support.details_link}"  class="btn btn-outline-secondary btn-sm on-default" target="_blank">
                                <i class="mdi mdi-eye"></i>
                            </a></td>
                        </tr>
            `;
        }

        {{--    fetch new supports when the modal is opened    --}}
        $('#new_supports_modal').on('show.bs.modal', function(e) {
            const button = $(e.relatedTarget);
            const mealId = button.data('meal-id');
            const orderSectorId = button.data('order-sector-id');
            const new_supports_body = $('#new_supports_body');

            $(`.support-dot-${orderSectorId}`).hide();
            $('#supports-label').text(button.data('label'))

            $.ajax({
                type: "GET",
                url: "{{ route('meals-dashboard.new-supports') }}",
                data: {
                    meal_id: mealId,
                    order_sector_id: orderSectorId,
                },
                dataType: "json",
                headers: {
                    'Accept-Language': 'ar',
                },
                success: function(response, jqXHR, xhr) {
                    new_supports_body.empty();
                    if (response.data.length > 0) {
                        new_supports_body.append(supportContainerTemplate(response.data));
                    } else {
                        new_supports_body.append(`<div class="text-center">
                                <div class="fw-bold text-secondary py-2" style="color: #9C9C9C!important;">{{ trans('translation.no-data') }}
                        </div>
                </div>`);
                    }

                },
                error: function(response, jqXHR, xhr) {
                    console.log(response);
                    Toast.fire({
                        icon: "error",
                        title: "{{ trans('translation.something went wrong') }}"
                    });
                },
            });
        })
    </script>
@endpush
