<?php

namespace Database\Seeders;

use App\Models\AttachmentLabel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttachmentLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // AttachmentLabel::create([ //1
        //     'label' => 'national_id',
        //     'placeholder_ar' => 'بطاقة الهوية',
        //     'placeholder_en' => 'ID Card',
        //     'type' => 'users',
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //2
        //     'label' => 'profile_photo',
        //     'placeholder_ar' => 'صورة الملف الشخصي',
        //     'placeholder_en' => 'Profile Picture',
        //     'type' => 'users',
        //     'is_required' => '0',
        //     'extensions'  => ['jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //3
        //     'label' => 'iban_label',
        //     'placeholder_ar' => 'شهادة الآيبان',
        //     'placeholder_en' => 'IBAN Certificate',
        //     'type' => 'users',
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // //?=============================================================================================

        // AttachmentLabel::create([ //4
        //     'label' => 'organization_profile',
        //     'placeholder_ar' => 'الملف التعريفي',
        //     'placeholder_en' => 'Organization Profile',
        //     'type' => 'organizations',
        //     'is_required' => '0',
        //     'extensions'  => ['pdf'],
        // ]);
        // AttachmentLabel::create([ //5
        //     'label' => 'logo',
        //     'placeholder_ar' => 'الشعار',
        //     'placeholder_en' => 'LOGO',
        //     'type' => 'organizations',
        //     'is_required' => '0',
        //     'extensions'  => ['jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //6
        //     'label' => 'background_image',
        //     'placeholder_ar' => 'الواجهة الخلفية',
        //     'placeholder_en' => 'Background Photo',
        //     'type' => 'organizations',
        //     'is_required' => '0',
        //     'extensions'  => ['jpg', 'png', 'jpeg'],
        // ]);
        // //?=============================================================================================


        // AttachmentLabel::create([ //7
        //     'label' => 'national_id_for_facility_owner',
        //     'placeholder_ar' => 'هوية مالك المنشأة',
        //     'placeholder_en' => "Facility's Owner ID",
        //     'type' => 'facilities',
        //     'arrangement' => 1,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //8
        //     'label' => 'national_id_for_facility_manger_according_to_the_commercial_register',
        //     'placeholder_ar' => 'هوية مدير المنشأة حسب السجل التجاري',
        //     'placeholder_en' => "Facility's Manager ID",
        //     'type' => 'facilities',
        //     'arrangement' => 2,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //9
        //     'label' => 'commercial_registration',
        //     'placeholder_ar' => 'شهادة السجل التجاري',
        //     'placeholder_en' => "Facility's Registration Certificate",
        //     'type' => 'facilities',
        //     'arrangement' => 3,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //10
        //     'label' => 'chamber_of_commerce_subscription_certificate',
        //     'placeholder_ar' => 'شهادة انتساب الغرفة التجارية',
        //     'placeholder_en' => 'Membership Certificate of Makkah Chamber',
        //     'type' => 'facilities',
        //     'arrangement' => 4,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //11
        //     'label' => 'civil_defense_license',
        //     'placeholder_ar' => 'ترخيص الدفاع المدني',
        //     'placeholder_en' => 'Civil Defense Licence',
        //     'type' => 'facilities',
        //     'arrangement' => 5,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //12
        //     'label' => 'commercial_activity_license',
        //     'placeholder_ar' => 'رخصة النشاط التجاري (بلدي)',
        //     'placeholder_en' => 'Commercial Activity Licence (balady)',
        //     'type' => 'facilities',
        //     'arrangement' => 6,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //13
        //     'label' => 'zakat_and_income',
        //     'placeholder_ar' => 'شهادة تسجيل هيئة الزكاة والدخل',
        //     'placeholder_en' => 'General Authority of Zakat & Tax Certificate',
        //     'type' => 'facilities',
        //     'arrangement' => 7,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //14
        //     'label' => 'vat_certificate',
        //     'placeholder_ar' => 'شهادة تسجيل في ضريبة القيمة المضافة',
        //     'placeholder_en' => 'VAT Registration Certificate',
        //     'type' => 'facilities',
        //     'arrangement' => 8,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //15
        //     'label' => 'insurance_certificate',
        //     'placeholder_ar' => 'شهادة التأمينات الإجتماعية',
        //     'placeholder_en' => 'GOSI Certificate',
        //     'type' => 'facilities',
        //     'arrangement' => 9,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //16
        //     'label' => 'saudization',
        //     'placeholder_ar' => 'شهادة السعودة',
        //     'placeholder_en' => 'Saudization Certificate',
        //     'type' => 'facilities',
        //     'arrangement' => 10,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //17
        //     'label' => 'national_address',
        //     'placeholder_ar' => 'العنوان الوطني',
        //     'placeholder_en' => 'National Address',
        //     'type' => 'facilities',
        //     'arrangement' => 11,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //18
        //     'label' => 'iban_number_stamped_by_the_bank',
        //     'placeholder_ar' => 'شهادة الآيبان',
        //     'placeholder_en' => 'IBAN Certificate',
        //     'type' => 'facilities',
        //     'arrangement' => 12,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);

        // AttachmentLabel::create([ //19
        //     'label' => 'memorandum_of_association_only_companies',
        //     'placeholder_ar' => 'عقد تأسيس (فقط للشركات)',
        //     'placeholder_en' => 'Establishment Contract (Only for Companies)',
        //     'type' => 'facilities',
        //     'arrangement' => 17,
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //20
        //     'label' => 'previous_work',
        //     'placeholder_ar' => 'ملف الأعمال',
        //     'placeholder_en' => 'Portfolio',
        //     'type' => 'facilities',
        //     'arrangement' => 18,
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // //?=============================================================================================

        // AttachmentLabel::create([ //21
        //     'label' => 'national_id',
        //     'placeholder_ar' => 'بطاقة الهوية',
        //     'placeholder_en' => 'ID Card',
        //     'type' => 'facility_employees',
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //22
        //     'label' => 'personal_photo',
        //     'placeholder_ar' => 'صورة الملف الشخصي',
        //     'placeholder_en' => 'Profile Picture',
        //     'type' => 'facility_employees',
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //23
        //     'label' => 'work_card',
        //     'placeholder_ar' => 'بطاقة العمل',
        //     'placeholder_en' => 'Job Card',
        //     'type' => 'facility_employees',
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //24
        //     'label' => 'health_card',
        //     'placeholder_ar' => 'البطاقة الصحية',
        //     'placeholder_en' => 'Health Card',
        //     'type' => 'facility_employees',
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);
        // //?=============================================================================================

        // AttachmentLabel::create([ //25
        //     'label' => 'ticket_attachments',
        //     'placeholder_ar' => 'مرفقات البلاغ',
        //     'placeholder_en' => 'Ticket Attachment',
        //     'type' => 'tickets',
        //     'is_required' => '1',
        //     'extensions'  => ['jpg', 'png', 'jpeg', 'mp4', 'mov'],
        // ]);
        // //?=============================================================================================

        // AttachmentLabel::create([ //26
        //     'label' => 'support_attachments',
        //     'placeholder_ar' => 'مرفقات الاسناد',
        //     'placeholder_en' => 'Support Attachment',
        //     'type' => 'supports',
        //     'is_required' => '1',
        //     'extensions'  => ['jpg', 'png', 'jpeg', 'mp4', 'mov'],
        // ]);
        // //?=============================================================================================

        // AttachmentLabel::create([ //27
        //     'label' => 'signature',
        //     'placeholder_ar' => 'التوقيع',
        //     'placeholder_en' => 'Assist Signature',
        //     'type' => 'assists',
        //     'is_required' => '1',
        //     'extensions'  => ['jpg', 'png', 'jpeg'],
        // ]);
        // AttachmentLabel::create([ //28
        //     'label' => 'assist_attachments',
        //     'placeholder_ar' => 'مرفقات الدعم',
        //     'placeholder_en' => 'Assist Attachment',
        //     'type' => 'assists',
        //     'is_required' => '1',
        //     'extensions'  => ['jpg', 'png', 'jpeg', 'mp4', 'mov'],
        // ]);
        // //?=============================================================================================
        // AttachmentLabel::create([ //29
        //     'label' => 'answer_attachments',
        //     'placeholder_ar' => 'مرفقات الاجوبة',
        //     'placeholder_en' => 'Answer Attachment',
        //     'type' => 'answers',
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg', 'mp4', 'mov'],
        // ]);
        // //?=============================================================================================
        // AttachmentLabel::create([ //30
        //     'label' => 'fine_attachments',
        //     'placeholder_ar' => 'مرفقات المخالفة',
        //     'placeholder_en' => 'Fine Attachment',
        //     'type' => 'fines',
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg', 'mp4', 'mov'],
        // ]);
        // //?=============================================================================================
        // AttachmentLabel::create([ //31
        //     'label' => 'candidate_cv_attachments',
        //     'placeholder_ar' => 'السيرة الذاتية للمرشحين',
        //     'placeholder_en' => 'Candidate CV',
        //     'type' => 'candidates',
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);

        // AttachmentLabel::create([ //32
        //     'label' => 'candidate_portfolio_attachments',
        //     'placeholder_ar' => 'محفظة الاعمال للمرشحين',
        //     'placeholder_en' => 'Candidate Portfolio',
        //     'type' => 'candidates',
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);

        // AttachmentLabel::create([ //33
        //     'label' => 'candidate_personal_picture_attachments',
        //     'placeholder_ar' => 'الصورة الشخصية للمرشحين',
        //     'placeholder_en' => 'Candidate Personal Picture',
        //     'type' => 'candidates',
        //     'is_required' => '1',
        //     'extensions'  => ['jpg', 'png', 'jpeg'],
        // ]);
        // //?=============================================================================================

        // AttachmentLabel::create([ //34
        //     'label' => 'Classification_of_catering_1443',
        //     'placeholder_ar' => 'تصنيف طائفة متعهدي الاعاشة 1443',
        //     'placeholder_en' => 'Classification of the catering sect 1443',
        //     'type' => 'facilities',
        //     'arrangement' => 14,
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);

        // AttachmentLabel::create([ //35
        //     'label' => 'Classification_of_catering_1444',
        //     'placeholder_ar' => 'تصنيف طائفة متعهدي الاعاشة 1444',
        //     'placeholder_en' => 'Classification of the catering sect 1444',
        //     'type' => 'facilities',
        //     'arrangement' => 15,
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);

        // AttachmentLabel::create([ //36
        //     'label' => 'Classification_of_catering_1445',
        //     'placeholder_ar' => 'تصنيف طائفة متعهدي الاعاشة 1445',
        //     'placeholder_en' => 'Classification of the catering sect 1445',
        //     'type' => 'facilities',
        //     'arrangement' => 13,
        //     'is_required' => '1',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);

        // AttachmentLabel::create([ //37
        //     'label' => 'municipal_rural_affairs_classification_certificate',
        //     'placeholder_ar' => 'تصنيف وزارة الشؤون البلدية والقروية',
        //     'placeholder_en' => 'Ministry of Municipal and Rural Affairs Classification Certificate',
        //     'type' => 'facilities',
        //     'arrangement' => 16,
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);

        // // ? ===================================================================

        // AttachmentLabel::create([ //38
        //     'label' => 'contract',
        //     'placeholder_ar' => 'العقد',
        //     'placeholder_en' => 'Contract',
        //     'type' => 'contracts',
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);

        // AttachmentLabel::create([ //39
        //     'label' => 'signed_contract',
        //     'placeholder_ar' => 'العقد الموقع',
        //     'placeholder_en' => 'Sigend Contract',
        //     'type' => 'contracts',
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);

        // AttachmentLabel::create([ //40
        //     'label' => 'employee_cv',
        //     'placeholder_ar' => 'السيرة الذاتية للموظف',
        //     'placeholder_en' => 'ِEmployee CV',
        //     'type' => 'facility_employees',
        //     'is_required' => '0',
        //     'extensions'  => ['pdf', 'jpg', 'png', 'jpeg'],
        // ]);


    // INSERT INTO `attachment_labels` (`id`, `label`, `arrangement`, `placeholder_ar`, `placeholder_en`, `type`, `extensions`, `is_required`) VALUES
    $attachmentsLabels = [
        [1, 'national_id', NULL, 'بطاقة الهوية', 'ID Card', 'users', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [2, 'profile_photo', NULL, 'الصورة الشخصية', 'Profile Picture', 'users', ['jpg', 'png', 'jpeg'], '0'],
        [3, 'iban_label', NULL, 'شهادة الآيبان', 'IBAN Certificate', 'users', ['pdf', 'jpg', 'png', 'jpeg'], '0'],
        [4, 'organization_profile', NULL, 'الملف التعريفي', 'Organization Profile', 'organizations', ['pdf'], '0'],
        [5, 'logo', NULL, 'الشعار', 'LOGO', 'organizations', ['jpg', 'png', 'jpeg'], '0'],
        [6, 'background_image', NULL, 'الواجهة الخلفية', 'Background Photo', 'organizations', ['jpg', 'png', 'jpeg'], '0'],
        [7, 'national_id_for_facility_owner', 1, 'هوية مالك المنشأة', 'Facility\'s Owner ID', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [8, 'national_id_for_facility_manger_according_to_the_commercial_register', 2, 'هوية مدير المنشأة حسب السجل التجاري', 'Facility\'s Manager ID', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [9, 'commercial_registration', 3, 'شهادة السجل التجاري', 'Facility\'s Registration Certificate', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [10, 'chamber_of_commerce_subscription_certificate', 4, 'شهادة انتساب الغرفة التجارية', 'Membership Certificate of Makkah Chamber', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [11, 'civil_defense_license', 5, 'ترخيص الدفاع المدني', 'Civil Defense Licence', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [12, 'commercial_activity_license', 6, 'رخصة النشاط التجاري (بلدي)', 'Commercial Activity Licence (balady)', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [13, 'zakat_and_income', 7, 'شهادة تسجيل هيئة الزكاة والدخل', 'General Authority of Zakat & Tax Certificate', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [14, 'vat_certificate', 8, 'شهادة تسجيل في ضريبة القيمة المضافة', 'VAT Registration Certificate', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [15, 'insurance_certificate', 9, 'شهادة التأمينات الإجتماعية', 'GOSI Certificate', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [16, 'saudization', 10, 'شهادة السعودة', 'Saudization Certificate', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [17, 'national_address', 11, 'العنوان الوطني', 'National Address', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [18, 'iban_number_stamped_by_the_bank', 12, 'شهادة الآيبان', 'IBAN Certificate', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [19, 'memorandum_of_association_only_companies', 17, 'عقد تأسيس (فقط للشركات)', 'Establishment Contract (Only for Companies)', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '0'],
        [20, 'previous_work', 18, 'الملف التعريفي للمنشأة', 'Portfolio', 'facilities', ['pdf', 'png', 'jpg', 'jpeg'], '0'],
        [21, 'national_id', NULL, 'بطاقة الهوية', 'ID Card', 'facility_employees', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [22, 'personal_photo', NULL, 'الصورة الشخصية', 'Profile Picture', 'facility_employees', ['jpg', 'png', 'jpeg'], '1'],
        [23, 'work_card', NULL, 'بطاقة العمل', 'Job Card', 'facility_employees', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [24, 'health_card', 4, 'البطاقة الصحية', 'Health Card', 'facility_employees', ['pdf', 'png', 'jpg', 'jpeg'], '0'],
        [25, 'ticket_attachments', NULL, 'مرفقات البلاغ', 'Ticket Attachment', 'tickets', ['jpg', 'png', 'jpeg', 'mp4', 'mov'], '1'],
        [26, 'support_attachments', NULL, 'مرفقات الاسناد', 'Support Attachment', 'supports', ['jpg', 'png', 'jpeg', 'mp4', 'mov'], '1'],
        [27, 'signature', NULL, 'التوقيع', 'Assist Signature', 'assists', ['jpg', 'png', 'jpeg'], '1'],
        [28, 'assist_attachments', NULL, 'مرفقات الدعم', 'Assist Attachment', 'assists', ['jpg', 'png', 'jpeg', 'mp4', 'mov'], '1'],
        [29, 'answer_attachments', NULL, 'مرفقات الاجوبة', 'Answer Attachment', 'answers', ['pdf', 'jpg', 'png', 'jpeg', 'mp4', 'mov'], '0'],
        [30, 'fine_attachments', NULL, 'مرفقات المخالفة', 'Fine Attachment', 'fines', ['pdf', 'jpg', 'png', 'jpeg', 'mp4', 'mov'], '0'],
        [31, 'candidate_cv_attachments', NULL, 'السيرة الذاتية للمرشحين', 'Candidate CV', 'candidates', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [32, 'candidate_portfolio_attachments', NULL, 'محفظة الاعمال للمرشحين', 'Candidate Portfolio', 'candidates', ['pdf', 'jpg', 'png', 'jpeg'], '0'],
        [33, 'candidate_personal_picture_attachments', NULL, 'الصورة الشخصية للمرشحين', 'Candidate Personal Picture', 'candidates', ['jpg', 'png', 'jpeg'], '1'],
        [34, 'Classification_of_catering_1443', 14, 'تصنيف طائفة متعهدي الاعاشة 1443', 'Classification of the catering sect 1443', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '0'],
        [35, 'Classification_of_catering_1444', 15, 'تصنيف طائفة متعهدي الاعاشة 1444', 'Classification of the catering sect 1444', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '0'],
        [36, 'Classification_of_catering_1445', 13, 'تصنيف طائفة متعهدي الاعاشة 1445', 'Classification of the catering sect 1445', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '1'],
        [37, 'municipal_rural_affairs_classification_certificate', 16, 'تصنيف وزارة الشؤون البلدية والقروية', 'Ministry of Municipal and Rural Affairs Classification Certificate', 'facilities', ['pdf', 'jpg', 'png', 'jpeg'], '0'],
        [38, 'contract', NULL, 'العقد', 'Contract', 'contracts', ['pdf', 'jpg', 'png', 'jpeg'], '0'],
        [39, 'signed_contract', NULL, 'العقد الموقع', 'Sigend Contract', 'contracts', ['pdf', 'jpg', 'png', 'jpeg'], '0'],
        [40, 'cv', 5, 'السيرة الذاتية', 'cv', 'facility_employees', ['pdf', 'png', 'jpg', 'jpeg'], '0'],
        [41, 'certificate_of_achievement', 19, 'شهادة إنجاز', 'Certificate of Achievement', 'facilities', ['pdf', 'png', 'jpg', 'jpeg'], '0'],
        [42, 'thankful_letter', 20, 'خطاب شكر', 'Thankful Letter', 'facilities', ['pdf', 'png', 'jpg', 'jpeg'], '0'],
        [43, 'endorsement_or_accreditation_from_the_mission', 21, 'خطاب تأييد أو اعتماد من البعثة', 'Endorsement or Accreditation from the Mission', 'facilities', ['pdf', 'png', 'jpg', 'jpeg'], '0'],
        [44, 'previous_food_menus', 22, 'قوائم طعام سابقة', 'Previous Food Menus', 'facilities', ['pdf', 'png', 'jpg', 'jpeg'], '0'],
        [45, 'candidate_iban', NULL, 'شهادة الآيبان', 'IBAN Certificate', 'candidates', ['pdf', 'png', 'jpg', 'jpeg'], '0'],
        [46, 'candidate_national_id', NULL, 'بطاقة الهوية', 'ID Card', 'candidates', ['pdf', 'png', 'jpg', 'jpeg'], '0'],
        // [47, 'sector_sight', NULL, 'صورة الشاخص', 'Sight photo', 'sectors', ['png', 'jpg', 'jpeg'], '0'],
    ];

    foreach ($attachmentsLabels as $label) {
        AttachmentLabel::create([
            'id' => $label[0],
            'label' => $label[1],
            'arrangement' => $label[2],
            'placeholder_ar' => $label[3],
            'placeholder_en' => $label[4],
            'type' => $label[5],
            'extensions' => $label[6],
            'is_required' => $label[7],
        ]);
    }

    }
}