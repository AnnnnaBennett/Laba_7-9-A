<?php

namespace App\Tests\Client;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientTest extends WebTestCase
{
    private array $trueData = ['email' => 'user@mail.ru', 'password' => 'user1234'];
    private array $falseData = ['email' => 'user@mail.ru', 'password' => '12345678'];

    public function testIndexPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Вопрос-ответ');
        $this->assertCount(20, $crawler->filter('.question'));

        $link = $crawler->filter('.question')->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(200);
        $this->assertPageTitleContains('Вопрос');
    }

    public function testAuthentication(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('#btn-authorization')->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(200);
        $this->assertPageTitleContains('Вход');
        $client->submitForm('Войти', $this->falseData);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', 'Неверный email или пароль');
        $client->submitForm('Войти', $this->trueData);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertPageTitleContains('Вопрос-ответ');
    }

    public function testPublicQuestion()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/publicQuestion');
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertPageTitleContains('Вход');
        $client->submitForm('Войти', $this->trueData);

        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $this->assertPageTitleContains('Вопрос-ответ');

        $link = $crawler->filter('#btn-publicQuestion')->link();
        $client->click($link);
        $questionEmpty = [
            'questions_form[title]' => ' ',
            'questions_form[text]' => 'Что делать если я хочу войти в ватсап с другого телефона,а прошлый телефон с ватсапом не работает? Ничего не получится сделать',
            'questions_form[category]' => 'Ватсап',
        ];
        $client->submitForm('Добавить вопрос', $questionEmpty);
        $this->assertSelectorTextContains('.alert-danger', 'Пожалуйста введите заголовок вопроса');
        $question = [
            'questions_form[title]' => 'Ватсап помогите что делать?',
            'questions_form[text]' => 'Что делать если я хочу войти в ватсап с другого телефона,а прошлый телефон с ватсапом не работает? Ничего не получится сделать',
            'questions_form[category]' => 'Ватсап',
        ];
        $client->submitForm('Добавить вопрос', $question);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertPageTitleContains('Вопрос-ответ');
    }
}
