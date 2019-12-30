<?php

namespace App\Http\Requests;

use App\Models\Value;
use Illuminate\Foundation\Http\FormRequest;

class StoreValuesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $data = $this->all();

        $ttl = config('app.ttl', 5);
        $expiresAt = now()->addMinutes($ttl);

        $store = collect();
        foreach($data as $key => $value){
            $store->push([
                'key' => $key,
                'value' => $value,
                'expires_at' => $expiresAt
            ]);
        }

        $this->getInputSource()->replace($store->toArray());
    }
}
