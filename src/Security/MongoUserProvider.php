<?php

namespace App\Security;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class MongoUserProvider implements UserProviderInterface{
    public function __construct(private DocumentManager $dm) {}

    public function loadUserByIdentifier(string $identifier): UserInterface{
        $user = $this->dm->getRepository(User::class)->findOneBy(['email' => $identifier]);
        if (!$user) {
            throw new UserNotFoundException("Usuário não encontrado");
        }
        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface{
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool{
        // return $class === User::class;
        return is_subclass_of($class, User::class) || $class === User::class;
    }
}
