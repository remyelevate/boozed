<?php

namespace App\Fields;

use App\Fields\Sections\PageHeader;
use App\Fields\Sections\ProjectsLister;

class ProjectArchiveFields
{
    public static function init(): void
    {
        acf_add_local_field_group([
            'key'                   => 'group_boozed_project_archive_sections',
            'title'                 => __('Projects overview sections', 'boozed'),
            'fields'                => [
                [
                    'key'           => 'field_boozed_project_archive_sections',
                    'label'         => __('Sections', 'boozed'),
                    'name'          => 'project_archive_sections',
                    'type'          => 'flexible_content',
                    'button_label'  => __('Add section', 'boozed'),
                    'instructions'  => __('Build the /projecten overview with sections.', 'boozed'),
                    'layouts'       => boozed_filter_sections_by_visibility([
                        'layout_boozed_page_header'     => PageHeader::get(),
                        'layout_boozed_projects_lister' => ProjectsLister::get(),
                    ]),
                ],
            ],
            'location'              => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'projects-overview',
                    ],
                ],
            ],
            'position'              => 'normal',
            'style'                 => 'default',
            'label_placement'       => 'top',
            'instruction_placement' => 'label',
            'active'                => true,
        ]);
    }
}
