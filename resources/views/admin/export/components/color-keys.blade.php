@foreach ($items as $item)
    <tr>
        <td style="background-color: {{$item['color']}};">
            &nbsp; &nbsp; &nbsp;
        </td>
        <td>{{$item[$description] ?? trans('translation.no-data')}}</td>
    </tr>   
@endforeach