<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AppModel extends Model
{

    public function fileUrl($col = null, $option = null)
    {
        $col = !empty($col) ? $col : 'avatar';

        if (!empty($option) && $option == 'path') {
            return Storage::url($this->$col);
        } else {
            return asset(Storage::url($this->$col));
        }
    }

    public function getById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function saveData($input, $model_id = null)
    {
        if (empty($model_id)) {
            $model = $this->create($input);
            $model = $model->fresh();
        } else {
            $model = $this->updateOrCreate(['id' => $model_id], $input);
            $model->save();
        }
        return $model;
    }
}
