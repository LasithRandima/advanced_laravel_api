<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Invoice;
use App\Http\Requests\V1\StoreInvoiceRequest;
use App\Http\Requests\V1\UpdateInvoiceRequest;
use App\Http\Controllers\Controller; // we have to manually import when namespaces changing
use App\Http\Resources\V1\InvoiceResource;
use App\Http\Resources\V1\InvoiceCollection;
use App\Filters\V1\InvoiceFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Requests\V1\BulkStoreInvoiceRequest;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return new InvoiceCollection(Invoice::paginate());

        $filter = new InvoiceFilter();
        $queryItems = $filter->transform($request);

        if (count($queryItems) == 0){
            return new InvoiceCollection(Invoice::paginate());
        } else {
            // return new InvoiceCollection(Invoice::where($queryItems)->paginate());    // problem is paginate not working with filters
            $invoices = Invoice::where($queryItems)->paginate();
            return new InvoiceCollection($invoices->appends($request->query()));   // solution - append request query parameter
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        //
    }

    // Bulk insertion - payload looks like [{customerId:1, status:'B' }, {customerId:2, status:'P' }]

        public function bulkStore(BulkStoreInvoiceRequest $request) {
            $bulk = collect($request->all())->map(function($arr, $key){ // this like js map fuction in php way
                return Arr::except($arr, ['customerId', 'billedDate', 'paidDate']);   //avoiding inserting these filds to request by making collection and convert into php array 
            });

            Invoice::insert($bulk->toArray());
        }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
