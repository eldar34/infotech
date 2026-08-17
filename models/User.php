<?php

declare(strict_types=1);

namespace app\models;

use yii\base\BaseObject;
use yii\web\IdentityInterface;

class User extends BaseObject implements IdentityInterface
{
    public int|string $id = '';
    public string $username = '';
    public string $email = '';
    public string $passwordHash = '';
    public string $authKey = '';
    public string $accessToken = '';
    private static array $_users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'email' => 'admin@tech.local',
            // password: admin12Ts72
            'passwordHash' => '$2y$13$4ZB0ofLlibw6h/ELOPig.Oivhgn9iW7upn4AUBAIaEbuPThUfYkTy',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'email' => 'demo@tech.local',
            // password: demo12Ts72
            'passwordHash' => '$2y$13$BO4ktCAxa2qn74mhuQXBDevAdekHkrQNQ3TufFm5ICL/3ABEQTz7G',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): static|null
    {
        return isset(self::$_users[$id]) ? new static(self::$_users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): static|null
    {
        foreach (self::$_users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): static|null
    {
        foreach (self::$_users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): string|null
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->authKey === $authKey;
    }
}
