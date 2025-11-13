<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Client::query();
        
        if ($status === 'active') {
            $query->where('active', true);
        } elseif ($status === 'inactive') {
            $query->where('active', false);
        }
        // 'all' shows everything, so no filter needed
        
        $clients = $query->get()->map(function ($client) {
            // Calculate balance from unpaid invoices
            $balance = \App\Models\Invoice::where('client_id', $client->id)
                ->whereIn('status', ['sent', 'overdue'])
                ->sum('total_amount');
            
            $client->balance = $balance;
            return $client;
        });
        
        return view('clients.index', compact('clients', 'status'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email|max:255',
            'phone' => 'nullable|string|max:20',
            'fax_number' => 'nullable|string|max:20',
            'mobile_number' => 'nullable|string|max:20',
            'web_address' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'customer_code' => 'nullable|string|max:255',
            'active' => 'nullable',
        ]);

        $validated['active'] = isset($validated['active']) ? ($validated['active'] == '1') : true;

        Client::create($validated);

        return redirect('/clients')->with('success', 'Client created successfully!');
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id . '|max:255',
            'phone' => 'nullable|string|max:20',
            'fax_number' => 'nullable|string|max:20',
            'mobile_number' => 'nullable|string|max:20',
            'web_address' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'customer_code' => 'nullable|string|max:255',
            'active' => 'nullable',
        ]);

        $validated['active'] = isset($validated['active']) ? ($validated['active'] == '1') : ($client->active ?? true);

        $client->update($validated);

        return redirect('/clients')->with('success', 'Client updated successfully!');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect('/clients')->with('success', 'Client deleted successfully!');
    }
}
