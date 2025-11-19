@if ($attachments->count() > 0)
    <pagebreak />
    <div class="">
        <h4 style="text-align: center;">{{ $label ?? 'جميع المرفقات' }}</h4>
        @foreach ($attachments as $attachment)
            <div style="margin-top: 10%"></div>
            <table class="content-table">
                <tr>
                    @if ($attachment->type != 'IMAGE')
                        <td rowspan="2" style=" height: 100px">
                            <img src="{{ App::environment('local') ? 'https://front-api.rmcc.sa/build/images/tools/scan.png' : asset('build/images/tools/scan.png') }}"
                                alt="{{ $attachment->url }}"
                                style="max-width: 50%; max-height: 50%; object-fit: contain;">
                        </td>
                    @else
                        <td rowspan="2" style=" height: 100px">
                            <img src="{{ App::environment('local') ? 'https://rakaya.sa/_next/static/media/Gold2.22225b5d.webp' : $attachment->url }}"
                                alt="{{ $attachment->url }}"
                                style="max-width: 50%; max-height: 50%; object-fit: contain;">
                        </td>
                    @endif
                    <td class="second-column-top">
                        <div>
                            <span>المرفق: {{ $attachment->attachment_label->placeholder }}</span>
                        </div>
                        <div>
                            <span>تاريخ المرفق: {{ $attachment->created_at }}</span>
                        </div>
                        <div>
                            <span>نوع المرفق: {{ $attachment->type }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="second-column-bottom">
                        <div>
                            <img style="max-width: 10%; max-height: 10%;"
                                src="https://api.qrserver.com/v1/create-qr-code/?data={{ $attachment->url }}&size=500x500&color=FFFFFF&bgcolor=000"
                                alt="QR Code">
                        </div>
                        <br>
                        <div style="margin-top: 10px">
                            <span>امسح الكود لرؤية المرفق</span>
                        </div>
                        <div>
                            <a href="{{ $attachment->url }}" target="_blank"
                                style="color: {{ $organization->primary_color ?? '#CAB272' }}">
                                <span>اضغط هنا</span>
                            </a>
                        </div>
                    </td>
                </tr>
            </table>

            @if ($loop->iteration % 2 == 0 && !$loop->last)
                <pagebreak />
            @endif
        @endforeach
    </div>
@endif
