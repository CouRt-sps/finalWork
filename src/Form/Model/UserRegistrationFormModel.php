<?php

namespace App\Form\Model;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @UniqueUser()
     */
    public $email;

    public $firstName;

    /**
     * @Assert\NotBlank(message="Пароль не указан")
     * @Assert\Length(min="6", minMessage="Пароль должен быть больше шести символов")
     */
    public $plainPassword;

    /**
     * @Assert\IsTrue(message="Вы должны согласитьс с условиями")
     */
    public $agreeTerms;
}