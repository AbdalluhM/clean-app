<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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

            "categoryNameArabic" => $this->name_ar,
            "categoryNameEnglish" => $this->name_en,
            "categoryImage " => $this->category_image_path,
            "description" => $this->desc,

        ];
    }
}
