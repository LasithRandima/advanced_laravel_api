<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Customer;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Controllers\Controller; // we have to manually import when namespaces changing
use App\Http\Resources\V1\CustomerResource;
use App\Http\Resources\V1\CustomerCollection;
use Illuminate\Http\Request;
use App\Filters\V1\CustomerFilter;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return Customer::all();

        //* Filtering Data Example query parameter in url - customers?postalCode[gt]=30000
        //* Filtering Data Example query parameter in url - customers?name[like]=john
        //* http://127.0.0.1:8000/api/V1/customers?postalCode[gt]=30000&type[eq]=I

        $filter = new CustomerFilter();
        $filterItems = $filter->transform($request); // Customer::where([['column', 'operator', 'value'],['column', 'operator', 'value']]);


        $includeInvoices = $request->query('includeInvoices'); // http://127.0.0.1:8000/api/V1/customers?includeInvoices=true

        $customers = Customer::where($filterItems);

        if($includeInvoices){
            $customers = $customers->with('invoices'); // using relationships attaching data
        }

        return new CustomerCollection($customers->paginate()->appends($request->query()));


    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // return new CustomerResource($customer);

        $includeInvoices = request()->query('includeInvoices'); // http://127.0.0.1:8000/api/V1/customers?includeInvoices=true

        if ($includeInvoices){
            return new CustomerResource($customer->loadMissing('invoices')); //loading related data using relations
            // we already have Customer $customer. so that situation don't want to make queries. just load missing invoices.
        }

        return new CustomerResource($customer);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
