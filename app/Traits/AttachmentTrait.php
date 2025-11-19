<?php

namespace App\Traits;

use App\Models\Attachment;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\AttachmentLabel;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

trait AttachmentTrait
{
    function store_attachment($attachment, $model, $attachment_label_id = null, $folder_name = "attachments", $user_id = null)
    {
        if (is_array($attachment)) {
            foreach ($attachment as $file) {
                $this->store_attachment($file, $model, $attachment_label_id, $folder_name, $user_id);
            }
        } else {
            $attachment_label = AttachmentLabel::find($attachment_label_id);
            $folder_type = $folder_name;
            if ($attachment_label) {
                $folder_name = $attachment_label->label;
                $folder_type = $attachment_label->type;
            }
            
            $user_id = $user_id ?? auth()->user()->id ?? null;
            // $file_extintion = $attachment_label->placeholder_en . '.'.$attachment->getClientOriginalExtension();
            $file_name = $attachment_label->placeholder_en . '.' . $attachment->getClientOriginalExtension();//. ' ' . $attachment->getClientOriginalName();
            $user_file_name = strtolower(str_replace([' ', '\'', '(', ')'], ['_', '', '', ''], $file_name));//str_replace(' ', '_', $attachment->getClientOriginalName());
            $folder_name = str_replace(' ', '_', $folder_name);
            $fileName = str_replace('.', '', microtime(true)) . '_' . $user_file_name; //.'.'.$attachment->getClientOriginalExtension();
            $folderType = str_replace(' ', '_', $folder_type);
            $model_id = $model->id ?? null;

            $path = $user_id ? "public/users/$user_id/$folder_name" : "public/$folderType/$model_id/$folder_name";
            try {
                // Attempt to upload to S3
                $s3_able = App::environment() == 'production' ? 's3' : '';
                $attachment->storeAs($path, $fileName, $s3_able);
                return $this->insert_attachment($fileName, $path, $model, $user_id, $attachment_label->id ?? null);
                // return response()->json(['status' => 'success', 'message' => 'File uploaded to S3'], 200);
            } catch (AwsException $e) {
                // On failure, save to local server
                $attachment->move($path, $fileName);
                Log::error('Failed uploading to s3. File uploaded to local server :' . $path );
                return $this->insert_attachment($fileName, $path, $model, $user_id, $attachment_label->id ?? null);
                // return response()->json(['status' => 'failure', 'message' => 'File uploaded to local server'], 200);
            }

            // $label = AttachmentLabel::find($attachment_label_id)->select('label'); //retrieve the attachment label

            // Store Files in Storage Folder
            // $attachment->storeAs($path, $fileName);
            //   // storage/app/files/file.pdf

            // Store Files in Public Folder
            // $file->move(public_path('files'), $fileName);
            //   // public/files/file.pdf

            // Store Files in S3
            // $file->storeAs('files', $fileName, 's3');

        }
    }

    function insert_attachment($fileName, $path, $model, $user_id, $attachment_label_id)
    {
        $attachment_db = $model->attachments()->create([
            'name' => $fileName,
            'user_id' => $user_id,
            'path' => $path,
            'attachment_label_id' => $attachment_label_id ?? null,
        ]);

        return $attachment_db;
    }

    function delete_attachment($model, $id)
    {
        $attachment_db = $model->attachments()->where('attachment_label_id', $id)->first();
        if ($attachment_db) {
            $attachment_db->delete();
        } else {
            return;
        }
    }

    function update_attachment($attachment, $model, $attachment_label_id = null, $folder_name = "attachments", $user_id = null)
    {
        $this->delete_attachment($model, $attachment_label_id);
        $this->store_attachment($attachment, $model, $attachment_label_id, null, $user_id);
    }

    protected function attachments_validator(array $attachments, $model = null)
    {
        // dd($attachments);
        //type is table, model is model_name
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        if (isset ($trace[1]['class'])) {
            $model_name = $trace[1]['class'];
            $model_name = explode('\\', $model_name);
            $model_name = array_pop($model_name);
            $model_name = str_replace("Controller", "", $model_name);
            $model_name = str_replace("Register", "User", $model_name);
            $table = app('App\Models\\' . $model_name)->getTable();
        }
        return $validator = Validator::make(
            $attachments,
            [
                'attachments' => [
                    'required',
                    'array',
                    'min:1',
                    function ($attribute, $value) use ($table, $model) { //check the required attachments
                        if ($model) { //an instance of the model will be sent so i can know it's an update and ignore this validation part
                            return;
                        }
                        $attachment_labels = AttachmentLabel::where(['type' => $table, 'is_required' => '1'])->get();
                        foreach ($attachment_labels as $label) {
                            if (!array_key_exists($label->id, $value)) {
                                $this->failedValidation(trans("translation.The-label-is-required-in-attribute with id: id", ["label" => trans('validation.labels.' . $label->label), "id" => $label->id, "attribute" => trans('validation.attributes.' . $attribute)]));
                            }
                        }
                    },
                    function ($attribute, $value) use ($table) { //check if there's wrong label ids >> not related attachments 
                        $attachment_labels = AttachmentLabel::where('type', $table)->pluck('id');
                        $keys = array_keys($value);
                        if (!($attachment_labels->intersect($keys)->count() == count($keys))) {
                            $required_attachments = AttachmentLabel::where('type', $table)->get();
                            $formattedAttachments = []; //for displaying the error message
                            foreach ($required_attachments as $attachment) {
                                $formattedAttachments[] = $attachment->id . '- ' . trans('validation.labels.' . $attachment->label);
                            }
                            $formattedAttachments = implode("  ", $formattedAttachments);
                            $this->failedValidation(trans("translation.Please-enter-a-valid-key.-The-allowed-attachments-are:-required_attachments", ["required_attachments" => $formattedAttachments]));
                            // $fail(trans("translation.Please-enter-a-valid-key.-The-allowed-attachments-are:-required_attachments", ["required_attachments" => $formattedAttachments]));
                        }
                    },
                ],
                'attachments.*' => [ //checks each attachment extension
                    function ($attribute, $value) {
                        $index = Str::afterLast($attribute, '.');
                        $this->validateExtension($index, $value);

                    }
                ],
            ],
            ['required' => __("translation.The-attachments-are-required"),]
        );
    }

    public function validateExtension($label_id, $value)
    {
        $attachment_label = AttachmentLabel::find($label_id);
        $allowedExtensions = $attachment_label->extensions;
        if (is_array($value)) { //if its array of attachment for the same label 
            foreach ($value as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                if (!in_array($extension, $allowedExtensions)) {
                    // return false;
                    $this->failedValidation(trans("translation.Invalid-file-extension-in-attachment_label.-Allowed-extensions-are: ", ["attachment_label" => trans('validation.labels.' . $attachment_label->label)]) . implode(', ', $allowedExtensions));
                }
            }
        } else { //if it was a file and not array of attachments
            $extension = strtolower($value->getClientOriginalExtension());
            if (!in_array($extension, $allowedExtensions)) {
                // return false;
                $this->failedValidation(trans("translation.Invalid-file-extension-in-attachment_label.-Allowed-extensions-are: ", ["attachment_label" => trans('validation.labels.' . $attachment_label->label)]) . implode(', ', $allowedExtensions));
            }
        }
        return true;
    }

    public function attachment_url_response_shape($attachments, $model)
    {
        $attachments_url = $attachments->transform(function ($item) {
            return [
                'attachment_id' => $item->id,
                'attachment_label_id' => $item->attachment_label->id,
                'label_ar' => $item->attachment_label->placeholder_ar ?? '',
                'label_en' => $item->attachment_label->placeholder_en ?? '',
                'value' => $item->url,
                'name' => $item->name ?? '-',
            ];
        })->sortBy('attachment_label_id')->values();
        $model->unsetRelation('attachments');
        return $attachments_url;
    }

    protected function failedValidation($message)
    {
        throw ValidationException::withMessages(
            [
                'attachments' => $message
            ]
        );
    }

    public function storeAnswerFile($fileRequest, $answer, $model)
    {
        if (!is_array($fileRequest)) {
            $attachment = $this->store_attachment($answer, $model, AttachmentLabel::ANSWER_LABEL, $model->user_id);
            $attachments[] = $attachment->id;
            // return $attachments = json_encode($attachments);
            return $attachments;
        } else {
            $attachments = [];
            foreach ($fileRequest as $file) {
                // Logic for storing each attachment
                $attachment = $this->store_attachment($file, $model, AttachmentLabel::ANSWER_LABEL, $model->user_id);
                $attachments[] = $attachment->id;
            }
            return $attachments;
            // return $attachments = json_encode($attachments);
        }
    }
}
