<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
{
    return [
        'id' => $this->id,
        'tanggal' => $this->tanggal,
        'jam_masuk' => $this->jam_masuk,
        'jam_pulang' => $this->jam_pulang,
        'lat_masuk' => $this->lat_masuk,
        'lon_masuk' => $this->lon_masuk,
        'lat_pulang' => $this->lat_pulang,
        'lon_pulang' => $this->lon_pulang,
        'kegiatan' => $this->kegiatan,
    ];
}
}
