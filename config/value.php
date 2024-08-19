<?php
return [
    'is_active'=>[
        0=> 'Inactive',
        1 => 'Active',
        null=>'Inactive'
    ],
    'status'=>[
        0=> 'Deactivated',
        1 => 'Active',
        2=> 'Inactive',
        null=>'Inactive'
    ],
    'tractor_status'=>[
        1 => ['Available','success'],
        2 => ['Issued','primary'],
        3 => ['Disposed','danger'],
        4 => ['Under Maintenance','info'],
        null=>['','secondary']
    ],
    'trailer_status'=>[
        1 => ['Available','success'],
        2 => ['Issued','primary'],
        3 => ['Disposed','danger'],
        4 => ['Under Maintenance','info'],
        null=>['','secondary']
    ],
    'tractor_trailer_status'=>[
        1 => ['Active','success'],
        2 => ['Inactive','secondary'],
    ],
    'cluster_driver_status'=>[
        1 => ['Available','info'],
        2 => ['Assigned','success'],
        3 => ['Inactive','secondary'],
    ],

    'haulage_status'=>[
        1 => ['Completed','success'],
        2 => ['On-Going','info'],
    ],
];
