<?php

namespace App\Http\Controllers;

use App\Models\ModelChange;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModelChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {

        $search = $request->input('search');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $nombre = $request->input('nombre') ?? 50;
        if (!is_numeric($nombre)) {
            $nombre = 50;
        }
        if ($search || $start_date || $end_date) {
            $modelChanges = ModelChange::query();

            if ($search) {
                $modelChanges->whereHas('user', function ($query) use ($search) {
                    $query->where('first_name', 'ILIKE', "%{$search}%")
                        ->orWhere('last_name', 'ILIKE', "%{$search}%");
                })
                    ->orWhere('model_type', 'ILIKE', "%{$search}%")
                    ->orWhere('before', 'ILIKE', "%{$search}%")
                    ->orWhere('after', 'ILIKE', "%{$search}%")
                    ->orWhere('event', 'ILIKE', "%{$search}%");
            }

            if ($start_date) {
                $modelChanges->where('created_at', '>=', $start_date);
            }

            if ($end_date) {
                $modelChanges->where('created_at', '<=', $end_date);
            }

            $modelChanges = $modelChanges->orderBy('created_at', 'desc')
                ->paginate($nombre);

        } else {
            $modelChanges = ModelChange::orderBy('created_at', 'desc')
                ->paginate($nombre);

        }

        return view('model_changes.index', ['modelChanges' => $modelChanges]);
    }


}
