<?php

use App\Mail\StarTaxiMail;
use App\Notifications\StarTaxiNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

if (!function_exists('getAndCheckModelById')) {
    /**
     * Get an Eloquent model instance by its ID and perform a existence check.
     *
     * This helper function retrieves an instance of the specified Eloquent model
     * by its ID. If the instance is not found, a NotFoundResourceException is thrown.
     *
     * @param string $model The fully qualified class name of the Eloquent model.
     * @param mixed $id The ID of the model to retrieve.
     * @return Model The retrieved Eloquent model instance.
     *
     * @throws NotFoundResourceException If the model instance is not found.
     */
    function getAndCheckModelById($model, $id)
    {
        $instance = $model::find($id);

        if (!$instance) {
            throw new NotFoundResourceException($model . 'Not found', 400);
        }

        return $instance;
    }
}


if (!function_exists('get_instances_with_value')) {
    /**
     * This Function it get the instance by any value inside it
     * @param Model $model the model will search about
     * @param mixed $value is any value will pass it and search if it exists in the model
     * @return Model $instance the finded instance
     */
    function get_instances_with_value($model, $value)
    {

        $instance = $model::where('id', $value)->first();
        if (is_null($instance)) {
            // Query the database to retrieve instances where any attribute has the specified value
            $instance = $model::where(function ($query) use ($model, $value) {
                $modelInstance = new $model(); // Create an instance of the model
                $fillableAttributes = $modelInstance->getFillable(); // Get fillable attributes of the model
                foreach ($fillableAttributes as $attribute) {
                    $query->orWhere($attribute, $value); // Add OR condition for each attribute
                }
            })->first();
        }

        return $instance;
    }
}


if (!function_exists('findAndUpdate')) {
    /**
     * This function is update the instance with keys and values passed to it
     * the keys and values lenght most be equale
     * @param Model $model the model will search about
     * @param mixed $search_param the value you will pass to search about it
     * @param mixed $keys the column key you want to update them
     * @param mixed $values the new column values
     * @return Model $instance the updated finded instance
     */
    function findAndUpdate($model, $search_param, $keys, $values)
    {

        // Ensure the number of keys and values match
        if (count($keys) !== count($values)) {
            throw new \InvalidArgumentException('Number of keys and values must be equal');
        }

        // Retrieve the model instance by searching for the value in any attribute
        $instances = get_instances_with_value($model, $search_param);

        // Create an associative array of keys and values
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $values[$key];
        }

        // Update the model attributes with the provided data
        $instances->update($data);

        return $instances; // Return the updated instances
    }
}


if (!function_exists('storeFile')) {
    /**
     * This function is used to store files
     * @param mixed $file the file i want to store it
     * @param string $path the path where i want to save it
     * @return string $path+fileName
     */
    function storeFile($file, $path): string
    {
        // get file extension
        $file_extension = $file->getClientOriginalExtension();

        // rename the file
        $file_name = time() . '.' . $file_extension;

        // store the file in public directory
        $file->move(public_path($path), $file_name);

        // return the path and file name
        return $path . '/' . $file_name;
    }
}


if (!function_exists('editFile')) {
    /**
     * This function is used to update files
     * @param string $old_path the old path where the file sotred
     * @param string $new_path the new path where i want to save the new file
     * @param mixed $file the file i want to store it
     * @return string $new_file_path+fileName
     */
    function editFile($old_path, $new_path, $new_file): string
    {
        // Delete the old file from storage
        if (file_exists($old_path)) {
            if (!in_array($old_path, ['/images/profile/man.png', '/images/profile/woman.png', '/images/services/logos/logo.png', '/images/services/images/service.png']))
                unlink(public_path($old_path));
        }

        // Store the new file
        $new_file_path = storeFile($new_file, $new_path);

        return $new_file_path;
    }
}


if (!function_exists('removeFile')) {
    /**
     * This function is used to remove files
     * @param string $path the path where the file sotred
     * @return mixed success message
     */
    function removeFile($path): string
    {
        $fullPath = public_path($path);
        // Delete the old file from storage
        if (file_exists($fullPath) && is_file($fullPath) && !in_array($path, ['/images/profile/man.png', '/images/profile/woman.png'])) {
            unlink(public_path($path));
            return 'success';
        }

        return 'failed';
    }
}


if (!function_exists('getAdminId')) {
    /**
     * this function return the admin id
     * @return string admin_id
     */
    function getAdminId()
    {
        return \App\Models\User::role(\App\Enums\UserEnums\UserType::Admin()->key)->first()?->id;
    }

}


if (!function_exists('getMyId')) {
    /**
     * Get my Id
     * @return string Auth user id
     */
    function getMyId()
    {
        return Auth::id();
    }
}


if (!function_exists('send_mail')) {
    /**
     * This function is used to email to user
     * @param string $mail_message as message you want to send
     * @param string $receiver as mail or list of mails
     */
    function send_mail($mail_message, $receiver)
    {
        try {

            Mail::to($receiver)->send(new StarTaxiMail($mail_message));

            return true;
        } catch (Exception $e) {
            return api_response(message: 'Cold not sending the email', errors: [$e->getMessage()]);
        }
    }
}


if (!function_exists('count_items')) {
    /**
     * Get Counter
     * @param mixed $model to counst within
     * @param array $validations to check by
     * @return <numeric/JsonResponse> count of items
     */
    function count_items($model, array $validations)
    {
        // withTrashed to count the deleted items to
        $item_count = $model::withTrashed()->where($validations)->get()->count();
        return $item_count;
    }
}

if (!function_exists('send_notifications')) {
    /**
     * Send notifications to receivers.
     *
     * @param  $receiver
     * @param array|string $message
     * @param array $viaChannel
     * @throws Exception
     */
    function send_notifications($receiver, array|string $message, array $viaChannel = ['database'])
    {
        Notification::send($receiver, new StarTaxiNotification($message, $receiver, $viaChannel));
    }
}
