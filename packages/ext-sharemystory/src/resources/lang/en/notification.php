<?php

$app = config('app.name');

return [

    'item_published_subject' => $app.' :item_type published, with id: :item_id',
    'item_published_greeting' => $app.' :item_type published',
    'item_published_line_1' => 'A :item_type has been published, with id: :item_id',
    'item_published_line_2' => 'Log in to view:',
    'item_published_action' => 'View :item_type',
];
