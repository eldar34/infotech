<?php

declare(strict_types=1);

namespace app\tests\phpunit;

use PHPUnit\Framework\TestCase;
use app\forms\BookForm;
use yii\validators\UniqueValidator;

final class BookFormTest extends TestCase
{
    /**
     * Вспомогательный метод для отключения обращения unique-валидатора к БД.
     */
    private function mockUniqueValidator(BookForm $form): void
    {
        foreach ($form->getValidators() as $validator) {
            if ($validator instanceof UniqueValidator) {
                // Создаем мок, который всегда возвращает true (считает, что запись уникальна)
                $mock = $this->createMock(UniqueValidator::class);

                // Переопределяем поведение встроенного метода валидации Yii2
                $validator->when = static function () {
                    return false; // Отключает выполнение этого конкретного валидатора
                };
            }
        }
    }

    public function testValidationSuccess(): void
    {
        $form = new BookForm();
        $form->attributes = [
            'title' => 'Чистый код',
            'release_year' => 2024,
            'isbn' => '9785446109538',
            'authorIds' => [1, 5, 7],
            'description' => 'Отличная книга для разработчиков',
        ];

        // Отключаем обращение к БД перед валидацией
        $this->mockUniqueValidator($form);

        $this->assertTrue($form->validate(), 'Форма должна быть валидной с корректными данными.');
    }

    public function testValidationFailsWhenTitleIsEmpty(): void
    {
        $form = new BookForm();
        $form->attributes = [
            'release_year' => 2024,
            'isbn' => '9785446109538',
            'authorIds' => [2, 8, 9],
        ];

        $this->mockUniqueValidator($form);

        $this->assertFalse($form->validate(), 'Валидация должна провалиться, если title отсутствует.');
        $this->assertArrayHasKey('title', $form->getErrors());
    }

    public function testValidationFailsWithInvalidReleaseYear(): void
    {
        $form = new BookForm();
        $form->attributes = [
            'title' => 'Книга из будущего',
            'release_year' => 2050,
            'isbn' => '9785446109538',
            'authorIds' => [3, 4, 6],
        ];

        $this->mockUniqueValidator($form);

        $this->assertFalse($form->validate());
        $this->assertArrayHasKey('release_year', $form->getErrors());
    }

    public function testValidationFailsWithShortIsbn(): void
    {
        $form = new BookForm();
        $form->attributes = [
            'title' => 'Тестовая книга',
            'release_year' => 2024,
            'isbn' => '12345',
            'authorIds' => [5, 7, 9],
        ];

        $this->mockUniqueValidator($form);

        $this->assertFalse($form->validate());
        $this->assertArrayHasKey('isbn', $form->getErrors());
    }
}
