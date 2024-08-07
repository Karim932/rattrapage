<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination View
    |--------------------------------------------------------------------------
    |
    | This view is used to render the pagination link output, and you can
    | customize it to better match your application's needs by either
    | modifying the view that we provide, or you can use your own view.
    |
    */

    'default' => 'tailwind',

    'view' => 'pagination::tailwind',

    'simple_view' => 'pagination::simple-tailwind',

    /*
    |--------------------------------------------------------------------------
    | Pagination Links per Page
    |--------------------------------------------------------------------------
    |
    | This option controls how many pages should be shown in the pagination.
    | By default, the system will display 3 pages on each side of the
    | current page. You can easily increase or decrease this number
    | according to the needs of your application.
    |
    */

    'links' => 3,

];
