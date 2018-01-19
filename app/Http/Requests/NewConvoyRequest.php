<?php

namespace App\Http\Requests;

use App\Models\Convoy;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class NewConvoyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('create', Convoy::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'             => 'max:50',
            'meeting_datetime'  => 'required|date|after:now',
            'leaving_datetime'  => 'required|date|after:meeting_datetime',
            'server_id'         => 'required|exists:servers,id',
            'start_town_id'     => 'required|exists:cities,id',
            'start_place'       => 'required|max:50',
            'finish_town_id'    => 'required|exists:cities,id',
            'finish_place'      => 'required|max:50',
            'stops'             => 'max:190',
            'dlcs'              => 'sometimes|array',
            'dlcs.*'            => 'exists:dlcs,id',
            'route_length'      => 'max:99999|min:0',
            'voice_description' => 'required|max:100',
            'map_url'           => 'nullable|url|active_url|urlmime|max:190',
            'background_url'    => 'nullable|url|active_url|urlmime|max:190',
            'description'       => 'required',
        ];
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return redirect()->back()
            ->with('alert.type', 'error')
            ->with('alert.title', 'Что-то тут не так...')
            ->with('alert.message', 'Исправь ошибки в форме конвоя.')
            ->withErrors($errors)
            ->withInput();
    }
}
