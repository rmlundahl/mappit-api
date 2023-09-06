<?php

namespace Tests\Feature\API\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Item;

class ImportItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_json_data__no_items()
    {
        // init
        Http::fake([
            config('exthvaindestad.import_json_data.api_url')
            => Http::response(
                json_decode(file_get_contents('tests/Feature/API/Item/stubs/response_200.json'), true),
                200
            )
        ]);

        // execute
        $response = $this->getJson('/api/v1/hvaindestad/import/json_data');

        // assert
        $response
            ->assertStatus(200)
            ->assertJsonCount(2);
            //   0 => "Time taken to parse JSON: 1.8557 seconds"
            //   1 => "Done."
            
    }

}
