<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Form\Type\ChangePasswordType;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Event\ContactCreatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Controller used to manage current contact.
 *
 * @Route("/profile")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/add", methods="GET|POST", name="contact_add")
     */
    public function store(Request $request, UserPasswordEncoderInterface $encoder, EventDispatcherInterface $eventDispatcher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'user.updated_successfully');

            //Send email
            $eventDispatcher->dispatch(new ContactCreatedEvent($form->getData()));

            return $this->redirectToRoute('contact_add');
        }

        return $this->render('user/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
