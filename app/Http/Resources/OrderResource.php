<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id'            => $this->id,
            'doctor_id'     => $this->doctor_id,
            'doctor'        => $this->doctor->name,
            'color_id'      => $this->color_id,
            'color'         => $this->color->name,
            'user_id'       => $this->user_id,
            'user'          => $this->user->name,
            'type_id'       => $this->type_id,
            'type'          => $this->type->name,
            'patient_no'    => 'MH' . $this->id,
            'patient_name'  => $this->patient_name,
            'required_date' => $this->required_date,
            'notes'         => $this->notes,
        ];
    }
}
