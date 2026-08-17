<?php

declare(strict_types=1);

namespace app\forms;

use yii\base\Model;

class AuthorForm extends Model
{
    public ?int $id = null;
    public ?string $full_name = null;

    public function rules(): array
    {
        return [
            [['full_name'], 'required'],
            [['full_name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'full_name' => 'ФИО Авторa',
        ];
    }
}
