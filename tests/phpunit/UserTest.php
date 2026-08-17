<?php

declare(strict_types=1);

namespace app\tests\phpunit;

use PHPUnit\Framework\TestCase;
use app\models\User;

final class UserTest extends TestCase
{
    public function testFindUserById(): void
    {
        $user = User::findIdentity(100);

        $this->assertNotNull($user);
        $this->assertSame('admin', $user->username);
    }

    public function testFindUserByWrongIdReturnsNull(): void
    {
        $user = User::findIdentity(999);

        $this->assertNull($user);
    }

    public function testFindByUsername(): void
    {
        $user = User::findByUsername('demo');

        $this->assertNotNull($user);
        $this->assertSame('demo@tech.local', $user->email);
    }
}
