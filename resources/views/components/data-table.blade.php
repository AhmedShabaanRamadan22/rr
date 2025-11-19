<div class="table-responsive">
    <table id="{{ $id }}"
        class="table table-bordered table-hover table-striped text-nowrap key-buttons border-bottom align-middle text-center"
        style="width: 100%">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <x-data-table-col>{{ trans('translation.' . $column) }}</x-data-table-col>
                @endforeach
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
