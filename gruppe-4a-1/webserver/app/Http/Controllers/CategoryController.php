<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:100',
            'subject_name' => 'required|string|exists:subject,subjectName',
            'description' => 'nullable|string|max:1400',
        ]);

        $data['category_name'] = strtolower(trim($data['category_name']));

        // Beschreibung nur für Mathe speichern
        if (strtolower($data['subject_name']) !== 'mathe') {
            unset($data['description']);
        }

        try {
            Category::create($data);
        } catch (\Exception $exception) {
            return redirect()->route('settings')->with('failure', 'Irgendwas ist schief gelaufen: ' . $exception);
        }


        return redirect()->route('settings')->with('success', 'Kategorie "' . $data['category_name'] . '" erfolgreich erstellt!');
    }


    /**
     * Löscht eine Kategorie
     */
    public function destroy(string $categoryName): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $category = Category::where('category_name', $categoryName)->firstOrFail();

            $tasks = Task::where('category_name', $categoryName)->get();
            foreach ($tasks as $task) {
                if ($task->subjectName != 'mathe') {
                    $content = is_array($task->content) ? $task->content : json_decode($task->content, true);
                    if (isset($content['path'])) {
                        Storage::disk('public')->delete($content['path']);
                    }
                }
            }

            Task::where('category_name', $categoryName)->delete();
            $category->delete();

            DB::commit();

            return redirect()->route('verwaltung')->with('success', 'Kategorie "' . $categoryName . '" erfolgreich gelöscht!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route('verwaltung')->with('failure', 'Fehler beim Löschen der Kategorie: ' . $exception->getMessage());
        }
    }
}
