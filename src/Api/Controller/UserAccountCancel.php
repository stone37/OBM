<?php

namespace App\Api\Controller;

use App\Entity\User;
 
class UserAccountCancel
{
    /**
     * @param User $data
     */
    public function __invoke($data): User
    {
        $data->setDeleteAt(null);

        return $data;
    }
}


