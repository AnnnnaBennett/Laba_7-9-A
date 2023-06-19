<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Questions;
use App\Entity\Answers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
//use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        $adminRoles = ['ROLE_USER', 'ROLE_ADMIN',];

        $userRoles = ['ROLE_USER'];
        
        $user = new User();
        $password = $this->hasher->hashPassword($user, 'user1234');
        $user->setEmail('user@mail.ru');
        $user->setName('user');
        $user->setRoles($userRoles);
        $user->setPassword($password);
        $user->setApiToken(Uuid::v1()->toRfc4122());

        $manager->persist($user);

        $user2 = new User();
        $password = $this->hasher->hashPassword($user2, 'admin1234');
        $user2->setEmail('admin@mail.ru');
        $user2->setName('admin');
        $user2->setRoles($adminRoles);
        $user2->setPassword($password);
        $user2->setApiToken(Uuid::v1()->toRfc4122());

        $manager->persist($user2);

        for ($i = 0; $i < 30; $i++) {
            
            if($i % 3 == 0){
                $question = new Questions();
                $question->setTitle('Что лучше? Новый ноут или обгрейд старого?');
                $question->setText('Есть ноут lenovo thinkpad x260 на i5. Хочу проабгрейдить озу и диск. С 4 на 8 гб озу и с 128 на 256 гб или купить другой (б/у) ноут с бюджетом до 16к?');
                $question->setCategory("Техника");
                $date = new \DateTime('@'.strtotime('now + 3 hours'));
                $question->setDate($date);
                $question->setUser($user);
                $manager->persist($question);

                $question->setToshow(true);

                $answer = new Answers();
                $answer->setText("Лучше апгрейдить пк. Ноут апгрейдить смысла нет. Лучше новый купить.");
                $answer->setDate($date);
                $answer->setUser($user2);
                $answer->setQuestion($question);
            }

            else if($i % 3 == 1){
                $question = new Questions();
                $question->setTitle('Не берут на работу');
                $question->setText('Почему не берут никуда на работу, несмотря на чистейшую биографию?');
                $question->setCategory("Трудоустройство");
                $date = new \DateTime('@'.strtotime('now + 3 hours'));
                $question->setDate($date);
                $question->setUser($user);
                $question->setUser($user);
                $question->setToshow(true);
                $manager->persist($question);

                $answer = new Answers();
                $answer->setText("Причин может быть много. Например, неправильно заполненное резюме или грамматические ошибки в нем. Все это может повлиять на выбор работодателя.");
                $answer->setDate($date);
                $answer->setUser($user2);
                $answer->setQuestion($question);
            }

            else if($i % 3 == 2){
                $question = new Questions();
                $question->setTitle('Ватсап помогите что делать?');
                $question->setText('Что делать если я хочу войти в ватсап с другого телефона,а прошлый телефон с ватсапом не работает? Ничего не получится сделать');
                $question->setCategory("Ватсап");
                $date = new \DateTime('@'.strtotime('now + 3 hours'));
                $question->setDate($date);
                $question->setUser($user);
                $question->setUser($user);
                $manager->persist($question);

                $answer = new Answers();
                $answer->setText("Придется удалить ватсап и потом снова установить и при установке вводить свой номер телефона");
                $answer->setDate($date);
                $answer->setUser($user2);
                $answer->setQuestion($question);
            }

            if($i % 2 == 0){
                $flag = false;
            }
            else{
                $flag = true;
            }
            $answer->setToshow($flag);
            $manager->persist($answer);
        }
        $manager->flush();
    }
}
