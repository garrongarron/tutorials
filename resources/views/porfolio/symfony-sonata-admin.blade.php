@extends('general.layout')


@section('content')
<div class="content">

    <h1>Symfony & Sonata-admin</h1>

    <p>Descargar laradock.</p>
    <p>Copiar el archivo de con las variabes de entorno.</p>
    <p>Nginx tiene archivo especial para Synfony.</p>
    <p>Entrar al workspace.</p>
    <p>Entrar al workspace.</p>
    <p>sudo docker-compose up -d nginx mysql</p>

    <p>sudo docker-compose exec workspace bash</p>
    <p>composer create-project symfony/website-skeleton .</p>
    <p>composer require sonata-project/admin-bundle (including "sonata-project/core-bundle (>=3.9)" AND "sonata-project/admin-bundle (>=3.31)") whit are from contributors.</p>
    <p>composer require sonata-project/doctrine-orm-admin-bundle</p>
    <p>chmod -R 777 .</p>
    <pre><code class="language-html"># config/packages/framework.yaml

framework:
    translator: { fallbacks: ['%locale%'] }</code></pre>


    <p>bin/console cache:clear</p>
    <p>bin/console assets:install</p>
    <p>http://yoursite.local/admin/dashboard</p>

    <p>bin/console make:entity</p>
    <p>BlogPost</p>
    <p>title</p>
    <p>string</p>
    <p>255</p>
    <p>no</p>

    <p>body</p>
    <p>text</p>
    <p>no</p>

    <p>draft</p>
    <p>boolean</p>
    <p>no</p>

    


    <p>bin/console make:entity</p>
    <p>Category</p>
    <p>name</p>
    <p>string</p>
    <p>255</p>
    <p>no</p>

    <p>blogPosts</p>
    <p>OneToMany</p>
    <p>App\Entity\BlogPost</p>
    <p>no</p>
    <p>no</p>\


    <p>Add "DATABASE_URL=mysql://default:secret@mysql:3306/default" to .env file</p>



    <p>bin/console doctrine:schema:update --force</p>



    <pre><code class="language-javascript">protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            // ->add('id')
            ->add('name')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            // ->add('id')
            ->add('name')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            // ->add('id')
            ->add('name')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            // ->add('id')
            ->add('name')
            ;
    }</code></pre>


    <p>Update BlogPostAdmin.php</p>

    <pre><code class="language-html">use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Sonata\AdminBundle\Form\Type\ModelType;
use App\Entity\Category;</code></pre>
    <pre><code class="language-html">protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            // ->add('id')
            ->add('title')
            ->add('body')
            ->add('draft')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            // ->add('id')
            ->addIdentifier('title')
            ->add('body')
            ->add('draft')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            // ->add('id')
            ->add('title')
            ->add('body')
            ->add('draft', CheckboxType::class, ['required'=> false])
            ->add('category', ModelType::class, [
                'class' => Category::class,
                'property' => 'name',
            ])
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            // ->add('id')
            ->add('title')
            ->add('body')
            ->add('draft')
            ;
    }</code></pre>

</div>
@endsection
