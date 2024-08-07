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
        1 => ['Active','success'],
        2 => ['Issued','primary'],
        3 => ['Disposed','danger'],
        4 => ['Under Maintenance','info'],
        null=>['','secondary']
    ],
    'trailer_status'=>[
        1 => ['Active','success'],
        2 => ['Issued','primary'],
        3 => ['Disposed','danger'],
        4 => ['Under Maintenance','info'],
        null=>['','secondary']
    ],
    'tractor_trailer_status'=>[
        1 => ['Active','success'],              //
        2 => ['Inactive','secondary'],            //
        3 => ['Under Maintenance','warning'],   //
    ],
    'cluster_driver_status'=>[
        1 => 'Available',
        2 => 'Assigned',
        3 => 'Inactive',
    ],
];
