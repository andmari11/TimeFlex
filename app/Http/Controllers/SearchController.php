<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke()
    {
        $query = User::query()
            ->where('name', 'LIKE', '%'.request('q').'%');

        if (auth()->user()->role !== 'admin') {
            $query->where('section_id', auth()->user()->section_id);
        }

        $employees = $query->get();

        return view('results', ['employees' => $employees, 'query' => request('q')]);
    }
}
