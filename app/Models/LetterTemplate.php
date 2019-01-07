<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class LetterTemplate extends Model
{
    protected $table = 'letter_templates';

    protected $appends = [
        'template_name',
        'villager_name',
        'signations_status',
        'needs_villager_data',
        'is_all_signed',
        'villager_data',
        'sign'
    ];

    protected $fillable = [
        'template_id',
        'data',
        'status'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function villager()
    {
        return $this->belongsTo(Villager::class);
    }

    public function getTemplateNameAttribute()
    {
        return $this->template->title;
    }

    public function getVillagerNameAttribute()
    {
        return $this->villager ? $this->villager->name : 'Tidak ada';
    }

    public function hasUserSignedIt($userId)
    {
        $field = $this->template->template_fields()->where('user_id', $userId)->first();

        if ($field) {
            $name = $field->name;
            $data = json_decode($this->data);
            if (isset($data->$name)) return $data->$name ? : false;

            return false;
        }

        return false;
    }

    public function signLetter($userId)
    {
        $field = $this->template->template_fields()->where('user_id', $userId)->first();

        if ($field) {
            $name = $field->name;
            $data = json_decode($this->data);
            $data->$name = true;
            $this->data = json_encode($data);
            if ($this->save()) {
                $this->deleteGeneratedFile();
                return true;
            }

            return false;
        }

        return false;
    }

    public function unsignLetter($userId)
    {
        $field = $this->template->template_fields()->where('user_id', $userId)->first();

        if ($field) {
            $name = $field->name;
            $data = json_decode($this->data);
            $data->$name = false;
            $this->data = json_encode($data);
            if ($this->save()) {
                $this->deleteGeneratedFile();
                return true;
            }

            return false;
        }

        return false;
    }

    public function getSignationsStatusAttribute()
    {
        $signers = $this->template->template_fields()->where('type', 4)->get();
        $status = [];
        foreach ($signers as $key => $signer) {
            $status[config('esisma.signature_field_prefix') . $signer->name] = $this->hasUserSignedIt($signer->user_id);
        }

        return $status;
    }

    public function getNeedsVillagerDataAttribute()
    {
        return $this->template->needs_villager_data ? true : false;
    }

    public function deleteGeneratedFile()
    {
        $filePath = config('esisma.generated_docs') . '/' . $this->generated_file;
        if (Storage::exists($filePath)) Storage::delete($filePath);
        $this->generated_file = null;
        if ($this->save()) return true;

        return false;
    }

    public function getIsAllSignedAttribute()
    {
        $signers = $this->template->template_fields()->where('type', 4)->get();
        foreach ($signers as $key => $signer) {
            if (!$this->hasUserSignedIt($signer->user_id)) return false;
        }

        return true;
    }

    public function getVillagerDataAttribute()
    {
        $villagerFields = $this->template->template_fields()->where('type', 3)->get();
        $data = [];
        foreach ($villagerFields as $key => $villagerField) {
            $field = config('esisma.villager_fields')[$villagerField->name];
            $data[$villagerField->name] = $this->villager->$field;
        }

        return $data;
    }

    public function getSignAttribute()
    {
        $userId = app('auth')->user()->id;
        $field = $this->template->template_fields()->where('user_id', $userId)->first();

        if ($field) {
            $data['has_permission'] = true;
            $data['has_signed'] = $this->hasUserSignedIt($userId);

            return $data;
        }

        return false;
    }

    public static function canSign($id)
    {
        $userId = app('auth')->user()->id;
        $letter = LetterTemplate::find($id);

        if ($letter) {
            $field = $letter->template->template_fields()->where('user_id', $userId)->first();
            if ($field) return true;
        }

        return false;
    }
}
