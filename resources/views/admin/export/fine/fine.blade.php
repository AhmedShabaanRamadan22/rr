@component('admin.export.pdf')
    @slot('content')
        <div class="">
            <h4 style="text-align: center;padding-top: 5%">معلومات عامة</h4>
            <table class="body-table" style="width:90%">

                <tr>
                    <th>#</th>
                    <th>التعريف</th>
                    <th style="text-align:center">المعلومة</th>
                </tr>
                <tr>
                    <th>1</th>
                    <td>القطاع</td>
                    <td style="text-align:center">{{ 'de' }}</td>
                </tr>
                <tr>
                    <th>2</th>
                    <td>مزود الخدمة</td>
                    <td style="text-align:center">
                        {{ 'dsds' }}</td>
                </tr>
                <tr>
                    <th>3</th>
                    <td>المخالفة</td>
                    <td style="text-align:center">
                        {{ 'dsds' }}</td>
                </tr>
                <tr>
                    <th>4</th>
                    <td>رمز المخالفة</td>
                    <td style="text-align:center">
                        {{ 'dsds' }}</td>
                </tr>
                <tr>
                    <th>5</th>
                    <td>قيمة المخالفة</td>
                    <td style="text-align:center">
                        {{ 'dsds' }}</td>
                </tr>
                <tr>
                    <th>6</th>
                    <td>المراقب</td>
                    <td style="text-align:center">
                        {{ 'dsds' }}</td>
                </tr>
                <tr>
                    <th>7</th>
                    <td>وقت المخالفة</td>
                    <td style="text-align:center">
                        {{ 'dsds' }}</td>
                </tr>
            </table>

        </div>

        @component('admin.export.components.attachment', [
            'organization' => [],
            'attachments' => collect([]),
        ])
        @endcomponent
    @endslot
@endcomponent
