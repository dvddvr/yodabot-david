<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            //falta el id de identificador del usuario
            'author' => $this->author,
            'message' => $this->message,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at'  => $this->updated_at
        ];
    }
}
