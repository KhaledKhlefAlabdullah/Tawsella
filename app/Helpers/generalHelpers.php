<?php


use Symfony\Component\Translation\Exception\NotFoundResourceException;

use Illuminate\Database\Eloquent\Model;

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
if (!function_exists('getAndCheckModelById')) {
    function getAndCheckModelById($model, $id)
    {
        $instance = $model::find($id);

        if (!$instance) {
            throw new NotFoundResourceException($model . 'Not found', 400);
        }

        return $instance;
    }
}

/**
 * This Function it get the instance by any value inside it
 * @param Model $model the model will search about 
 * @param mixed $value is any value will pass it and search if it exists in the model
 * @return Model $instance the finded instance 
 */
if (!function_exists('get_instances_with_value')) {
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


/**
 * This function is update the instance with keys and values passed to it
 * the keys and values lenght most be equale
 * @param Model $model the model will search about 
 * @param mixed $search_param the value you will pass to search about it
 * @param mixed $keys the column key you want to update them 
 * @param mixed $values the new column values
 * @return Model $instance the updated finded instance 
 */
if (!function_exists('findAndUpdate')) {
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

/**
 * This function is used to store files 
 * @param mixed $file the file i want to store it
 * @param string $path the path where i want to save it
 * @return string $path+fileName
 */
if (!function_exists('storeProfileAvatar')) {

    function storeProfileAvatar($file, $path): string
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

/**
 * This function is used to update files 
 * @param string $old_path the old path where the file sotred
 * @param string $new_path the new path where i want to save the new file
 * @param mixed $file the file i want to store it
 * @return string $new_file_path+fileName
 */
if (!function_exists('editProfileAvatar')) {

    function editProfileAvatar($old_path,$new_path, $new_file): string
    {
        // Delete the old file from storage
        if (file_exists($old_path)) {

            unlink(public_path($old_path));

        }

        // Store the new file
        $new_file_path = storeProfileAvatar($new_file, $new_path);

        return $new_file_path;
    }

}
