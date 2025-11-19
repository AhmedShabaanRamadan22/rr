<?php

namespace Database\Seeders;


use App\Models\OrganizationAttachmentLabel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationAttachmentLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationAttachmentLabel::create([
            'organization_id'=> '1',
            'attachment_label_id'=> '1',
            'notes'=> 'that belongs to AlBait Guest',
        ]);

        OrganizationAttachmentLabel::create([
            'organization_id'=> '1',
            'attachment_label_id'=> '2',
        ]);

        OrganizationAttachmentLabel::create([
            'organization_id'=> '2',
            'attachment_label_id'=> '1',
            'notes'=> 'only for Africa',
        ]);

        OrganizationAttachmentLabel::create([
            'organization_id'=> '2',
            'attachment_label_id'=> '2',
        ]);


    }
}
