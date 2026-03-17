<?php

namespace App\Fields\Sections;

class IntakeProcess
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_intake_process',
            'name'       => 'intake_process',
            'label'      => 'Intake process',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'   => 'field_boozed_intake_process_title',
                    'label' => 'Title',
                    'name'  => 'intake_process_title',
                    'type'  => 'text',
                    'instructions' => 'e.g. Hoe werkt solliciteren bij Boozed',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'   => 'field_boozed_intake_process_subtitle',
                    'label' => 'Subtitle / CTA line',
                    'name'  => 'intake_process_subtitle',
                    'type'  => 'text',
                    'instructions' => 'Line below the title (e.g. Herken jij je in bovenstaande omschrijving? Stuur ons dan snel je CV en motivatie!)',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_intake_process_button_label',
                    'label'   => 'Button label',
                    'name'    => 'intake_process_button_label',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_intake_process_button_url',
                    'label'   => 'Button URL',
                    'name'    => 'intake_process_button_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_intake_process_image_1',
                    'label'         => 'Image (top left)',
                    'name'          => 'intake_process_image_1',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_intake_process_image_2',
                    'label'         => 'Image (bottom right)',
                    'name'          => 'intake_process_image_2',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'          => 'field_boozed_intake_process_steps',
                    'label'        => 'Steps',
                    'name'         => 'intake_process_steps',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => 'Add step',
                    'min'          => 4,
                    'max'          => 4,
                    'collapsed'    => 'field_boozed_intake_process_step_title',
                    'instructions' => 'Exactly 4 steps. Each has a title (e.g. Stap 1 - Kennismaking) and content.',
                    'sub_fields'   => [
                        [
                            'key'   => 'field_boozed_intake_process_step_title',
                            'label' => 'Step title',
                            'name'  => 'title',
                            'type'  => 'text',
                            'wrapper' => ['width' => '100'],
                        ],
                        [
                            'key'   => 'field_boozed_intake_process_step_content',
                            'label' => 'Content',
                            'name'  => 'content',
                            'type'  => 'textarea',
                            'rows'  => 4,
                            'wrapper' => ['width' => '100'],
                        ],
                    ],
                    'wrapper' => ['width' => '100'],
                ],
            ],
        ];
    }
}
