<?php

return [
    'pattern' => 'choices',
    'action'  => function () {
        return [
            'component' => 'k-choices-view',
            'props' => [
                'choices' => site()->getChoices()
            ]
        ];
    }
];

