<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'email' => $this->email,
            'address' => $this->address,
            'state' => $this->state,
            'city' => $this->city,
            'postalCode' => $this->postal_code,  // custormize fieldnames in response because of json camelcase naming convention
            'invoices' => InvoiceResource::collection($this->whenLoaded('invoices')), // only when added to the response only when requesting. otherwise not include
        ];
    }
}
