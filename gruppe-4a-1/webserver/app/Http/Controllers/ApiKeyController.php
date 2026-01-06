<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ApiKeyController extends Controller
{
    /**
     * Speichert den LLM API Key in der .env Datei
     */
    public function updateLlmApiKey(Request $request)
    {

        if (Auth::user()->role !== 'admin') {
            return redirect()->route('settings')->with('failure', 'Keine Berechtigung.');
        }

        $request->validate([
            'llm_api_key' => 'required|string|min:10',
        ]);

        $apiKey = $request->input('llm_api_key');

        $this->setEnvValue('LLM_API_KEY', $apiKey);

        return redirect()->route('settings')->with('success', 'LLM API Key erfolgreich gespeichert!');
    }

    /**
     * Prüft eine Antwort über die LLM API
     */
    public function checkAnswerWithLlm(Request $request)
    {
        $request->validate([
            'user_answer' => 'required|string',
            'correct_answer' => 'required|string',
        ]);

        $userAnswer = $request->input('user_answer');
        $correctAnswer = $request->input('correct_answer');

        $apiKey = env('LLM_API_KEY');

        if (empty($apiKey)) {
            return response()->json(['result' => false, 'error' => 'API Key nicht konfiguriert']);
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.llm7.io/v1/chat/completions', [
                'model' => 'default',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Stell dir vor du bist ein Mathematikprofessor. Berechne die gegebene Antwort und prüfe ob das Ergebnis mit der Musterlösung übereinstimmt.  Antworte NUR mit "true" wenn die Antwort korrekt ist, oder "false" wenn sie falsch ist. Keine weiteren Erklärungen.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Musterlösung: {$correctAnswer}\n Gegebene Antwort: {$userAnswer}\n\n "
                    ]
                ],
                'max_tokens' => 1000,
                'temperature' => 0,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                $isCorrect = str_contains(strtolower($content), 'true');
                return response()->json(['result' => $isCorrect]);
            }

            return response()->json(['result' => false, 'error' => 'API Anfrage fehlgeschlagen']);

        } catch (\Exception $e) {
            return response()->json(['result' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Setzt einen Wert in der .env Datei
     */
    private function setEnvValue(string $key, string $value): void
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        // Prüfen ob der Key bereits existiert
        if (preg_match("/^{$key}=.*/m", $envContent)) {
            // Key existiert - ersetzen
            $envContent = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $envContent
            );
        } else {
            // Key existiert nicht - am Ende hinzufügen
            $envContent .= "\n{$key}={$value}";
        }

        file_put_contents($envPath, $envContent);
    }
}
