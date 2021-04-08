<?php

namespace App\Auth;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Eloquent\Model;

class CasUserProvider implements UserProvider
{
    public function __construct(
        protected $model,
    )
    {
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param mixed $identifier
     * @return UserContract|Model|null
     */
    public function retrieveById($identifier)
    {
        $cas = app('cas');

        if (!$cas->isAuthenticated()) {
            return null;
        }

        $pasporId = $cas->user();
        if ($pasporId != $identifier) {
            return null;
        }

        /** @var Authenticatable $model */
        $model = $this->createModel();
        $key   = $model->getAuthIdentifierName();

        return $this
            ->newModelQuery($model)
            ->where([
                $key       => $pasporId,
                'is_aktif' => '1',
            ])
            ->first();
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(UserContract $user, $token)
    {
        return null;
    }

    public function retrieveByCredentials(array $credentials)
    {
        return null;
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {
        return null;
    }

    /**
     * Get a new query builder for the model instance.
     *
     * @param Model|null $model
     * @return Eloquent\Builder
     */
    protected function newModelQuery($model = null)
    {
        return is_null($model)
            ? $this->createModel()->newQuery()
            : $model->newQuery();
    }

    /**
     * Create a new instance of the model.
     *
     * @return Model
     */
    public function createModel()
    {
        $class = '\\' . ltrim($this->model, '\\');

        return new $class;
    }
}
