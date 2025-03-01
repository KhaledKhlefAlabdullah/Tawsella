<?php

namespace App\Http\Requests\Messages;

use App\Http\Requests\BaseRequest;

class SendMessageRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'sender_id' => getMyId()
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'chat_id' => ['uuid', 'required', 'exists:chats,id'],
            'sender_id' => ['uuid', 'required', 'exists:users,id'],
            'receiver_id' => ['uuid', 'required', 'exists:users,id'],
            'message' => ['sometimes', 'string', 'min:1'],
            'media' => ['sometimes', 'file', 'mimes:png,jpg,gif,jpeg,mp3,mp4,pdf,txt'],
        ];
    }
}
