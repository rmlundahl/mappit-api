<?php

$app = config('app.name');

return [

    'item_published_subject' => $app.' :item_type gepubliceerd, met id: :item_id',
    'item_published_greeting' => $app.' :item_type gepubliceerd',
    'item_published_line_1' => 'Er is een :item_type gepubliceerd, met id: :item_id',
    'item_published_line_2' => 'Log in om te bekijken:',
    'item_published_action' => 'Bekijk :item_type',
];
