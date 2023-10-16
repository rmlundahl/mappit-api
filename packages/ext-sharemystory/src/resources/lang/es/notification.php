<?php

$app = config('app.name');

return [

    'item_published_subject' => $app.' :item_type publicada, con id: :item_id',
    'item_published_greeting' => $app.' :item_type publicada',
    'item_published_line_1' => 'Se ha publicado una historia :item_type, con id: :item_id',
    'item_published_line_2' => 'Conectarse para ver:',
    'item_published_action' => 'Ver :item_type',
];
