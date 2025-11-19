<footer style="background-color: {{ $data['organization_data']->primary_color ?? '#cab272cc' }};">
    <table width="100%" style="direction:rtl;">
        <tr>
            <td style="width:2.5%;"></td>
            <td style="width:77.5%; font-size:10px;">
                جميع الحقوق محفوظة
                &copy;
                <span>{{ $data['current_year'] }}</span>
                لشركة
                <a href="https://rakaya.sa" target="_blank" style="color:black">ركايا للاستشارات الادارية                </a>
            </td>
            <td style="width:10%; text-align:center; font-size:10px;">{PAGENO} من {nbpg}</td>
            <td style="width:10%; text-align:center"><img style="max-width: 5%; max-height: 5%;"
                    src="https://api.qrserver.com/v1/create-qr-code/?data=OK-OF-GA-LB-RB-MA-EA-AA-RA-JA-MA{{ $data['current_date'] }}&size=100x100&color=FFFFFF&bgcolor=000"
                    alt="QR Code"></td>
        </tr>
    </table>
</footer>
