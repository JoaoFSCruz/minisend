<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Response;

class EmailController extends Controller
{
    public function index()
    {
        $emails = Email::with('attachments')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($emails, Response::HTTP_OK);
    }
}
