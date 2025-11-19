@if ($model->notes->count() > 0)
    <td style="text-align:start">
        @foreach ($model->notes as $index => $note)
            <p>{{ $note->content }}</p>
            <table class="notes-table" style="width: 100%;">
                <tr>
                    <td style="width:65%; text-align:right">
                        بواسطة: {{ $note->user_name ?? 'غير معروف' }}
                    </td>
                    <td style="width: 35%; text-align:left important!">
                        {{ \Carbon\Carbon::parse($note->created_at)->format('h:i:sa | Y/m/d') }}
                    </td>
                </tr>
            </table>
        @endforeach
    </td>
@else
    <td style="text-align:center">
        {{trans('translation.no-data')}}
    </td>
@endif