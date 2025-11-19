<div class="">
    <div style="border: 0.1ch dashed #CAB272">
        <div style="background-color: #CAB272">
            <table class="" style="border-collapse: collapse;margin: 10px;width:100%;">
                <tr style="border: 0px">
                    <th style="border: 0px;text-align:center">اسم القسم</th>
                </tr>
            </table>
        </div>
        <div style="padding-left: 3%;padding-right: 3%">
           
            <div style="border-bottom: 0.1% solid #CDCDCD;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr style="background-color: #f9f9f9;">
                        <th style="width:50%; text-align: right; padding: 10px;">السؤال</th>
                        <th style="width:50%;text-align: right; padding: 10px;">الإجابة</th>
                    </tr>
                </table>
            </div>
            @component('admin.export.components.table-child', [
                'key' => 'سؤال ١',
                'value' => 'جواب ١',
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'سؤال 2',
                'value' => 'جواب 2',
            ])
            @endcomponent
            @component('admin.export.form.sections.section-attachments', [
                'key' => 'سؤال ٤',
                'attachments' => ''
            ])
            @endcomponent

        </div>
        <div style="margin-top: 1%"></div>
    </div>
</div>
