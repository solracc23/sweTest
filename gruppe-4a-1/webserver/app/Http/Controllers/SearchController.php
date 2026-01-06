<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->input('query'));

        if (!$query) {
            return redirect()->back()->with('search_error', 'Bitte gib einen Suchbegriff ein.');
        }

        $searchableContent = config('search.searchable_content', []);

        foreach ($searchableContent as $item) {
            foreach ($item['keywords'] as $keyword) {
                if (strcasecmp($keyword, $query) === 0) {

                    return $this->redirectToResult($item);
                }
            }
        }
        return redirect()->back()->with('search_error', 'Keine Ergebnisse für "' . $query . '" gefunden.');
    }

    private function redirectToResult($item)
    {
        $url = $item['route'];

        if (!empty($item['params'])) {
            $url .= '?' . http_build_query($item['params']);
        }
        return redirect($url);
    }
}
