<div class="row">
    @forelse ($order->contracts() as $contract)
    <div class="col-md-4 col-lg-12">
        <div>{{$contract}}</div>
    </div>
    @empty
        <div class="col-md-12 col-lg-12 text-center">
            @lang('translation.no-contract')
        </div>
    @endforelse
</div>
    
@push('after-scripts')
    <script>
        $(document).ready(function() {
            
        });
    </script>
@endpush