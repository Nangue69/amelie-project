<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Flash;
use App\Core\Mailer;
use App\Core\Request;
use App\Core\Seo;
use App\Core\Settings;
use App\Core\Validator;
use App\Models\Faq;
use App\Models\Message;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\SizeGuide;
use App\Models\Subscriber;

final class PageController extends SiteController
{
    public function show(Request $request): void
    {
        $this->boot($request);
        $this->guardComingSoon($request);

        $slug = $request->param('slug');
        $page = Page::findBySlug($slug, $this->lang);

        if ($page === null) {
            $this->renderNotFound();

            return;
        }

        Seo::set([
            'title'       => $page['meta_title'] ?: $page['title'],
            'description' => $page['meta_description'] ?: excerpt($page['intro'] ?: $page['body']),
            'path'        => $slug,
        ]);

        Seo::addSchema(Seo::breadcrumbSchema([
            ['name' => t('common.home'), 'url' => lang_url('')],
            ['name' => (string) $page['title'], 'url' => lang_url($slug)],
        ]));

        $data = ['page' => $page];

        $template = match ($page['template']) {
            'story'     => 'site/pages/story',
            'sizeguide' => 'site/pages/size-guide',
            'faq'       => 'site/pages/faq',
            'contact'   => 'site/pages/contact',
            default     => 'site/pages/default',
        };

        if ($page['template'] === 'sizeguide') {
            $data['guides'] = SizeGuide::allWithRows();
        }

        if ($page['template'] === 'faq') {
            $data['faqs'] = Faq::active($this->lang);
            Seo::addSchema($this->faqSchema($data['faqs']));
        }

        if ($page['template'] === 'story') {
            $data['products'] = Product::featured($this->lang, 2);
        }

        $this->view($template, $data);
    }

    public function journal(Request $request): void
    {
        $this->boot($request);
        $this->guardComingSoon($request);

        Seo::set([
            'title'       => t('journal.title'),
            'description' => t('journal.subtitle'),
            'path'        => 'journal',
        ]);

        $this->view('site/journal', ['posts' => Post::published($this->lang, 12)]);
    }

    public function post(Request $request): void
    {
        $this->boot($request);
        $this->guardComingSoon($request);

        $post = Post::findBySlug($request->param('slug'), $this->lang);

        if ($post === null) {
            $this->renderNotFound();

            return;
        }

        $path = 'journal/' . $post['slug'];

        Seo::set([
            'title'       => $post['meta_title'] ?: $post['title'],
            'description' => $post['meta_description'] ?: excerpt($post['excerpt'] ?: $post['body']),
            'image'       => $post['cover_image'],
            'type'        => 'article',
            'path'        => $path,
        ]);

        Seo::addSchema([
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => $post['title'],
            'description'   => excerpt($post['excerpt'], 200),
            'image'         => Seo::absolute(img((string) $post['cover_image'])),
            'datePublished' => $post['published_at'],
            'dateModified'  => $post['updated_at'],
            'author'        => ['@type' => 'Organization', 'name' => setting('site_name', 'AMÉLIE')],
        ]);

        $this->view('site/post', [
            'post'   => $post,
            'others' => Post::others((int) $post['id'], $this->lang, 2),
        ]);
    }

    public function contactSubmit(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        // Campo trampa para robots: si viene relleno, fingimos exito y descartamos.
        if ($request->text('website') !== '') {
            Flash::success(t('msg.message_sent'));
            $this->redirect(lang_url('contact'));
        }

        $validator = new Validator($request->all());
        $validator->required('name', t('msg.fields_required'))
            ->required('email', t('msg.fields_required'))
            ->email('email', t('msg.invalid_email'))
            ->required('message', t('msg.fields_required'))
            ->minLength('message', 10, t('msg.fields_required'))
            ->maxLength('message', 5000, t('msg.fields_required'));

        if ($validator->fails()) {
            Flash::error($validator->firstError());
            Flash::setErrors($validator->errors());
            Flash::keepInput($request->all());
            $this->redirect(lang_url('contact'));
        }

        Message::store([
            'name'    => $validator->value('name'),
            'email'   => $validator->value('email'),
            'subject' => mb_substr($validator->value('subject'), 0, 190),
            'body'    => $validator->value('message'),
            'lang'    => $this->lang,
        ]);

        Mailer::toAdmin(
            'Nuevo mensaje desde la web',
            '<p><strong>' . e($validator->value('name')) . '</strong> (' . e($validator->value('email')) . ')</p>'
            . '<p>' . nl2br(e($validator->value('message'))) . '</p>'
        );

        Flash::clearInput();
        Flash::success(t('msg.message_sent'));
        $this->redirect(lang_url('contact'));
    }

    public function newsletterSubmit(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $email = $request->text('email');

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            if ($request->isAjax()) {
                $this->json(['ok' => false, 'message' => t('msg.invalid_email')]);
            }

            Flash::error(t('msg.invalid_email'));
            $this->back();
        }

        $result  = Subscriber::subscribe($email, $this->lang, $request->text('source', 'footer'));
        $ok      = $result !== 'exists';
        $message = $ok ? t('msg.subscribed') : t('msg.already_subscribed');

        if ($result === 'new') {
            Mailer::toAdmin('Nueva suscripción', '<p>' . e($email) . ' (' . e($this->lang) . ')</p>');
        }

        if ($request->isAjax()) {
            $this->json(['ok' => $ok, 'message' => $message]);
        }

        $ok ? Flash::success($message) : Flash::info($message);
        $this->back();
    }

    public function unsubscribe(Request $request): void
    {
        $this->boot($request);

        $done = Subscriber::unsubscribeByToken($request->param('token'));

        Seo::set(['title' => t('footer.newsletter_title'), 'noindex' => true, 'path' => '']);

        $this->view('site/unsubscribe', ['done' => $done]);
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');

        $urls = [];

        foreach (\App\Core\Lang::codes() as $code) {
            $urls[] = ['loc' => Seo::absolute(lang_url('', $code)), 'priority' => '1.0'];
            $urls[] = ['loc' => Seo::absolute(lang_url('shop', $code)), 'priority' => '0.9'];
            $urls[] = ['loc' => Seo::absolute(lang_url('journal', $code)), 'priority' => '0.6'];

            foreach (Product::slugs() as $product) {
                $urls[] = [
                    'loc'      => Seo::absolute(lang_url('product/' . $product['slug'], $code)),
                    'lastmod'  => format_date($product['updated_at'], 'Y-m-d'),
                    'priority' => '0.9',
                ];
            }

            foreach (Page::slugs() as $page) {
                $urls[] = ['loc' => Seo::absolute(lang_url($page['slug'], $code)), 'priority' => '0.5'];
            }

            foreach (Post::slugs() as $post) {
                $urls[] = [
                    'loc'      => Seo::absolute(lang_url('journal/' . $post['slug'], $code)),
                    'lastmod'  => format_date($post['updated_at'], 'Y-m-d'),
                    'priority' => '0.5',
                ];
            }
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            echo '  <url><loc>' . e($url['loc']) . '</loc>';

            if (!empty($url['lastmod'])) {
                echo '<lastmod>' . e($url['lastmod']) . '</lastmod>';
            }

            echo '<priority>' . e($url['priority']) . '</priority></url>' . "\n";
        }

        echo '</urlset>';
        exit;
    }

    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');

        if (Settings::bool('robots_index', true) && !Settings::bool('coming_soon')) {
            echo "User-agent: *\n";
            echo "Disallow: /admin\n";
            echo "Disallow: /*/cart\n";
            echo "Disallow: /*/checkout\n";
            echo "Disallow: /*/search\n\n";
        } else {
            echo "User-agent: *\nDisallow: /\n\n";
        }

        echo 'Sitemap: ' . Seo::absolute(url('sitemap.xml')) . "\n";
        exit;
    }

    private function faqSchema(array $faqs): array
    {
        $entities = [];

        foreach ($faqs as $faq) {
            $entities[] = [
                '@type'          => 'Question',
                'name'           => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags((string) $faq['answer'])],
            ];
        }

        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $entities,
        ];
    }
}
