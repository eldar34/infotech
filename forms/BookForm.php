<?php

declare(strict_types=1);

namespace app\forms;

use app\models\Book;
use yii\base\Model;

class BookForm extends Model
{
    public ?int $bookId = null; 
    public ?string $title = null;
    public ?int $release_year = null;
    public ?string $description = null;
    public ?string $isbn = null;
    /** @var int[] */
    public array $authorIds = [];
    public $imageFile;

    public function rules(): array
    {
        return [
            [['title', 'release_year', 'isbn', 'authorIds'], 'required'],
            [['title', 'description'], 'string'],
            ['release_year', 'integer', 'min' => 1000, 'max' => (int)date('Y') + 1],
            ['isbn', 'string', 'min' => 10, 'max' => 13], 
            [
                'isbn', 
                'unique', 
                'targetClass' => Book::class, 
                'targetAttribute' => 'isbn',
                'message' => 'Книга с таким ISBN уже существует в базе данных.',
                // Исключаем текущую книгу из проверки при редактировании
                'filter' => function ($query) {
                    if ($this->bookId) { // Если у формы есть ID редактируемой книги
                        $query->andWhere(['not', ['id' => $this->bookId]]);
                    }
                }
            ],
            ['authorIds', 'each', 'rule' => ['integer']],
            [
                ['imageFile'], 
                'file', 
                'skipOnEmpty' => true, 
                'extensions' => 'png, jpg, jpeg',
                'maxSize' => 1024 * 1024 * 5 // 5MB
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Название книги',
            'release_year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'authorIds' => 'Авторы',
            'imageFile' => 'Фото главной страницы',
        ];
    }
}
