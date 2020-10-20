<?php


namespace App\Controller\Main;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, PostRepository $postRepository, UserRepository $userRepository)
    {
//        if ($request->request->get('searchVal'))
//        {
//            $search = $request->request->get('searchVal');
//        }
//        $order = $request->query->get('order');
//        $sort = $request->query->get('sort') === 'ASC' ? 'DESC' : 'ASC';
//        $page = $request->get('page') ? $request->get('page') : 1;
//        $limit = 2;
//
//        $post = $search ? $postRepository->findByAllTerm($search, $order, $sort) : $postRepository->findBy(
//            array(),
//            $order ? array($order => $sort) : null,
//            $limit,
//            ($page-1)*$limit
//        );
//        $users = $userRepository->findAll();
        $post = $this->getDoctrine()->getRepository(Post::class)
            ->findAll();
        $forRender = parent::renderDefault();
        $forRender['post'] = $post;
        return $this->render('main/index.html.twig', $forRender);
    }
}