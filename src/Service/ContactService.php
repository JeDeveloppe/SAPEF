<?php

namespace App\Service;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ContactService
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        ){
    }

    public function saveQuestionInDatabase($entity,$form){

        $user = $this->security->getUser();

        if(!$user){

            $entity
                ->setEmail($form->get('email')->getData())
                ->setPhone($form->get('phone')->getData());

        }else{

            $entity
                ->setUser($user)
                ->setPhone($user->getPhone())
                ->setEmail($user->getEmail());
        }

        $entity
            ->setCreatedAt(new DateTimeImmutable('now'))
            ->setQuestion($form->get('question')->getData())
            ->setSubject($form->get('subject')->getData());

        $this->em->persist($entity);
        $this->em->flush();

    }
}