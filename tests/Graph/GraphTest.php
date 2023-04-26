<?php

namespace Tests\Graph;

use App\Models\GpsData;
use App\Models\User;

class GraphTest extends TestCase
{
    public function testQueryAllUsers(): void
    {
        $users = User::all(['id', 'name', 'email']);
        $response = $this->graphQL(/** @lang GraphQL */ '
    {
        users {
            id
            name
            email
        }
    }
    ')->assertJson([
            'data' => [
                'users' => $users->toArray(),
            ],
        ]);
    }


    public function testQueryUserByEmail()
    {
        $email = User::inRandomOrder()->first(['email'])->email;
        $user = User::where('email', $email)->first(['id', 'name', 'email'])->toArray();
        $response = $this->graphQL(/** @lang GraphQL */ '   
        query ($userEmail: String!) {
            user(email: $userEmail) {
                id
                name
                email
            }
        }   
        ', [
            'userEmail' => $email,
        ])->assertJson([
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function testQueryUserById()
    {
        $userId = User::inRandomOrder()->first(['id'])->id;;
        $user = User::find($userId, ['id', 'name', 'email'])->toArray();
        $response = $this->graphQL(/** @lang GraphQL */ '   
        query ($userId: ID!) {
            user(id: $userId) {
                id
                name
                email
            }
        }   
        ', [
            'userId' => $userId,
        ])->assertJson([
            'data' => ['user' => $user],
        ],
        );

    }


    public function testQueryAllGpsData()
    {
        // get from database all gps data with fields id, latitude, longitude, recorded_at
        $gpsData = GpsData::all(['id', 'latitude', 'longitude', 'recorded_at'])->toArray();
        $response = $this->graphQL(/** @lang GraphQL */ '
        {
            allGpsData {
                id
                latitude
                longitude
                recorded_at
            }
        }
        ')->assertJson([
            'data' => [
                'allGpsData' => $gpsData,
            ],
        ]);
    }

    public function testQueryFirstGpsData()
    {
        $firstGpsData = GpsData::orderBy('recorded_at', 'asc')->first(['id', 'latitude', 'longitude', 'recorded_at'])->toArray();
        $response = $this->graphQL(/** @lang GraphQL */ '
        {
            firstGpsData {
                id
                latitude
                longitude
                recorded_at
            }
        }
        ')->assertJson([
            'data' => [
                'firstGpsData' => $firstGpsData,
            ],
        ]);
    }

    public function testQueryLastGpsData()
    {
        $lastGpsData = GpsData::orderBy('recorded_at', 'desc')->first(['id', 'latitude', 'longitude', 'recorded_at'])->toArray();
        $response = $this->graphQL(/** @lang GraphQL */ '
        {
            lastGpsData {
                id
                latitude
                longitude
                recorded_at
            }
        }
        ')->assertJson([
            'data' => [
                "lastGpsData" => $lastGpsData,
            ],
        ]);
    }


}