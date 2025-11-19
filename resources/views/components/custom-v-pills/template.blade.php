<div class="row">
    @component('components.custom-v-pills.pills-section')
        {{$pills}}
    @endcomponent
    @component('components.custom-v-pills.data-section')
        {{$content}}
    @endcomponent
</div>