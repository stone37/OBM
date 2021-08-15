<?php

namespace App\Controller\Account;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Message;
use App\Entity\Settings;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messageSend(Request $request, PaginatorInterface $paginator, EntityManagerInterface $em)
    {
        if (!$this->settings->isActiveMessage()) throw $this->createNotFoundException('Page introuvable');

        $messages = $em->getRepository(Message::class)->getByUserSend($this->getUser());
        $messages = $paginator->paginate($messages, $request->query->getInt('page', 1), 15);

        $messageSendN = $em->getRepository(Message::class)->getByUserSendNumber($this->getUser());
        $messageReceiveN = $em->getRepository(Message::class)->getByUserReceiveNumber($this->getUser());

        return $this->render('user/message/send.html.twig', [
            'settings' => $this->settings,
            'messages' => $messages,
            'messageSendN' => $messageSendN,
            'messageReceiveN' => $messageReceiveN,
            'user' => $this->getUsers($em, $this->getUser()->getId()),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messageReceive(Request $request, PaginatorInterface $paginator, EntityManagerInterface $em)
    {
        if (!$this->settings->isActiveMessage()) throw $this->createNotFoundException('Page introuvable');

        $messages = $em->getRepository(Message::class)->getByUserReceive($this->getUser());
        $messages = $paginator->paginate($messages, $request->query->getInt('page', 1), 15);

        $messageSendN = $em->getRepository(Message::class)->getByUserSendNumber($this->getUser());
        $messageReceiveN = $em->getRepository(Message::class)->getByUserReceiveNumber($this->getUser());

        return $this->render('user/message/receive.html.twig', [
            'settings' => $this->settings,
            'messages' => $messages,
            'messageSendN' => $messageSendN,
            'messageReceiveN' => $messageReceiveN,
            'user' => $this->getUsers($em, $this->getUser()->getId()),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $id
     * @return JsonResponse|Response|RedirectResponse
     * @throws \Exception
     */
    public function delete(
        Request $request,
        EntityManagerInterface $em,
        $id)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $msg = $em->getRepository(Message::class)->find($id);

        $form = $this->deleteForm($msg);

        if ($request->getMethod() == 'DELETE') {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if ($msg->getEmail() == $this->getUser()->getEmail()) {
                    $msg->setDeleted(true);
                } else {
                    $msg->setRecepDeleted(true);
                }

                if ($msg->isDeleted() && $msg->isRecepDeleted()) {
                    $em->remove($msg);
                }

                $em->flush();

                $this->addFlash('success', 'Le message a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, le message n\'a pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cet message ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $msg,
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
    private function deleteForm(Message $message)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_dashboard_message_delete', ['id' => $message->getId()]))
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
