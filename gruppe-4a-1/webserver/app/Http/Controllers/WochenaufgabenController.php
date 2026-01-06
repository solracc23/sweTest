<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subject;

class WochenaufgabenController extends Controller
{
    /**
     * Zeigt die Wochenaufgaben-Übersicht mit allen verfügbaren Wochen pro Fach
     */
    public function index()
    {
        $subjects = Subject::all();
        $wochenProFach = [];

        foreach ($subjects as $subject) {
            $wochen = Task::where('subjectName', $subject->subjectName)
                ->whereNotNull('week')
                ->distinct()
                ->orderBy('week')
                ->pluck('week')
                ->toArray();

            if (!empty($wochen)) {
                $wochenProFach[$subject->subjectName] = $wochen;
            }
        }

        return view('wochenaufgaben', compact('wochenProFach'));
    }
}

