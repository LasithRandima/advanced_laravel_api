<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Controllers\Controller; // we have to manually import when namespaces changing
use App\Http\Resources\V1\CustomerResource;
use App\Http\Resources\V1\CustomerCollection;
use Illuminate\Http\Request;
use App\Services\V1\CustomerQuery;

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

        $filter = new CustomerQuery(); // creating custom class called CustomerQuery & create instance to filter things
        $queryItems = $filter->transform($request); // transform method gives us array of query parameters to pass model eloquent

        // Customer::where([['column', 'operator', 'value'],['column', 'operator', 'value']]); // array of query parameters look like this

        if (count($queryItems) == 0){
            return new CustomerCollection(Customer::paginate());
        } else {
            return new CustomerCollection(Customer::where($queryItems)->paginate());
        }


        Customer::where($queryItems);


        return new CustomerCollection(Customer::paginate());
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
    public function store(StoreCustomerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // return $customer;
        return new CustomerResource($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
