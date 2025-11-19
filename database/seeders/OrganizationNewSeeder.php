<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationNew;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrganizationNewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationNew::create([
            "new"=> "هذه الاخبار تعرض في الصفحة الرئيسية للمنظمة",
            "organization_id" => 1,
        ]);

        OrganizationNew::create([
            "new"=> "كن مستعدًا للمشاركة في رحلة تجتمع فيها مشاركة المعرفة والتعاون البناء للابتكار ويتشكل فيها مستقبل تجارب الحج والعمرة. حيث سيشارك في مؤتمر خدمات الحج والعمرة خبراء وقادة ورواد أعمال بخبرتهم ورؤاهم المتمشية مع رؤية السعودية 2030، والتي تهدف الى راحة ضيوف الرحمن عبر الارتقاء بمستوى خدمات الحج والعمرة.",
            "organization_id" => 1,
        ]);

        OrganizationNew::create([
            "new"=> "هذه الاخبار تعرض في الصفحة الرئيسية للمنظمة",
            "organization_id" => 2,
        ]);

        OrganizationNew::create([
            "new"=> "كن مستعدًا للمشاركة في رحلة تجتمع فيها مشاركة المعرفة والتعاون البناء للابتكار ويتشكل فيها مستقبل تجارب الحج والعمرة. حيث سيشارك في مؤتمر خدمات الحج والعمرة خبراء وقادة ورواد أعمال بخبرتهم ورؤاهم المتمشية مع رؤية السعودية 2030، والتي تهدف الى راحة ضيوف الرحمن عبر الارتقاء بمستوى خدمات الحج والعمرة.",
            "organization_id" => 2,
        ]);

    }
}