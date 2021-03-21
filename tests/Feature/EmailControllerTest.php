<?php

namespace Tests\Feature;

use App\Models\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class EmailControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_all_emails()
    {
        Email::factory()->count(10)->create();

        $response = $this->getJson(route('emails.index'))
            ->assertStatus(Response::HTTP_OK);

        self::assertCount(10, $response->getData());
    }
    
    /**
     * @test
     * @dataProvider validSearchProvider
     */
    public function it_correctly_filters_emails($search)
    {
        Email::factory()->count(5)->create($search['attributes']);
        Email::factory()->count(5)->create();

        $response = $this->getJson(route('emails.index') . '?searchQuery=' . $search['searchQuery'])
            ->assertStatus(Response::HTTP_OK);

        self::assertCount(5, $response->getData());
    }

    /**
     * @test
     * @dataProvider invalidSearchProvider
     */
    public function it_ignores_the_invalid_filters($search)
    {
        Email::factory()->count(5)->create($search['attributes']);
        Email::factory()->count(5)->create();

        $response = $this->getJson(route('emails.index') . '?searchQuery=' . $search['searchQuery'])
            ->assertStatus(Response::HTTP_OK);

        self::assertCount(10, $response->getData());
    }

    public function validSearchProvider()
    {
        return [
            'only sender' => [
                [
                    'searchQuery' => 'from:johndoe@mail.com',
                    'attributes' => [
                        'sender' => 'johndoe@mail.com'
                    ],
                ]
            ],
            'only recipient' => [
                [
                    'searchQuery' => 'to:johndoe@mail.com',
                    'attributes' => [
                        'recipient' => 'johndoe@mail.com'
                    ]
                ]
            ],
            'only subject' => [
                [
                    'searchQuery' => 'subject:"Live Concert"',
                    'attributes' => [
                        'subject' => 'Live Concert'
                    ]
                ]
            ],
            'sender and recipient' => [
                [
                    'searchQuery' => 'from:johndoe@mail.com to:sarahdoe@mail.com',
                    'attributes' => [
                        'sender' => 'johndoe@mail.com',
                        'recipient' => 'sarahdoe@mail.com'
                    ],
                ]
            ],
            'sender, recipient and subject' => [
                [
                    'searchQuery' => 'from:johndoe@mail.com to:sarahdoe@mail.com subject:"Live Concert"',
                    'attributes' => [
                        'sender' => 'johndoe@mail.com',
                        'recipient' => 'sarahdoe@mail.com',
                        'subject' => 'Live Concert'
                    ],
                ]
            ]
        ];
    }

    public function invalidSearchProvider()
    {
        return [
            'sender wrongly defined' => [
                [
                    'searchQuery' => 'fro:johndoe@mail.com',
                    'attributes' => [
                        'sender' => 'johndoe@mail.com'
                    ]
                ]
            ],
            'recipient wrongly defined' => [
                [
                    'searchQuery' => 'tu:johndoe@mail.com',
                    'attributes' => [
                        'recipient' => 'johndoe@mail.com'
                    ]
                ]
            ],
            'subject wrongly defined' => [
                [
                    'searchQuery' => 'subject:Live Concert',
                    'attributes' => [
                        'subject' => 'Live Concert'
                    ]
                ]
            ],
        ];
    }
}
