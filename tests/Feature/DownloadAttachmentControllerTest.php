<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Email;
use App\Models\Attachment;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DownloadAttachmentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_retrieves_the_attachment_download()
    {
        Storage::fake();

        $email = Email::factory()
            ->has(Attachment::factory()->count(1))
            ->create();

        $attachment = $email->attachments->first();

        Storage::putFile(
            $attachment->filepath,
            UploadedFile::fake()->create($attachment->filename, 100)
        );

        $this->get(route('download.attachment') . '?attachment_id=' . $attachment->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('content-disposition', 'attachment; filename=' . $attachment->filename);
    }
}
