<?php

namespace App\Http\Controllers;

use _PHPStan_781aefaf6\Nette\Neon\Exception;
use App\Models\Category;
use App\Models\MathTask;
use App\Models\StudentTaskCompleted;
use App\Models\Subject;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\isFalse;

class TaskController extends Controller
{
    /**
     * Zeigt die Verwaltungsseite für Aufgaben und Kategorien an
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $tasks = Task::orderBy('taskID', 'desc')->get();
        $categories = Category::orderBy('category_name')->get();
        $subjects = Subject::all();

        return view('verwaltung', compact('tasks', 'categories', 'subjects'));
    }

    /**
     * Löscht eine Aufgabe
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $task = Task::where('taskID', $id)->firstOrFail();
            if ($task->subjectName != 'mathe') {
                $content = is_array($task->content) ? $task->content : json_decode($task->content, true);
                if (isset($content['path'])) {
                    // Extrahiere nur den Dateinamen aus dem Pfad (pdfs/filename.pdf -> filename.pdf)
                    $filename = basename($content['path']);
                    Storage::disk('public_pdfs')->delete($filename);
                }
            }

            $task->delete();

            return redirect()->route('verwaltung')->with('success', 'Aufgabe erfolgreich gelöscht!');
        } catch (\Exception $exception) {
            return redirect()->route('verwaltung')->with('failure', 'Fehler beim Löschen der Aufgabe: ' . $exception->getMessage());
        }
    }

    /**
     * Zeigt die Baukasten-Seite an
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $categories = Category::forSubject('mathe');
        return view('baukasten', compact('categories'));
    }

    /**
     * Serialisiert eine Aufgabe, speichert sie in der Datenbank und gibt sie zurück
     */
    public function serializeMathTask(Request $request): JsonResponse
    {
        $data = $request->validate([
            'description' => 'nullable|string|max:500',
            'expression' => 'required|string|max:255',
            'tokens' => 'required|array|min:1',
            'tokens.*' => 'required|string',
            'gap_index' => 'required|integer|min:0',
            'week' => 'nullable|integer|min:1|max:52',
            'category_name' => 'required|string|exists:category,category_name',
        ]);

        // Prüfen ob gap_index gültig ist
        if ($data['gap_index'] >= count($data['tokens'])) {
            return response()->json([
                'success' => false,
                'error' => 'Ungültiger Lücken-Index',
            ], 400);
        }

        // MathTask erstellen
        $mathTask = MathTask::fromRequest($data);
        $taskData = $mathTask->jsonSerialize();

        // JSON-Content für die Datenbank
        $contentForDb = [
            'description' => $taskData['description'],
            'expression' => $taskData['expression'],
            'tokens' => $taskData['tokens'],
            'gap_index' => $taskData['gap_index'],
            'correct_answer' => $taskData['correct_answer'],
            'week' => $taskData['week'],
        ];

        // In Datenbank speichern
        Task::create([
            'subjectName' => 'mathe',
            'week' => $data['week'] ?? null,
            'content' => json_encode($contentForDb),
            'category_name' => $data['category_name'],
        ]);

        return response()->json([
            'success' => true,
            'task' => $taskData,
            'message' => 'Aufgabe erfolgreich erstellt und gespeichert!',
        ]);
    }

    /**
     * Zeigt die PDF-Upload-Seite an
     */
    public function pdfUpload(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $subjects = Subject::where('subjectName', '!=', 'mathe')->get();
        $categories = Category::all();

        return view('pdf-upload', compact('subjects', 'categories'));
    }

    /**
     * Speichert ein hochgeladenes PDF
     */
    public function storePdf(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:10240',
            'subject' => 'required|string',
            'category' => 'required|string',
            'week' => 'nullable|integer|min:1|max:52',
        ]);

        $file = $request->file('pdf');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        if (strtolower($extension) !== 'pdf') {
            $baseName = pathinfo($originalName, PATHINFO_FILENAME);
            $filename = time() . '_' . $baseName . '.pdf';
        } else {
            $filename = time() . '_' . $originalName;
        }
        $file->storeAs('pdfs', $filename, 'public');

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile(public_path('storage/pdfs/' . $filename));
        $text = $pdf->getText();


        $maxLength = 15000;
        if (strlen($text) > $maxLength) {
            $text = mb_substr($text, 0, $maxLength, 'UTF-8');
        }
        $content = [
            'path' => 'pdfs/' . $filename,
            'text' => $text,
        ];

        Task::create([
            'subjectName' => strtolower($request->get('subject')),
            'week' => $request['week'] ?? null,
            'content' => json_encode($content),
            'category_name' => $request->get('category'),
        ]);

        return redirect()->route('pdf.upload')->with('success', 'PDF erfolgreich hochgeladen!');
    }

    /**
     * Markiert eine Aufgabe als abgeschlossen für den aktuellen Benutzer
     */
    public function markTaskCompleted(Request $request): JsonResponse
    {
        $data = $request->validate([
            'task_id' => 'required|integer|exists:task,taskID',
        ]);

        $userId = Auth::id();
        $taskId = $data['task_id'];

        // Prüfen ob bereits als abgeschlossen markiert
        $existing = StudentTaskCompleted::where('userID', $userId)
            ->where('taskID', $taskId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Aufgabe war bereits als abgeschlossen markiert.',
            ]);
        }


        StudentTaskCompleted::create([
            'userID' => $userId,
            'taskID' => $taskId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Aufgabe erfolgreich als abgeschlossen markiert!',
        ]);
    }


    /**
     * Markiert eine Aufgabe als abgeschlossen für den aktuellen Benutzer
     */
    public function markTaskCompleted(Request $request): JsonResponse
    {
        $data = $request->validate([
            'task_id' => 'required|integer|exists:task,taskID',
        ]);

        $userId = Auth::id();
        $taskId = $data['task_id'];

        // Prüfen ob bereits als abgeschlossen markiert
        $existing = StudentTaskCompleted::where('userID', $userId)
            ->where('taskID', $taskId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Aufgabe war bereits als abgeschlossen markiert.',
            ]);
        }


        StudentTaskCompleted::create([
            'userID' => $userId,
            'taskID' => $taskId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Aufgabe erfolgreich als abgeschlossen markiert!',
        ]);
    }
}
