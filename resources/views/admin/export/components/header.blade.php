<header style="border-bottom-color: {{ $data['organization_data']->primary_color ?? '#cab272cc' }}; margin-bottom:3%">
    <table style="width: 100%; height: 100%; vertical-align: middle;">
        <tr>
            <td>
                <table style="width: 100%; height: 10%;padding-right:20px">
                    <tr>
                        <td style="width: 50%; height: 100px; text-align: right; vertical-align: middle;">
                            <img src="{{ App::environment('local') ? $data['header_default_logo'] : asset('build/images/logos/rakaya.png') }}"
                                alt="Image Description" style="max-width: 15%; max-height: 10%;">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; text-align:center">
                <h1>
                    {{ $data['attachment_label'] }}
                </h1>
            </td>
            <td>
                <table style="width: 100%; height: 10%;padding-left:20px">
                    <tr>
                        <td style="width: 50%; height: 100px; text-align: left; vertical-align: middle;">
                            <img src="{{ App::environment('local') ? $data['header_default_logo'] : ($data['organization_data']->logo ?? asset('build/images/logos/icon-logo.png')) }}"
                                alt="Image Description" style="max-width: 15%; max-height: 10%;">
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
    </table>
</header>
