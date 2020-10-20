<?php


namespace App\Controller\Admin;


use App\Entity\Creator;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CreatorRepository;
use App\Repository\CreatorRepositoryInterface;
use App\Repository\PostRepository;
use App\Repository\PostRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AdminBaseController
{
    private $creatorRepository;
    private $postRepository;

    public function __construct(CreatorRepositoryInterface $creatorRepository, PostRepositoryInterface $postRepository)
    {
        $this->creatorRepository = $creatorRepository;
        $this->postRepository = $postRepository;
    }
    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Wszystkie publikacje';
        $forRender['post'] = $this->postRepository->getAllPost();
        $forRender['check_creator'] = $this->creatorRepository->getAllCreator();
        return $this->render('admin/post/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/post/create", name="admin_post_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->postRepository->setCreatePost($post);
            $this->addFlash('success', 'Dodano'); //dodaje message
            return $this->redirectToRoute('admin_post');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Tworzenie wpisu';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig', $forRender);
    }

    /**
     * @Route("/admin/post/update/{id}", name="admin_post_update")
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function update(int $id, Request $request)
    {
        $post = $this->postRepository->getOnePost($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            if($form->get('save')->isClicked())
            {
                $this->postRepository->setUpdatePost($post);
                $this->addFlash('success', 'Wpis zostal zmodyfikowany');
            }
            if($form->get('delete')->isClicked())
            {
                $this->postRepository->setDeletePost($post);
                $this->addFlash('success', 'Wpis zostal usuniety');
            }
            return $this->redirectToRoute('admin_post');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Modyfikacja wpisu';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig', $forRender);

    }

    /**
     * @param Request $request
     * @param PostRepository $postRepository
     * @return RedirectResponse|Response
     */
    public function export(Request $request, PostRepository $postRepository)
    {
        $all = true;
        $columns = '';

        if(!empty($request->get('id')))
        {
            $columns = 'post_item.id,';
        }
        if(!empty($request->get('title')))
        {
            $columns = 'post_item.title,';
        }
        if(!empty($request->get('year')))
        {
            $columns = 'post_item.year,';
        }
        if(!empty($request->get('participation')))
        {
            $columns = 'post_item.participation,';
        }
        if(!empty($request->get('doi')))
        {
            $columns = 'post_item.doi,';
        }
        if(!empty($request->get('numOfPoints')))
        {
            $columns = 'post_item.numOfPoints,';
        }
        if(!empty($request->get('conference')))
        {
            $columns = 'post_item.numOfPoints,';
        }
        if(!empty($request->get('creator')))
        {
            $columns = 'post_item.creator,';
        }
        if(empty($columns))
        {
            $columns = 'post_item';
        } else {
            $all  = false;
            $columns = rtrim($columns, ',');
        }
        $ids = '';
        for ($i = 1; $i <= 10; $i++) {
            if(null !== $request->get("row_".$i)) {
                if($i > 8) $ids = $i;
                $ids = ' '.$ids.''.$i.',';
            }
        }
        if(empty($ids)) {
            $rows = '';
        } else {
            $rows = rtrim($ids, ',');
        }
        $posts = $postRepository->createQueryBuilder('post_item')
            ->select($columns);

        if(!empty($rows)) {
            $posts = $posts->where("post_item.id IN (".$rows.")");
        }

        $posts = $posts->getQuery()
            ->getResult();

        $pubs = [];
        foreach($posts as $post ) {
            $isEmpty = true;
            foreach($post as $field ) {
                if ($field instanceof \DateTime) {
                    $posts['date'] = $field->format('Y-m-d');
                }
                if($field) $isEmpty = false;
            }
            if(!$isEmpty) array_push($pubs, $post);
        }
        if($columns !== 'post_item') {
            $columns = str_replace('post_item.', '', $columns);
            $columns = explode(',', $columns);
        }

        if($columns) {

            // Instantiate Dompdf with our options
            $phpWord = new phpWord();

            $twig = $this->get('twig');
            /** @var \Twig_Template $template */
            $template = $twig->load('admin/post/preview.html.twig');

            // Retrieve the HTML generated in our twig file
            $html = $template->renderBlock('body',[
                'publishes' => $pubs,
                'columns' => $columns,
            ]);

            $section = $phpWord->addSection();

            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html);
            // Saving the document as OOXML file...

            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            header("Content-Disposition: attachment; filename=export.docx");
            $objWriter->save("php://output");


            return $this->render('admin/post/preview.html.twig', [
                'publishes' => $pubs,
                'columns' => $columns,
            ]);
        }

    }
}