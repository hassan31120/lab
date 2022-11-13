<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => (double) $this->price,
            'amount' => $this->amount,
            'total_price' => $this->total_price,
            'provider' => $this->provider->name,
            'created_at' => $this->created_at->diffForhumans(),
        ];
    }
}
