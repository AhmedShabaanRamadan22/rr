<table class="barcode-table">
    <tr>
        @foreach ($attachments as $attachment)
            @component('admin.export.components.barcode-td', ['url' => $attachment['url'], 'organization' => $organization, 'index' => ++$loop->index])@endcomponent
            @if ((++$loop->index % ($new_line_at ?? 3)) == 0)
                <br>
            @endif
        @endforeach
    </tr>
</table>