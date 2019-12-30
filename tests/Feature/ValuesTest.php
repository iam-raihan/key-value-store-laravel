<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValuesTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateOperation()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('POST', '/api/values', [
            "123" => "123",
            "abc" => "abc"
        ]);

        $response
            ->assertJson([
                'status' => 201
            ])
            ->assertCreated();

        $this->assertDatabaseHas('values', [
            'key' => '123',
            'value' => '123'
        ]);

        $this->assertDatabaseHas('values', [
            'key' => 'abc',
            'value' => 'abc'
        ]);
    }

    public function testEmptyDataCreateOperation()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('POST', '/api/values', []);

        $response
            ->assertJson([
                'status' => 400
            ])
            ->assertStatus(400);
    }

    public function testUpdateOperation()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('PATCH', '/api/values', [
            "456" => "def",
        ]);

        $response
            ->assertJson([
                'status' => 400
            ])
            ->assertStatus(400);
    }

    public function testEmptyDataUpdateOperation()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('PATCH', '/api/values', []);

        $response
            ->assertJson([
                'status' => 400
            ])
            ->assertStatus(400);
    }

    public function testGetAll()
    {
        $response = $this->json('GET', '/api/values');

        $response
            ->assertJson([
                'status' => 200
            ])
            ->assertOk();
    }

    public function testGetByKeys()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('GET', '/api/values?keys=123,xyz,abc');

        $response
            ->assertJson([
                'status' => 200
            ])
            ->assertOk();
    }
}
