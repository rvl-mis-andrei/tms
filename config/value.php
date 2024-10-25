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
        1 => ['On Trip','info'],
        2 => ['No Driver','info'],
        3 => ['For PMS','info'],
        4 => ['Available','info'],
        5 => ['Absent Driver','info'],
        6 => ['Trailer Repair','info'],
        7 => ['Tractor Repair','info'],
        null=>['','secondary']
    ],
    'trailer_status'=>[
        1 => ['On Trip','info'],
        2 => ['No Driver','info'],
        3 => ['For PMS','info'],
        4 => ['Available','info'],
        5 => ['Absent Driver','info'],
        6 => ['Trailer Repair','info'],
        7 => ['Tractor Repair','info'],
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

    'delivery_status'=>[
        0 => ['On Delivery','primary'],
        1 => ['Delivered','success'],
        2 => ['Not Delivered','danger'],
        3 => ['Cancelled Delivered','warning'],
    ],
];
