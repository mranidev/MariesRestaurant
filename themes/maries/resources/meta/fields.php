<?php

return [
    // Set form fields for the admin theme settings (Design > Themes > Customise).
    'form' => [
        'general' => [
            'title' => 'General',
            'fields' => [
                'site_name' => [
                    'label' => 'Restaurant Name',
                    'type' => 'text',
                    'span' => 'left',
                    'default' => 'Maries',
                    'comment' => 'Shown in the header logo and footer.',
                    'rules' => 'required|string',
                ],
                'phone' => [
                    'label' => 'Phone Number',
                    'type' => 'text',
                    'span' => 'right',
                    'default' => '+216 53 283 233',
                    'comment' => 'Displayed in the top bar and contact section.',
                    'rules' => 'required|string',
                ],
                'email' => [
                    'label' => 'Email Address',
                    'type' => 'text',
                    'span' => 'left',
                    'default' => 'pastacosi@example.com',
                    'rules' => 'required|email',
                ],
                'address' => [
                    'label' => 'Address',
                    'type' => 'text',
                    'span' => 'right',
                    'default' => 'Lake Constance Street, 1053 Tunis, Tunisia',
                    'rules' => 'required|string',
                ],
                'open_hours' => [
                    'label' => 'Opening Hours',
                    'type' => 'textarea',
                    'span' => 'left',
                    'default' => "Monday-Thursday: 12:00 AM - 23:00 PM\nFriday-Sunday: 12:00 AM - 00:00 PM",
                    'comment' => 'One line per time range, shown in the top bar and contact section.',
                    'rules' => 'required|string',
                ],
            ],
        ],
        'social' => [
            'title' => 'Social Media',
            'fields' => [
                'facebook_url' => [
                    'label' => 'Facebook URL',
                    'type' => 'text',
                    'span' => 'left',
                    'default' => 'https://www.facebook.com/pastacositn/',
                    'rules' => 'nullable|url',
                ],
                'instagram_url' => [
                    'label' => 'Instagram URL',
                    'type' => 'text',
                    'span' => 'right',
                    'default' => 'https://www.instagram.com/pastacositn/',
                    'rules' => 'nullable|url',
                ],
            ],
        ],
    ],
];
