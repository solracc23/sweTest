<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\StudentParent;
use App\Models\StudentTaskCompleted;
use App\Models\Subject;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Random\RandomException;

class CodeController extends Controller
{
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('code');
    }

    /**
     * @throws RandomException
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:10',
        ]);


        $number = random_int(10000000, 99999999);
        $data['code'] = $number;
        $data['used'] = 0;

        $code = Code::create($data);

        return redirect()->route('settings')->with('success', 'Code "' . $data['name'] . '" mit Rolle "' . $data['role'] .  '" erfolgreich erstellt!' );
    }

    public function destroy(int $code_id): \Illuminate\Http\RedirectResponse
    {
        $code = Code::findOrFail($code_id);
        $code->delete();

        return redirect()->back()->with('success', 'Code erfolgreich gelöscht!');
    }

    public function show(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $codes = Code::all();
        $subjects = Subject::all();

        // Lernfortschritt berechnen
        $totalMathTasks = Task::where('subjectName', 'mathe')->count();
        $completedTasks = StudentTaskCompleted::where('userID', Auth::id())->count();
        $progressPercent = $totalMathTasks > 0 ? round(($completedTasks / $totalMathTasks) * 100) : 0;

        $progressData = [
            'total' => $totalMathTasks,
            'completed' => $completedTasks,
            'percent' => $progressPercent,
        ];

        // Verknüpfte Kinder für Eltern laden (mit Lernfortschritt)
        $linkedChildren = [];
        if (Auth::user()->role === 'eltern') {
            $linkedChildren = StudentParent::where('parentID', Auth::id())
                ->with('student')
                ->get()
                ->map(function ($relation) use ($totalMathTasks) {
                    $student = $relation->student;
                    $completedByChild = StudentTaskCompleted::where('userID', $student->id)->count();
                    $childPercent = $totalMathTasks > 0 ? round(($completedByChild / $totalMathTasks) * 100) : 0;

                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'progress' => [
                            'completed' => $completedByChild,
                            'total' => $totalMathTasks,
                            'percent' => $childPercent,
                        ],
                    ];
                });
        }


        if(Auth::user()->role == 'admin'){
            return view('settings', ['codes' => $codes, 'subjects' => $subjects, 'progress' => $progressData, 'linkedChildren' => $linkedChildren]);
        }else {
            return view('settings', ['subjects' => $subjects, 'progress' => $progressData, 'linkedChildren' => $linkedChildren]);
        }
    }

    /**
     * Verknüpft ein Kind mit dem eingeloggten Elternteil
     */
    public function linkChild(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'student_code' => 'required|integer',
        ]);

        $parentId = Auth::id();

        // Schüler-Code in User-ID umwandeln
        $studentId = User::decodeStudentCode($data['student_code']);

        // Prüfen ob der Schüler existiert und die Rolle "schüler" hat
        $student = User::where('id', $studentId)->where('role', 'schüler')->first();

        if (!$student) {
            return redirect()->route('settings')->with('failure', 'Kein Schüler mit diesem Code gefunden. Bitte überprüfen Sie den Code.');
        }

        // Prüfen ob bereits verknüpft
        $existing = StudentParent::where('parentID', $parentId)
            ->where('studentID', $studentId)
            ->first();

        if ($existing) {
            return redirect()->route('settings')->with('failure', 'Dieses Kind ist bereits mit Ihrem Konto verknüpft.');
        }

        // Verknüpfung erstellen
        StudentParent::create([
            'parentID' => $parentId,
            'studentID' => $studentId,
        ]);
        return redirect()->route('settings')->with('success', 'Kind "' . $student->name . '" erfolgreich verknüpft!');
    }

    /**
     * Entfernt die Verknüpfung eines Kindes
     */
    public function unlinkChild(int $studentId): \Illuminate\Http\RedirectResponse
    {
        $parentId = Auth::id();

        StudentParent::where('parentID', $parentId)
            ->where('studentID', $studentId)
            ->delete();

        return redirect()->route('settings')->with('success', 'Verknüpfung erfolgreich entfernt.');
    }
}
