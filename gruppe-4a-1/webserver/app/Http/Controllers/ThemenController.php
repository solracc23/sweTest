<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Task;

class ThemenController extends Controller
{
    /**
     * Zeigt die Themen-Seite für das ausgewählte Fach an
     */
    public function index(Request $request)
    {
        // Fach aus Query-Parameter holen
        $fach = $request->query('fach', 'mathe'); // Default: Mathe
        $woche = $request->query('woche');
        // Validierung der erlaubten Fächer
        $erlaubteFaecher = ['mathe', 'deutsch', 'englisch'];
        if (!in_array($fach, $erlaubteFaecher)) {
            return redirect('/fachauswahl')->with('failure', 'Ungültiges Fach ausgewählt.');
        }

        $kategorien = Category::where('subject_name', $fach)->get();
        $themen = $kategorien->pluck('category_name');
        $kategorieBeschreibungen = $kategorien->pluck('description', 'category_name');

        // Aufgaben laden und nach Kategorie gruppieren
        $aufgabenProKategorie = [];
        $pdfsProKategorie = [];

        $tasksQuery = Task::where('subjectName', $fach);
        if ($woche) {
            $tasksQuery->where('week', $woche);
        }
        $tasks = $tasksQuery->get();

        foreach ($tasks as $task) {
            $kategorie = $task->category_name ?? 'sonstiges';

            if ($fach === 'mathe') {
                // Mathe-Aufgaben
                $mathTask = $task->getMathTask();
                if ($mathTask) {
                    $aufgabenProKategorie[$kategorie][] = $mathTask;
                }
            } else {
                // Deutsch/Englisch Aufgaben (PDFs)
                $content = json_decode($task->content, true);

                if (isset($content['path'])) {
                    // Beide Formate unterstützen
                    $pdfsProKategorie[$kategorie][] = (object)[
                        'path' => $content['path'],
                        'task_id' => $task->taskID,
                        'week' => $task->week,
                        'category_name' => $task->category_name,
                        'original_name' => basename($content['path']),
                        // Zusätzlich für getGenericTask() falls existiert
                        'getGenericTask' => method_exists($task, 'getGenericTask')
                            ? $task->getGenericTask()
                            : null,
                    ];
                }
            }
        }

        return view('themen', [
            'fach' => $fach,
            'woche' => $woche,
            'themen' => $themen,
            'aufgabenProKategorie' => $aufgabenProKategorie,
            'pdfsProKategorie' => $pdfsProKategorie,
            'kategorieBeschreibungen' => $kategorieBeschreibungen,
        ]);
    }
}
