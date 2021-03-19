<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Jobs\SendEmail;
use App\Models\Attachment;
use Illuminate\Http\Response;
use App\Http\Requests\EmailRequest;
use Illuminate\Support\Facades\Storage;

class SendEmailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\EmailRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(EmailRequest $request)
    {
        $email = Email::create($request->all());

        $emailAttachments = [];
        if ($request->has('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                Storage::putFileAs('public/files/', $attachment, $attachment->getClientOriginalName());
                $filepath = 'public/files/' . $attachment->getClientOriginalName();

                $attachmentFilePaths[] = $filepath;
                $emailAttachments[] = new Attachment(['filepath' => $filepath]);
            }
        }

        $email->attachments()->saveMany($emailAttachments);

        SendEmail::dispatch($email);

        return response()->json([
            'message' => 'Email queued to be sent.'
        ], Response::HTTP_ACCEPTED);
    }
}
