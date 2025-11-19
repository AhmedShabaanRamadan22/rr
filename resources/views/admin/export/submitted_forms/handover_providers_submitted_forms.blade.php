<!DOCTYPE html>
<html lang="ar" dir="rtl">
@php
    $answers = $data['answers'];
    $sector = $data['sector'];
    $nationality_name = $data['nationality_name'];
    $organization_name = $data['organization_name'];
    $organization_license_id = $data['organization_license_id'];
    $facility = $data['facility'];
    $submitted_form = $data['submitted_form'];
    $handover_provider_answer_service = $data['handover_provider_answer_service'];
    $minaFormImage = $data['minaFormFlag'];
    $arafahFormImage = $data['arafahFormFlag'];
    $hijri = $data['hijri'];
@endphp
<head>
    <meta charset="UTF-8">
    <title>محضر تسليم متعهد الإعاشة</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            font-size: 14px;
            margin: 40px 60px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px 8px;
            vertical-align: top;
        }

        .section-title {
            background-color: #c7e3c1;
            font-weight: bold;
            text-align: center;
        }

        .data-label {
            background-color: #f0ffed;
            font-weight: bold;
        }

        .checkbox-icon{
            height: 14px;
            width: 14px;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div style="margin:0 20;">
        <div class="text-center text-bold" style="margin-bottom: 20px;">
            شركات تقديم الخدمة لحجاج الخارج<br>
            محضر تسليم متعهد الإعاشة (مطبخ)<br>
            لموسم حج عام 1446هـ
        </div>

        <!-- بيانات الموقع المسلم -->
        <table>
            <tr>
                <td colspan="2" class="section-title">بيانات الموقع المسلم:</td>
            </tr>
            <tr>
                <td class="data-label">مكان المطبخ:</td>
                <td><img class="checkbox-icon" src="{{ asset('build/images/symbols/'.$minaFormImage.'.png') }}" alt="">  منى  <img class="checkbox-icon" src="{{ asset('build/images/symbols/'.$arafahFormImage.'.png') }}" alt=""> عرفات</td>
                <!-- <td> ></td> -->
                <!-- <td></td> -->
            </tr>
        </table>

        <!-- بيانات مركز الخدمة -->
        <table>
            <tr>
                <td colspan="6" class="section-title">بيانات مركز الخدمة:</td>
            </tr>
            <tr>
                <td class="data-label">اليوم:</td>
                <td>{{($submitted_form_created_at = $submitted_form->created_at)->translatedFormat("l")}}</td>
                <td class="data-label">التاريخ:</td>
                <td>{{$submitted_form_created_at->format('Y/m/d')}}</td>
                <td class="data-label">الموافق:</td>
                <td>{{$hijri::ShortDate($submitted_form_created_at)}}</td>
            </tr>
            <tr>
                <td class="data-label" colspan="2">وقت التسليم:</td>
                <td colspan="4">الساعة: {{$submitted_form_created_at->format('H:i:s')}}</td>

            </tr>
        </table>

        <!-- بيانات متعهد الإعاشة -->
        <table>
            <tr>
                <td colspan="4" class="section-title">بيانات متعهد الإعاشة (المستلم):</td>
            </tr>
            <tr>
                <td class="data-label">رقم السجل التجاري</td>
                <td>{{$facility->registration_number ?? "-"}}</td>
                <td class="data-label">اسم الشركة</td>
                <td>{{$facility->name}}</td>
            </tr>
            <tr>
                <td class="data-label">رقم التواصل</td>
                <td>{{$facility->user->phone}}</td>
                <td class="data-label">اسم المتعهد</td>
                <td>{{$facility->user->name}}</td>
            </tr>
            <tr>
                <td  class="data-label" colspan="1">العنوان الرئيسي:</td>
                <td colspan="3">{{$facility->national_address}}</td>
            </tr>
        </table>

        <!-- بيانات شركة تقديم الخدمة -->
        <table>
            <tr>
                <td colspan="6" class="section-title">بيانات شركة تقديم الخدمة (المسلم):</td>
            </tr>
            <tr>
                <td class="data-label">رقم الترخيص</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,300,null,$organization_license_id??null)}}</td>
                <td class="data-label">اسم شركة تقديم الخدمة</td>
                <td colspan="3">{{$handover_provider_answer_service->getValueAnswerById($answers,319,null,$organization_name??null)}}</td>
            </tr>
            <tr>
                <td class="data-label">رقم المربع</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,301,null,$sector->block_number??null)}}</td>
                <td class="data-label">رقم المخيم</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,306,null,$sector->camp_number??null)}}</td>
                <td class="data-label">رقم الباقة في المسار</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,302,null,$sector->track_package_number??null)}}</td>
            </tr>
            <tr>
                <td class="data-label">رقم الشاخص</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,320,null,$sector->sight??null)}}</td>
                <td class="data-label">رقم مركز الخدمة</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,321,null,$sector->label??null)}}</td>
                <td class="data-label">اسم المركز</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,322,null,$sector->label??null)}}</td>
            </tr>
            <tr>
                <td class="data-label">جنسية الحجاج</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,323,null,$nationality_name??null)}}</td>
                <td class="data-label">عدد الحجاج في المخيم</td>
                <td colspan="3">{{$handover_provider_answer_service->getValueAnswerById($answers,324,null,$sector->guest_quantity??null)}}</td>
            </tr>
            <tr>
                <td class="data-label">رقم التواصل</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,303)}}</td>
                <td class="data-label">اسم ممثل المركز</td>
                <td colspan="3">{{$handover_provider_answer_service->getValueAnswerById($answers,325,null,$handover_provider_answer_service->getValueAnswerById($answers,308))}}</td>
            </tr>
        </table>

        <!-- إقرار التسليم -->
        <table>
            <tr>
                <td class="section-title">إقرار تسليم</td>
            </tr>
            <tr>
                <td >
                    <h4>
                        ١. يقر متعهد الإعاشة الموضحة بياناته أعلاه بأنه استلم كامل الموقع بالتاريخ الموضح أعلاه ويلتزم بتجهيزه وتوريد كامل التجهيزات والمواد خلال المدة المحددة في عقد الإعاشة.<br>
                        ٢. يقر المتعهد بتحمله نتيجة أي تأخير أو تجاوز للمدة المحددة للتجهيز.<br>
                        ٣. يلتزم الطرفان بالالتزام بعقد الاتفاق الموقع بينهما، ويتحمل كل منهما تبعات أي تأخير تحدث من طرفه.<br>
                        ٤. تسلم نسخة من هذا المحضر موقعة من الطرفين للمجلس التنسيقي لشركات تقديم الخدمة لحجاج الخارج.
                    </h4>
                </td>
            </tr>
        </table>

        <!-- بيانات الطرفين -->
        <table>
            <tr>
                <td colspan="3" class="section-title">بيانات الطرفين (التسليم والاستلام)</td>
            </tr>
            <tr>
                <td class="section-title" rowspan="2"></td>
                <td class="text-center">ممثل المستلم</td>
                <td class="text-center">ممثل المسلم</td>
            </tr>
            <tr>
                <td class="data-label">اسم الجهة: {{$handover_provider_answer_service->getValueAnswerById($answers,326)}}</td>
                <td class="data-label">اسم الجهة: {{$handover_provider_answer_service->getValueAnswerById($answers,327)}}</td>
            </tr>
            <tr>
                <td class="data-label">الاسم الرباعي</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,304)}}</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,308)}}</td>
            </tr>
            <tr>
                <td class="data-label">الصفة</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,305)}}</td>
                <td>{{$handover_provider_answer_service->getValueAnswerById($answers,309)}}</td>
            </tr>
            <tr>
                <td class="data-label">التوقيع</td>
                <td>
                    {{$handover_provider_answer_service->getSignatureAnswerValueById($answers,307)}}
                </td>
                <td>
                    {{$handover_provider_answer_service->getSignatureAnswerValueById($answers,310)}}
                </td>
            </tr>
            <tr>
                <td class="data-label">التاريخ</td>
                <td>{{$handover_provider_answer_service->getDateAnswerValueById($answers,307,'created_at')}}</td>
                <td>{{$handover_provider_answer_service->getDateAnswerValueById($answers,310,'created_at')}}</td>
            </tr>
        </table>
    </div>
</body>

</html>