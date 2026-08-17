<?php

declare(strict_types=1);

namespace app\tests\phpunit;

use PHPUnit\Framework\TestCase;
use app\forms\AuthorForm;

final class AuthorFormTest extends TestCase
{
    /**
     * Тест успешной валидации формы с корректным именем автора.
     */
    public function testValidationSuccess(): void
    {
        $form = new AuthorForm();
        $form->attributes = [
            'full_name' => 'Александр Сергеевич Пушкин',
        ];

        $this->assertTrue($form->validate(), 'Форма должна быть валидной с корректным ФИО.');
    }

    /**
     * Тест ошибки валидации, если поле имени пустое.
     */
    public function testValidationFailsWhenNameIsEmpty(): void
    {
        $form = new AuthorForm();
        $form->attributes = [
            'full_name' => '',
        ];

        $this->assertFalse($form->validate(), 'Валидация должна провалиться с пустым ФИО.');
        $this->assertArrayHasKey('full_name', $form->getErrors(), 'Должна быть ошибка для поля full_name.');
    }

    /**
     * Тест ограничений на максимальную длину строки.
     */
    public function testValidationFailsWhenNameIsTooLong(): void
    {
        $form = new AuthorForm();
        $form->attributes = [
            'full_name' => str_repeat('A', 256), // Слишком длинная строка
        ];

        $this->assertFalse($form->validate(), 'Форма не должна пропускать строки длиннее 255 символов.');
        $this->assertArrayHasKey('full_name', $form->getErrors());
    }
}
