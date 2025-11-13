<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    /**
     * Display a listing of the quotes.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Quote::with('client');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $quotes = $query->orderBy('created_at', 'desc')->get();
        
        return view('quotes.index', compact('quotes', 'status'));
    }

    /**
     * Show the form for creating a new quote (with client pre-selected).
     */
    public function create(Request $request)
    {
        $clientId = $request->get('client_id');
        $clients = Client::where('active', true)->get();
        $products = Product::all();
        
        return view('quotes.create', compact('clients', 'products', 'clientId'));
    }

    /**
     * Store a newly created quote in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'quote_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*' => 'required|exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'unit_prices' => 'required|array|min:1',
            'unit_prices.*' => 'required|numeric|min:0',
            'descriptions' => 'nullable|array',
            'descriptions.*' => 'nullable|string',
            'status' => 'nullable|in:draft,sent,rejected,approved',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated) {
            $gstRate = 0.05; // 5% GST
            $subtotal = 0;
            
            // Calculate subtotal from items
            foreach ($validated['products'] as $index => $productId) {
                $quantity = $validated['quantities'][$index];
                $unitPrice = $validated['unit_prices'][$index];
                $subtotal += $unitPrice * $quantity;
            }
            
            $gstAmount = round($subtotal * $gstRate, 2);
            $totalAmount = round($subtotal + $gstAmount, 2);

            // Create quote
            $quote = Quote::create([
                'client_id' => $validated['client_id'],
                'quote_date' => $validated['quote_date'],
                'subtotal' => $subtotal,
                'gst_amount' => $gstAmount,
                'total_amount' => $totalAmount,
                'status' => $validated['status'] ?? 'draft',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Generate quote number
            $quoteNumber = Quote::generateQuoteNumber();
            $quote->update(['quote_number' => $quoteNumber]);

            // Create quote items
            foreach ($validated['products'] as $index => $productId) {
                $quantity = $validated['quantities'][$index];
                $unitPrice = $validated['unit_prices'][$index];
                $totalPrice = round($unitPrice * $quantity, 2);
                $description = $validated['descriptions'][$index] ?? null;

                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'description' => $description,
                ]);
            }
        });

        return redirect('/quotes')->with('success', 'Quote created successfully!');
    }

    /**
     * Display the specified quote.
     */
    public function show($id)
    {
        $quote = Quote::with(['client', 'quoteItems.product'])->findOrFail($id);
        return view('quotes.show', compact('quote'));
    }

    /**
     * Show the form for editing the specified quote.
     */
    public function edit($id)
    {
        $quote = Quote::with(['quoteItems.product'])->findOrFail($id);
        $clients = Client::where('active', true)->get();
        $products = Product::all();
        return view('quotes.edit', compact('quote', 'clients', 'products'));
    }

    /**
     * Update the specified quote in storage.
     */
    public function update(Request $request, $id)
    {
        $quote = Quote::findOrFail($id);
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'quote_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*' => 'required|exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'unit_prices' => 'required|array|min:1',
            'unit_prices.*' => 'required|numeric|min:0',
            'descriptions' => 'nullable|array',
            'descriptions.*' => 'nullable|string',
            'status' => 'required|in:draft,sent,rejected,approved',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($quote, $validated) {
            $gstRate = 0.05; // 5% GST
            $subtotal = 0;
            
            // Calculate subtotal from items
            foreach ($validated['products'] as $index => $productId) {
                $quantity = $validated['quantities'][$index];
                $unitPrice = $validated['unit_prices'][$index];
                $subtotal += $unitPrice * $quantity;
            }
            
            $gstAmount = round($subtotal * $gstRate, 2);
            $totalAmount = round($subtotal + $gstAmount, 2);

            // Update quote
            $quote->update([
                'client_id' => $validated['client_id'],
                'quote_date' => $validated['quote_date'],
                'subtotal' => $subtotal,
                'gst_amount' => $gstAmount,
                'total_amount' => $totalAmount,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Delete existing items
            $quote->quoteItems()->delete();

            // Create new quote items
            foreach ($validated['products'] as $index => $productId) {
                $quantity = $validated['quantities'][$index];
                $unitPrice = $validated['unit_prices'][$index];
                $totalPrice = round($unitPrice * $quantity, 2);
                $description = $validated['descriptions'][$index] ?? null;

                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'description' => $description,
                ]);
            }
        });

        return redirect('/quotes')->with('success', 'Quote updated successfully!');
    }

    /**
     * Remove the specified quote from storage.
     */
    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();

        return redirect('/quotes')->with('success', 'Quote deleted successfully!');
    }

    /**
     * Convert quote to invoice.
     */
    public function convertToInvoice($id)
    {
        $quote = Quote::with(['client', 'quoteItems.product'])->findOrFail($id);
        
        $invoice = DB::transaction(function () use ($quote) {
            // Create invoice from quote
            $invoice = Invoice::create([
                'client_id' => $quote->client_id,
                'invoice_date' => now()->toDateString(),
                'subtotal' => $quote->subtotal,
                'gst_amount' => $quote->gst_amount,
                'total_amount' => $quote->total_amount,
                'status' => 'draft',
                'notes' => 'Converted from Quote: ' . $quote->quote_number,
            ]);

            // Generate invoice number
            $invoiceNumber = Invoice::generateInvoiceNumber();
            $invoice->update(['invoice_number' => $invoiceNumber]);

            // Create invoice items from quote items
            foreach ($quote->quoteItems as $quoteItem) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $quoteItem->product_id,
                    'quantity' => $quoteItem->quantity,
                    'unit_price' => $quoteItem->unit_price,
                    'total_price' => $quoteItem->total_price,
                ]);
            }

            // Mark quote as approved (or keep status, but typically converted quotes are approved)
            $quote->update(['status' => 'approved']);
            
            return $invoice;
        });

        return redirect('/invoices/' . $invoice->id)->with('success', 'Quote converted to invoice successfully!');
    }

    /**
     * Search clients for autocomplete (AJAX).
     */
    public function searchClients(Request $request)
    {
        $search = $request->get('q', '');
        
        $clients = Client::where('active', true)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email']);
        
        return response()->json($clients);
    }

    /**
     * Search products for autocomplete (AJAX).
     */
    public function searchProducts(Request $request)
    {
        $search = $request->get('q', '');
        
        $products = Product::where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'price', 'description']);
        
        return response()->json($products);
    }
}
