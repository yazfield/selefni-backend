<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait CommonServiceMethods
{
    /**
     * Throws exception if $result is null or returns $result.
     *
     * @param mixed $result
     *
     * @throws ModelNotFoundException
     *
     * @return mixed
     */
    private function returnOrThrow($result)
    {
        if (is_null($result)) {
            throw new ModelNotFoundException;
        }

        return $result;
    }

    public function findBy(string $field, $value, bool $includeTrashed = false)
    {
        $result = $this->model->where($field, $value);
        if ($includeTrashed) {
            $result = $result->withTrashed();
        }

        return $this->returnOrThrow($result->first());
    }
}
