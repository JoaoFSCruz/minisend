<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        $query = Email::with('attachments');

        if ($request->filled('searchQuery')) {
            $searchQuery = $request->input('searchQuery');
            $regex = '/[a-z]*:[^\s]*@[^\s]*|[a-z]*:"(.*?)"/';
            $regexResult = null;
            preg_match_all($regex, $searchQuery, $regexResult);

            $data = [];
            // Get only first iteration of regex results
            foreach ($regexResult[0] as $result) {
                $aux = explode(':', $result);
                $data[strtolower($aux[0])] = $aux[1];
            }

            if (isset($data['subject'])) {
                $data['subject'] = str_replace(['\\', '"'], '', $data['subject']);
            }

            $query = $query->where([
                    [ 'sender', 'like', isset($data['from']) ? '%' . $data['from'] . '%' : '%' ],
                    [ 'recipient', 'like', isset($data['to']) ? '%'. $data['to'] . '%' : '%' ],
                    [ 'subject', 'like', isset($data['subject']) ? '%'. $data['subject'] . '%' : '%' ]
                ]);
        }

        $emails = $query->orderByDesc('created_at')->get();

        return response()->json($emails, Response::HTTP_OK);
    }
}
