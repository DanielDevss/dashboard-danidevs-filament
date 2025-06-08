<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index() {
        try {
            $works = Work::latest()
                ->get()
                ->map(fn($record) => [
                    "id" => $record->id,
                    "company" => $record->company,
                    "workstation" => $record->workstation,
                    "init" => $record->init,
                    "finish" => $record->finish
                ]);
                
            return response()->json($works);
        } catch (\Exception $err) {
            return response()->json(["error" => $err->getMessage()], $err->getCode());
        }
    }
}
