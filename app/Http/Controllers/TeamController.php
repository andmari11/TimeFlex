<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index() {
        $section = auth()->user()->role === 'employee' ? auth()->user()->section : null;
        $sectionEmployees = $section ? $section->users()->paginate(9) : collect();

        $employeesCollection = auth()->user()->company->employees()->get();
        $employees = $employeesCollection->isEmpty() ? collect([]) : auth()->user()->company->employees()->paginate(12);
        return view('equipo', compact('section', 'employees', 'sectionEmployees'));
    }


    public function indexAdminTeam($id) {
        $section = auth()->user()->company->sections()->where('id', $id)->first();

        if (!$section) {
            abort(404);
        }

        $sectionEmployees = $section->users()->paginate(9);

        $employeesCollection = auth()->user()->company->employees()->get();
        $employees = $employeesCollection->isEmpty() ? collect([]) : auth()->user()->company->employees()->paginate(12);

        return view('equipo', compact('section', 'employees', 'sectionEmployees'));
    }
}
