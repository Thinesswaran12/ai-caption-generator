<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiCaptionController extends Controller
{
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'topic' => ['required', 'string', 'max:255'],
            'platform' => ['required', 'string', 'max:50'],
            'tone' => ['required', 'string', 'max:50'],
        ]);

        $prompt = "Generate exactly 3 short social media captions for {$validated['platform']} about {$validated['topic']} in a {$validated['tone']} tone. Return each caption on a new line only, with no numbering and no extra explanation.";

        $response = Http::withToken(env('GROQ_API_KEY'))
            ->timeout(30)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful social media copywriting assistant.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ],
                ],
                'temperature' => 0.8,
            ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to generate captions.',
                'error' => $response->json(),
            ], 500);
        }

        $content = data_get($response->json(), 'choices.0.message.content', '');

        $captions = collect(preg_split("/\r\n|\n|\r/", $content))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(fn ($line) => preg_replace('/^\d+[\).\-\s]+/', '', $line))
            ->values()
            ->take(3);

        return response()->json([
            'captions' => $captions,
        ]);
    }
}