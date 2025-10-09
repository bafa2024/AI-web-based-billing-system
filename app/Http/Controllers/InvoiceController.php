<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SerialNumber;
use App\Models\Client;
use App\Models\Product;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        $invoices = Invoice::with('client')->orderBy('created_at', 'desc')->get();
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $clients = Client::all();
        $products = Product::all();
        return view('invoices.create', compact('clients', 'products'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*' => 'required|exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'serial_numbers' => 'nullable|array',
            'serial_numbers.*' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated) {
            // Calculate totals (assuming prices are GST-inclusive)
            $totalInclusiveAmount = 0;
            $products = Product::whereIn('id', $validated['products'])->get()->keyBy('id');
            
            foreach ($validated['products'] as $index => $productId) {
                $product = $products[$productId];
                $quantity = $validated['quantities'][$index];
                $totalInclusiveAmount += $product->price * $quantity;
            }

            $gstRate = 0.05; // 5% GST
            $gstDivisor = 1 + $gstRate; // 1.05 for 5% GST
            
            // Calculate subtotal (GST-exclusive) from GST-inclusive total
            $subtotal = round($totalInclusiveAmount / $gstDivisor, 2);
            $gstAmount = round($subtotal * $gstRate, 2);
            $total = round($subtotal + $gstAmount, 2);

            // Create invoice without invoice_number first
            $invoice = Invoice::create([
                'client_id' => $validated['client_id'],
                'invoice_date' => $validated['invoice_date'],
                'subtotal' => $subtotal,
                'gst_amount' => $gstAmount,
                'total' => $total,
                'status' => 'draft',
                'notes' => $validated['notes'],
            ]);

            // Generate and assign invoice number based on ID
            $invoiceNumber = 'INV-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT);
            $invoice->update(['invoice_number' => $invoiceNumber]);

            // Create invoice items and serial numbers
            foreach ($validated['products'] as $index => $productId) {
                $product = $products[$productId];
                $quantity = $validated['quantities'][$index];
                // Convert GST-inclusive price to GST-exclusive unit price
                $unitPrice = round($product->price / $gstDivisor, 2);
                $totalPrice = round($unitPrice * $quantity, 2);

                $invoiceItem = InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                // Create serial numbers if provided
                if (!empty($validated['serial_numbers'][$index])) {
                    $serialNumbers = array_map('trim', explode(',', $validated['serial_numbers'][$index]));
                    foreach ($serialNumbers as $serialNumber) {
                        if (!empty($serialNumber)) {
                            SerialNumber::create([
                                'invoice_item_id' => $invoiceItem->id,
                                'serial_number' => $serialNumber,
                            ]);
                        }
                    }
                }
            }
        });

        return redirect('/invoices')->with('success', 'Invoice created successfully!');
    }

    /**
     * Display the specified invoice.
     */
    public function show($id)
    {
        $invoice = Invoice::with(['client', 'invoiceItems.product', 'invoiceItems.serialNumbers'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit($id)
    {
        $invoice = Invoice::with(['invoiceItems.product', 'invoiceItems.serialNumbers'])->findOrFail($id);
        $clients = Client::all();
        $products = Product::all();
        return view('invoices.edit', compact('invoice', 'clients', 'products'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*' => 'required|exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'serial_numbers' => 'nullable|array',
            'serial_numbers.*' => 'nullable|string',
            'status' => 'required|in:draft,sent,paid,overdue',
            'notes' => 'nullable|string|max:1000',
        ]);

        $invoice = Invoice::findOrFail($id);

        DB::transaction(function () use ($invoice, $validated) {
            // Calculate totals (assuming prices are GST-inclusive)
            $totalInclusiveAmount = 0;
            $products = Product::whereIn('id', $validated['products'])->get()->keyBy('id');
            
            foreach ($validated['products'] as $index => $productId) {
                $product = $products[$productId];
                $quantity = $validated['quantities'][$index];
                $totalInclusiveAmount += $product->price * $quantity;
            }

            $gstRate = 0.05; // 5% GST
            $gstDivisor = 1 + $gstRate; // 1.05 for 5% GST
            
            // Calculate subtotal (GST-exclusive) from GST-inclusive total
            $subtotal = round($totalInclusiveAmount / $gstDivisor, 2);
            $gstAmount = round($subtotal * $gstRate, 2);
            $total = round($subtotal + $gstAmount, 2);

            // Update invoice
            $invoice->update([
                'client_id' => $validated['client_id'],
                'invoice_date' => $validated['invoice_date'],
                'subtotal' => $subtotal,
                'gst_amount' => $gstAmount,
                'total' => $total,
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);

            // Delete existing items and serial numbers
            $invoice->invoiceItems()->delete();

            // Create new invoice items and serial numbers
            foreach ($validated['products'] as $index => $productId) {
                $product = $products[$productId];
                $quantity = $validated['quantities'][$index];
                // Convert GST-inclusive price to GST-exclusive unit price
                $unitPrice = round($product->price / $gstDivisor, 2);
                $totalPrice = round($unitPrice * $quantity, 2);

                $invoiceItem = InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                // Create serial numbers if provided
                if (!empty($validated['serial_numbers'][$index])) {
                    $serialNumbers = array_map('trim', explode(',', $validated['serial_numbers'][$index]));
                    foreach ($serialNumbers as $serialNumber) {
                        if (!empty($serialNumber)) {
                            SerialNumber::create([
                                'invoice_item_id' => $invoiceItem->id,
                                'serial_number' => $serialNumber,
                            ]);
                        }
                    }
                }
            }
        });

        return redirect('/invoices')->with('success', 'Invoice updated successfully!');
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect('/invoices')->with('success', 'Invoice deleted successfully!');
    }

    /**
     * Download invoice as PDF.
     */
    public function downloadPdf($id)
    {
        $invoice = Invoice::with(['client', 'invoiceItems.product', 'invoiceItems.serialNumbers'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        $filename = 'Invoice-' . ($invoice->invoice_number ?? $invoice->id) . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Email invoice as PDF attachment.
     */
    public function emailInvoice($id)
    {
        $invoice = Invoice::with(['client', 'invoiceItems.product', 'invoiceItems.serialNumbers'])
            ->findOrFail($id);

        // Generate PDF
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        // Send email
        Mail::to($invoice->client->email)->send(new InvoiceMail($invoice, $pdf->output()));
        
        return redirect()->back()->with('success', 'Invoice has been sent to ' . $invoice->client->email . ' successfully!');
    }
}
