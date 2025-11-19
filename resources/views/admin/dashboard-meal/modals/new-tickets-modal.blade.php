@component('modals.modal-template',[
"modalId"=>"new_tickets_modal",
"modalRoute"=>'dashboard',
"modalSize"=>'modal-xl',
"modalMaxHeight" => '',
])
    <!-- ================================================ -->
    @slot('modalHeader')
        <h5 class="modal-title text-white">{{trans('translation.new-tickets')}}<span id="tickets-label"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                id="close-modal"></button>
    @endslot
    <!-- ================================================ -->
    @slot('modalBody')
        <div class="modal-body" id="new_tickets_body">
            <span class="spinner-border spinner-border-sm  text-center" role="status" aria-hidden="true"></span>
        </div>
    @endslot
    <!-- ================================================ -->
    @slot('modalFooter')
    @endslot

@endcomponent

@push('after-scripts')
    <script>

        {{--    template for the whole tickets table    --}}
        const containerTemplate = (tickets) =>
        {
            return `
                <div class="table-responsive rounded-4 border">
                    <table class="table w-100 text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{trans('translation.code')}}</th>
                                <th>{{trans('translation.status')}}</th>
                                <th>{{trans('translation.reason')}}</th>
                                <th>{{trans('translation.reporter-name')}}</th>
                                <th>{{trans('translation.create-time')}}</th>
                                <th>{{trans('translation.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tickets.map(ticket => ticketTemplate(ticket)).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        {{--    template for each ticket row    --}}
        const ticketTemplate = (ticket) => {
            return `
                    <tr>
                        <td>${ticket.code}</td>
                        <td><span class="text-white rounded-3 px-2 py-1 fs-xs" style="background-color: ${ticket.status_color};">${ticket.status}</span></td>
                        <td><span class="text-white rounded-3 px-2 py-1 fs-xs" style="background-color: ${ticket.level_color};">${ticket.reason}</span></td>
                        <td>${ticket.reporter_name}</td>
                        <td>
                            <span class="${ticket.is_today ? '' : 'text-white rounded-3 px-2 py-1 fs-xs bg-info'}">${ticket.created_at}</span>
                        </td>
                        <td>
                            <a href="${ticket.details_link}"  class="btn btn-outline-secondary btn-sm on-default" target="_blank">
                                <i class="mdi mdi-eye"></i>
                            </a></td>
                        </tr>
            `;
        }

        {{--    fetch new tickets when the modal is opened    --}}
        $('#new_tickets_modal').on('show.bs.modal', function(e) {
            const button = $(e.relatedTarget);
            const orderSectorId = button.data('order-sector-id');
            const new_tickets_body = $('#new_tickets_body');

            $(`.ticket-dot-${orderSectorId}`).hide();
            $('#tickets-label').text(button.data('label'))

            $.ajax({
                type: "GET",
                url: "{{ route('meals-dashboard.new-tickets') }}",
                data: {
                    order_sector_id: orderSectorId,
                },
                dataType: "json",
                headers: {
                    'Accept-Language': 'ar',
                },
                success: function(response, jqXHR, xhr) {
                    new_tickets_body.empty();
                    if (response.data.length > 0) {
                        new_tickets_body.append(containerTemplate(response.data));
                    } else {
                        new_tickets_body.append(`<div class="text-center">
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
