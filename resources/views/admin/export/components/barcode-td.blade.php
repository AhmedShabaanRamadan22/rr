<td style="max-width: 33%">
    <div>
        <img style="max-width: 7%; max-height: 7%; margin-top: 5px"
            src="https://api.qrserver.com/v1/create-qr-code/?data={{ $url }}&size=100x100&color=FFFFFF&bgcolor=000"
            alt="QR Code">
    </div>
    <a href="{{ $url }}" target="_blank" style="color: {{ $organization->primary_color ?? '#CAB272' }}">
        اضغط {{$index ?? 'هنا'}}
    </a>
</td>