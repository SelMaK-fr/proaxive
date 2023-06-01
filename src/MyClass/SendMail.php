<?php
/*
 * SendMail Class
 *
 * @author SelMaK - synexolabs@gmail.com - https://www.synexolabs.com
 * @version 1.0
 * @date May 21, 2021
 */

namespace src\MyClass;


use Mailjet\Client;
use Mailjet\Resources;
use src\Controller\Controller;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\EventListener\MessageListener;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class SendMail extends Controller
{

    protected $mailer;

    /**
     * @var mixed
     */
    private $viewJson;

    private Transport\TransportInterface $transport;

    public function __construct()
    {
        parent::__construct();
        // Lecture du fichier de configuration json
        $viewJsonFile = file_get_contents(ROOT. '/config/mail.json');
        $this->viewJson = json_decode($viewJsonFile, false);

        $messageListener = new MessageListener(null, new BodyRenderer($this->twig));
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($messageListener);

        $this->transport = Transport::fromDsn('smtp://'.$this->viewJson->mail_username.':'.urlencode($this->viewJson->mail_password).'@'.$this->viewJson->mail_host.':'.$this->viewJson->mail_port, $eventDispatcher);
        $this->transport->setPingThreshold(10);
        $this->mailer = new Mailer($this->transport);
    }


    /**
     * Permet d'envoyer un email simple
     * TEST LOCAL / DEV
     * @param string $subject
     * @param string $view
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendSwiftMailerLocal(string $subject, string $view)
    {
        $message = (new Email())
            ->subject($subject)
            ->from('john@doe.com')
            ->to('selmak@myserver.eu')
            ->html($this->twig->render($view),'text/html');

        $this->mailer->send($message);
    }

    /**
     * Permet d'envoyer un email avec variables dans une vue twig
     * LOCAL
     * @param string $subject
     * @param array $data
     * @param string $view
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendDataSwiftMailerLocal(string $subject, array $data, string $view)
    {
        $message = (new TemplatedEmail())
            ->from('john@doe.com')
            ->to('selmak@myserver.eu')
            ->htmlTemplate('/views/templates/mail_updateintervention.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
            ]);

        $this->mailer->send($message);
    }


    public function sendDataMailer(){
        if ($this->viewJson->mail_service == 'mailjet'){

        } elseif ($this->viewJson->mail_service == 'mailer_smtp'){

        }
    }

    /**
     * Envoi un email de suivi d'intervention au client
     * Peut également être utiilisé avec d'autres vues
     * PROD
     * @param string $subject
     * @param string $from
     * @param string $to
     * @param string $name
     * @param array $data
     * @param string $view
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws TransportExceptionInterface
     */
    public function sendDataSmtpMailer(string $subject, string $from, string $to, string $name, array $data, string $view)
    {
        $subject = $this->viewJson->mail_title_from;
        $message = (new Email())
            ->from($from)
            ->to($from)
            ->subject($subject)
            ->html($this->twig->render($view, $data));
        $this->mailer->send($message);
    }

    /**
     * Envoi d'email via les serveurs de Mailjet (configuration par défaut)
     * Un compte gratuit permet d'envoyer 6000 mails par mois / 200 mails par jour
     * Configurez vos clé via le fichier de configuration (.env) à la racine de l'application
     * Pour créer compte gratuit Mailjet : mailjet.com
     * @param string $subject
     * @param string $mail
     * @param string $fullname
     * @param array $data
     * @param string $view
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendDataMailJet(string $subject, string $mail, string $fullname, array $data, string $view){

        $mj = new Client($this->viewJson->mailjet_publickey, $this->viewJson->mailjet_privatekey,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->viewJson->mail_address,
                        'Name' => $this->viewJson->mail_title_from
                    ],
                    'To' => [
                        [
                            'Email' => "$mail",
                            'Name' => "$fullname"
                        ]
                    ],
                    'Subject' => "$subject",
                    'TextPart' => "Greetings from Mailjet!",
                    'HTMLPart' => $this->twig->render($view, $data)
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        return $response->success();
    }

}
