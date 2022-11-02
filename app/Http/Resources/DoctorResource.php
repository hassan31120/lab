<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'id'            =>$this->id,
            'name'          => $this->name,
            'number'        => $this->number,
            'address'       => $this->address,
            'image'         => asset($this->image),
            'created_at'    => $this->created_at,
            'orders'        =>  $this->when(OrderResource::collection($this->orders), function(){
                if (count(OrderResource::collection($this->orders)) > 0) {
                    return OrderResource::collection($this->orders);
                } else {
                    return 'there is no orders!';
                }
            }),
        ];
    }
}
