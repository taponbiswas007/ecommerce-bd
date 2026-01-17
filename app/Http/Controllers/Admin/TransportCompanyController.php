<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransportCompany;

class TransportCompanyController extends Controller
{
    public function index()
    {
        $companies = TransportCompany::paginate(25);
        return view('admin.transport-companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.transport-companies.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $data = $request->only(['name', 'slug', 'contact']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        TransportCompany::create($data);
        return redirect()->route('admin.transport-companies.index')->with('success', 'Transport company created.');
    }

    public function edit(TransportCompany $transportCompany)
    {
        return view('admin.transport-companies.edit', ['company' => $transportCompany]);
    }

    public function update(Request $request, TransportCompany $transportCompany)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $data = $request->only(['name', 'slug', 'contact']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $transportCompany->update($data);
        return redirect()->route('admin.transport-companies.index')->with('success', 'Updated');
    }

    public function destroy(TransportCompany $transportCompany)
    {
        $transportCompany->delete();
        return redirect()->route('admin.transport-companies.index')->with('success', 'Deleted');
    }
}
