<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Email;
use App\Jobs\SendEmail;
use App\Mail\GeneralEmail;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendEmailControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Storage::fake();
    }

    /** @test */
    public function it_sends_an_email_when_receiving_a_valid_payload()
    {
        $payload = [
            'sender' => $this->faker->email,
            'recipient' => $this->faker->email,
            'subject' => $this->faker->words(3, true),
            'text' => $this->faker->text,
            'html' => $this->faker->randomHtml(1, 1),
            'attachments' => [
                UploadedFile::fake()->create('test.txt', 100)
            ]
        ];

        $this->postJson(route('api.email.send'), $payload)
            ->assertStatus(Response::HTTP_ACCEPTED);

        Storage::assertExists('public/files/test.txt');

        Mail::assertSent(GeneralEmail::class);
    }

    /**
     * @test
     * @dataProvider invalidEmailRequestsProvider
     */
    public function it_does_not_send_an_email_when_receiving_an_invalid_payload($payload)
    {
        $this->postJson(route('api.email.send'), $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        Mail::assertNotSent(GeneralEmail::class);
    }

    /** @test */
    public function it_stores_the_email_and_attachments_in_the_database()
    {
        $payload = [
            'sender' => $this->faker->email,
            'recipient' => $this->faker->email,
            'subject' => $this->faker->words(3, true),
            'text' => $this->faker->text,
            'html' => '<p>test</p>', // TODO: add faker
            'attachments' => [
                UploadedFile::fake()->create('test.pdf', 100)
            ]
        ];

        $this->postJson(route('api.email.send'), $payload)
            ->assertStatus(Response::HTTP_ACCEPTED);

        $email = Email::with('attachments')->first();
        self::assertNotNull($email);
        self::assertNotNull($email->attachments);
        self::assertCount(1, $email->attachments);
        self::assertEquals($payload['sender'], $email->sender);
        self::assertEquals($payload['recipient'], $email->recipient);
        self::assertEquals($payload['subject'], $email->subject);
        self::assertEquals($payload['text'], $email->text);
        self::assertEquals($payload['html'], $email->html);
        self::assertEquals('Posted', $email->status);
        self::assertEquals(
            'public/files/' . $payload['attachments'][0]->getClientOriginalName(),
            $email->attachments->first()->filepath
        );
    }

    /** @test */
    public function it_correctly_builds_the_email()
    {
        $payload = [
            'sender' => $this->faker->email,
            'recipient' => $this->faker->email,
            'subject' => $this->faker->words(3, true),
            'text' => $this->faker->text,
            'html' => '<p>test</p>', // TODO: add faker
            'attachments' => [
                UploadedFile::fake()->create('test.txt', 100)
            ]
        ];

        $this->postJson(route('api.email.send'), $payload);

        Mail::assertSent(GeneralEmail::class, function ($email) use ($payload) {
            $email->build();

            return $email->hasFrom($payload['sender'])
                && $email->hasTo($payload['recipient'])
                && $email->subject === $payload['subject']
                && $email->viewData['text'] === $payload['text']
                && $email->viewData['html'] === $payload['html'];
        });
    }
    
    /** @test */
    public function it_enqueues_a_job_to_send_an_email_when_receiving_a_valid_payload()
    {
        Queue::fake();

        $payload = [
            'sender' => $this->faker->email,
            'recipient' => $this->faker->email,
            'subject' => $this->faker->words(3, true),
            'text' => $this->faker->text,
            'html' => $this->faker->randomHtml(1, 1),
            'attachments' => [
                UploadedFile::fake()->create('test.txt', 100)
            ]
        ];

        $this->postJson(route('api.email.send'), $payload);

        Queue::assertPushed(SendEmail::class);
    }

    /**
     * @test
     * @dataProvider invalidEmailRequestsProvider
     */
    public function it_does_not_enqueue_a_job_to_send_an_email_when_receiving_an_invalid_payload($payload)
    {
        Queue::fake();

        $this->postJson(route('api.email.send'), $payload);

        Queue::assertNotPushed(SendEmail::class);
    }

    public function invalidEmailRequestsProvider()
    {
        return [
            'empty required values' => [
                [
                    'sender' => '',
                    'recipient' => '',
                    'subject' => '',
                ]
            ],
            'attachment too big' => [
                [
                    'sender' => 'johndoe@mail.com',
                    'recipient' => 'sarahdoe@mail.com',
                    'subject' => 'Music Concert',
                    'text' => 'It\'s tonight!',
                    'html' => '<p>Are you ready?</p>',
                    'attachments' => [
                        UploadedFile::fake()->create('test.pdf', 10000000)
                    ],
                ]
            ],
            'attachments total size too big' => [
                [
                    'sender' => 'johndoe@mail.com',
                    'recipient' => 'sarahdoe@mail.com',
                    'subject' => 'Music Concert',
                    'text' => 'It\'s tonight!',
                    'html' => '<p>Are you ready?</p>',
                    'attachments' => [
                        UploadedFile::fake()->create('test.pdf', 2500),
                        UploadedFile::fake()->create('test2.pdf', 3000),
                        UploadedFile::fake()->create('test3.pdf', 4000),
                        UploadedFile::fake()->create('test3.pdf', 1500)
                    ],
                ]
            ]
        ];
    }
}
