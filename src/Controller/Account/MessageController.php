<?php

namespace App\Controller\Account;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Advert;
use App\Entity\Message;
use App\Entity\Settings;
use App\Entity\Thread;
use App\Manager\ThreadManager;
use App\Provider\ThreadProvider;
use App\Service\Composer;
use App\Service\Sender;
use App\Service\SettingsManager;
use App\Service\ThreadDeleter;
use Doctrine\ORM\EntityManagerInterface;
use ReCaptcha\ReCaptcha;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class MessageController
 * @package App\Controller\Account
 */
class MessageController extends AbstractController
{
    use ControllerTrait;

    /**
     * @var Settings
     */
    private $settings;

    public function __construct(SettingsManager $manager)
    {
        $this->settings = $manager->get();
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param ThreadProvider $provider
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(ThreadProvider $provider, EntityManagerInterface $em)
    {
        $threads = $provider->getThreads();

        return $this->render('user/message/index.html.twig', [
            'threads' => $threads,
            'settings' => $this->settings,
            'user' => $this->getUsers($em, $this->getUser()->getId()),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ThreadProvider $provider
     * @param EntityManagerInterface $em
     * @param $threadId
     * @return Response
     */
    public function show(Request $request, ThreadProvider $provider, EntityManagerInterface $em, $threadId)
    {
        $thread = $provider->getThread($threadId);

        return $this->render('user/message/show.html.twig', [
            'thread' => $thread,
            'settings' => $this->settings,
            'index' => $request->query->get('index'),
            'user' => $this->getUsers($em, $this->getUser()->getId()),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Advert $advert
     * @param Request $request
     * @param Composer $composer
     * @param Sender $sender
     * @param ValidatorInterface $validator
     * @param ReCaptcha $reCaptcha
     * @return JsonResponse
     */
    public function replyThread(
        Thread $thread,
        Request $request,
        Composer $composer,
        Sender $sender,
        ValidatorInterface $validator
        //ReCaptcha $reCaptcha
    )
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $message = $composer->reply($thread)
                ->setSender($this->getUser())
                ->setBody($request->request->get('content'))
                ->getMessage();

        $errors = $validator->validate($message);

        if (!$this->isCsrfTokenValid('advert-message', $request->request->get('_token'))) {
            $errors[] = 'Le jeton CSRF est invalide.';
        }

        /*if (!$reCaptcha->verify($request->request->get('recaptchaToken'))->isSuccess()) {
            $errors[] = 'Erreur pendant l\'envoi de votre message';
        }*/

        if (!count($errors)) {
            $sender->send($message);

            return new JsonResponse([
                'success' => true,
                'message' => 'Votre message a été envoyer',
                'data' => [
                    'content' => $message->getBody(),
                    'createdAt' => $message->getCreatedAt(),
                ]
            ]);
        }

        $data = [];

        foreach ($errors as $error) {
            $data[] = $error->getMessage();
        }

        return new JsonResponse(['success' => false, 'errors' => json_encode($data)]);
    }


    public function delete(
        Request $request,
        ThreadProvider $provider,
        ThreadDeleter $deleter,
        ThreadManager  $manager,
        $id
    )
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $thread = $provider->getThread($id);

        $form = $this->deleteForm($thread);

        if ($request->getMethod() == 'DELETE') {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $deleter->markAsDeleted($thread);
                $manager->saveThread($thread);

                $this->addFlash('success', 'La conversation a été supprimée');
            } else {
                $this->addFlash('error', 'Désolé, la conversation n\'a pas pu être supprimée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cette conversation ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $thread,
            'message' => $message,
            'configuration' => $this->deleteConfig(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    /**
     * @param Message $message
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteForm(Thread $thread)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_message_thread_delete', ['id' => $thread->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @return \string[][][]
     */
    private function deleteConfig()
    {
        return [
            'modal' => [
                'delete' => [
                    'type' => 'modal-danger',
                    'icon' => 'fas fa-times',
                    'yes_class' => 'btn-outline-danger',
                    'no_class' => 'btn-danger',
                ]
            ]
        ];
    }
}
