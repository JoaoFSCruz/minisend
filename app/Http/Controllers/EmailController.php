<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('searchQuery')) {
            $searchQuery = $request->input('searchQuery');
            $regex = '/[a-z]*:"(.*?)"|[a-z]*:([^\s]+)/';
            $regexResult = null;
            preg_match_all($regex, $searchQuery, $regexResult);

            $data = [];
            // Get only first iteration of regex application
            foreach ($regexResult[0] as $result) {
                $aux = explode(':', $result);
                $data[strtolower($aux[0])] = $aux[1];
            }

            if (isset($data['subject'])) {
                $data['subject'] = str_replace(['\\', '"'], '', $data['subject']);
            }

            $filteredEmails = Email::with('attachments')
                ->where([
                    [ 'sender', 'like', isset($data['from']) ? '%' . $data['from'] . '%' : '%' ],
                    [ 'recipient', 'like', isset($data['to']) ? '%'. $data['to'] . '%' : '%' ],
                    [ 'subject', 'like', isset($data['subject']) ? '%'. $data['subject'] . '%' : '%' ]
                ])
                ->orderByDesc('created_at')
                ->get();

            return response()->json($filteredEmails, Response::HTTP_OK);
        }

        $emails = Email::with('attachments')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($emails, Response::HTTP_OK);
    }
}
