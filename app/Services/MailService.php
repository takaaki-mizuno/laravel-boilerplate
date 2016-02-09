<?php namespace App\Services;

use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;

use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;

class MailService
{


    public function __construct()
    {
    }

    public function sendMail($title, $from, $to, $template, $data)
    {
        if (\Config::get('app.offline_mode')) {
            return true;
        }


        if (\App::environment() != 'production') {
            $title = '[' . \App::environment() . '] ' . $title;
            $to = [
                'address' => \Config::get('mail.tester'),
                'name'    => \App::environment() . ' Original: ' . $to['address'],
            ];
        }

        $client = new SesClient([
            'credentials' => [
                'key'    => \Config::get('aws.key'),
                'secret' => \Config::get('aws.secret'),
            ],
            'region'      => \Config::get('aws.ses_region'),
            'version'     => 'latest',
        ]);

        try {
            $body = \View::make($template, $data)->render();
            $sesData = [
                'Source'      => mb_encode_mimeheader($from['name']) . ' <' . $from['address'] . '>',
                'Destination' => [
                    'ToAddresses' => [$to['address']],
                ],
                'Message'     => [
                    'Subject' => [
                        'Data'    => $title,
                        "Charset" => "UTF-8",
                    ],
                    'Body'    => [
                        'Html' => [
                            "Data"    => $body,
                            "Charset" => "UTF-8",
                        ],
                    ],
                ],
            ];
            $client->sendEmail($sesData);
        } catch (\Exception $e) {
            echo $e->getMessage(), "\n";
        }

        return true;
    }

    /**
     * @param  string $mail
     * @return bool
     */
    public function sendNewsletterSubscriptionMail($mail)
    {

        $articles = $this->articleRepository->getFeaturedArticles(0, 2);

        return $this->sendMail('【登録完了】Catalyst Newsletter', \Config::get('mail.contacts.info'), [
                'address' => $mail,
                'name'    => null,
            ], 'emails.users.newsletter_subscribed', [
                'articles' => $articles,
            ]);
    }

    /**
     * @param  string $mail
     * @param  string $language
     * @return bool
     */
    public function sendNewsletterMail($mail, $language)
    {

        $categories = $this->categoryRepository->getEnabled('id', 'desc', 0, 100);
        $articles = [];
        foreach ($categories as $category) {
            $articles[ $category->id ] = $this->articleRepository->getByCategoryId($category->id, 'id', 'desc', 0, 2,
                true, false, $language);
        }
        $featureArticles = $this->articleRepository->getFeaturedArticles(0, 1);

        return $this->sendMail('【Catalyst News】 ' . $featureArticles[0]->title, \Config::get('mail.contacts.info'), [
                'address' => $mail,
                'name'    => null,
            ], 'emails.users.newsletter', [
                'categories'      => $categories,
                'featuredArticle' => $featureArticles[0],
                'articles'        => $articles,
            ]);
    }

    /**
     * @param  \App\Models\User $user
     * @return bool
     */
    public function sendRegisteredMail($user)
    {
        return $this->sendMail('ご登録ありがとうございます', \Config::get('mail.contacts.info'), [
                'address' => $user->email,
                'name'    => null,
            ], 'emails.users.registered', []);
    }

}