<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Flash;
use App\Core\Lang;
use App\Core\Request;
use App\Core\Uploader;
use App\Models\Faq;
use App\Models\Message;
use App\Models\Page;
use App\Models\Post;
use App\Models\SizeGuide;
use App\Models\Subscriber;

final class ContentController extends AdminController
{
    // ------------------------------------------------------------------
    // Páginas
    // ------------------------------------------------------------------

    public function pages(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/pages', ['pages' => Page::all(Lang::default())]);
    }

    public function editPage(Request $request): void
    {
        $this->boot($request);

        $pageId = (int) $request->param('id');
        $page   = Page::find($pageId);

        if ($page === null) {
            Flash::error('Esa página no existe.');
            $this->redirect(admin_url('pages'));
        }

        $this->view('admin/page-edit', [
            'page'         => $page,
            'translations' => Page::translations($pageId),
        ]);
    }

    public function savePage(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $pageId = (int) $request->param('id');
        $page   = Page::find($pageId);

        if ($page === null) {
            $this->redirect(admin_url('pages'));
        }

        Database::update('pages', [
            'is_active' => $request->text('is_active') === '1' ? 1 : 0,
            'position'  => $request->int('position'),
        ], '`id` = :id', ['id' => $pageId]);

        foreach (array_keys($this->langs) as $code) {
            Page::saveTranslation($pageId, $code, [
                'title'            => $request->text("t_{$code}_title"),
                'intro'            => $request->text("t_{$code}_intro"),
                'body'             => safe_html((string) $request->input("t_{$code}_body", '')),
                'meta_title'       => $request->text("t_{$code}_meta_title"),
                'meta_description' => $request->text("t_{$code}_meta_description"),
            ]);
        }

        Auth::log('update', 'page', $pageId, (string) $page['slug']);
        $this->saved();
        $this->redirect(admin_url('pages/' . $pageId));
    }

    // ------------------------------------------------------------------
    // Journal
    // ------------------------------------------------------------------

    public function posts(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/posts', ['posts' => Post::all(Lang::default())]);
    }

    public function createPost(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/post-new');
    }

    public function storePost(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $title = $request->text('title');

        if ($title === '') {
            Flash::error('La entrada necesita un título.');
            $this->redirect(admin_url('posts/new'));
        }

        $postId = Database::insert('posts', [
            'slug'         => $this->uniquePostSlug($title),
            'author_id'    => Auth::id(),
            'status'       => 'draft',
            'published_at' => date('Y-m-d H:i:s'),
        ]);

        foreach (array_keys($this->langs) as $code) {
            Post::saveTranslation($postId, $code, ['title' => $title]);
        }

        Auth::log('create', 'post', $postId, $title);
        $this->saved('Entrada creada. Ahora escribe su contenido.');
        $this->redirect(admin_url('posts/' . $postId));
    }

    public function editPost(Request $request): void
    {
        $this->boot($request);

        $postId = (int) $request->param('id');
        $post   = Post::find($postId);

        if ($post === null) {
            Flash::error('Esa entrada no existe.');
            $this->redirect(admin_url('posts'));
        }

        $this->view('admin/post-edit', [
            'post'         => $post,
            'translations' => Post::translations($postId),
        ]);
    }

    public function savePost(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $postId = (int) $request->param('id');

        if (Post::find($postId) === null) {
            $this->redirect(admin_url('posts'));
        }

        $published = $request->text('published_at');

        Database::update('posts', [
            'slug'         => $this->uniquePostSlug($request->text('slug'), $postId),
            'cover_image'  => $request->text('cover_image'),
            'status'       => $request->text('status') === 'published' ? 'published' : 'draft',
            'published_at' => $published === '' ? date('Y-m-d H:i:s') : str_replace('T', ' ', $published) . ':00',
        ], '`id` = :id', ['id' => $postId]);

        foreach (array_keys($this->langs) as $code) {
            Post::saveTranslation($postId, $code, [
                'title'            => $request->text("t_{$code}_title"),
                'excerpt'          => $request->text("t_{$code}_excerpt"),
                'body'             => safe_html((string) $request->input("t_{$code}_body", '')),
                'meta_title'       => $request->text("t_{$code}_meta_title"),
                'meta_description' => $request->text("t_{$code}_meta_description"),
            ]);
        }

        Auth::log('update', 'post', $postId);
        $this->saved();
        $this->redirect(admin_url('posts/' . $postId));
    }

    public function deletePost(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);
        $this->requireAdmin();

        $postId = (int) $request->param('id');

        Database::delete('posts', '`id` = :id', ['id' => $postId]);
        Auth::log('delete', 'post', $postId);
        $this->saved('Entrada eliminada.');
        $this->redirect(admin_url('posts'));
    }

    // ------------------------------------------------------------------
    // Preguntas frecuentes
    // ------------------------------------------------------------------

    public function faqs(Request $request): void
    {
        $this->boot($request);

        $faqs = Faq::all(Lang::default());

        foreach ($faqs as $index => $faq) {
            $faqs[$index]['translations'] = Faq::translations((int) $faq['id']);
        }

        $this->view('admin/faqs', ['faqs' => $faqs]);
    }

    public function saveFaqs(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $rows = $request->input('faq', []);

        if (is_array($rows)) {
            foreach ($rows as $faqId => $row) {
                $faqId = (int) $faqId;

                Database::update('faqs', [
                    'position'  => (int) ($row['position'] ?? 0),
                    'is_active' => isset($row['is_active']) ? 1 : 0,
                ], '`id` = :id', ['id' => $faqId]);

                foreach (array_keys($this->langs) as $code) {
                    Faq::saveTranslation($faqId, $code, [
                        'question' => mb_substr((string) ($row['question'][$code] ?? ''), 0, 255),
                        'answer'   => (string) ($row['answer'][$code] ?? ''),
                    ]);
                }
            }
        }

        Auth::log('update', 'faq');
        $this->saved();
        $this->redirect(admin_url('faq'));
    }

    public function addFaq(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $faqId = Database::insert('faqs', [
            'position'  => (int) Database::value('SELECT COALESCE(MAX(`position`),0)+1 FROM `faqs`', [], 1),
            'is_active' => 1,
        ]);

        foreach (array_keys($this->langs) as $code) {
            Faq::saveTranslation($faqId, $code, ['question' => 'Nueva pregunta', 'answer' => '']);
        }

        $this->saved('Pregunta añadida. Complétala y guarda.');
        $this->redirect(admin_url('faq'));
    }

    public function deleteFaq(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        Database::delete('faqs', '`id` = :id', ['id' => (int) $request->param('id')]);
        $this->saved('Pregunta eliminada.');
        $this->redirect(admin_url('faq'));
    }

    // ------------------------------------------------------------------
    // Guía de tallas
    // ------------------------------------------------------------------

    public function sizes(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/sizes', ['guides' => SizeGuide::allWithRows()]);
    }

    public function saveSizes(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $rows = $request->input('row', []);

        if (is_array($rows)) {
            foreach ($rows as $rowId => $row) {
                SizeGuide::updateRow((int) $rowId, [
                    'label'     => mb_substr((string) ($row['label'] ?? ''), 0, 40),
                    'bust'      => mb_substr((string) ($row['bust'] ?? ''), 0, 40),
                    'underbust' => mb_substr((string) ($row['underbust'] ?? ''), 0, 40),
                    'waist'     => mb_substr((string) ($row['waist'] ?? ''), 0, 40),
                    'hip'       => mb_substr((string) ($row['hip'] ?? ''), 0, 40),
                    'eu'        => mb_substr((string) ($row['eu'] ?? ''), 0, 20),
                    'it'        => mb_substr((string) ($row['it'] ?? ''), 0, 20),
                    'uk'        => mb_substr((string) ($row['uk'] ?? ''), 0, 20),
                    'us'        => mb_substr((string) ($row['us'] ?? ''), 0, 20),
                ]);
            }
        }

        Auth::log('update', 'size_guide');
        $this->saved();
        $this->redirect(admin_url('sizes'));
    }

    // ------------------------------------------------------------------
    // Textos de la web
    // ------------------------------------------------------------------

    public function texts(Request $request): void
    {
        $this->boot($request);

        $code = $request->text('lang', Lang::default());

        if (!Lang::isValid($code)) {
            $code = Lang::default();
        }

        $strings = Lang::allStrings($code);
        $groups  = [];

        foreach ($strings as $string) {
            $groups[explode('.', (string) $string['skey'])[0]][] = $string;
        }

        ksort($groups);

        $this->view('admin/texts', [
            'groups'   => $groups,
            'editLang' => $code,
            'labels'   => $this->groupLabels(),
        ]);
    }

    public function saveTexts(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $code  = $request->text('lang', Lang::default());
        $rows  = $request->input('text', []);

        if (!Lang::isValid($code)) {
            $this->redirect(admin_url('texts'));
        }

        if (is_array($rows)) {
            foreach ($rows as $id => $value) {
                Database::update('ui_strings', ['value' => (string) $value],
                    '`id` = :id AND `lang` = :lang', ['id' => (int) $id, 'lang' => $code]);
            }
        }

        Auth::log('update', 'texts', null, 'Idioma: ' . $code);
        $this->saved('Textos guardados.');
        $this->redirect(admin_url('texts?lang=' . $code));
    }

    private function groupLabels(): array
    {
        return [
            'nav'      => 'Menú de navegación',
            'home'     => 'Página de inicio',
            'shop'     => 'Tienda',
            'product'  => 'Ficha de producto',
            'cart'     => 'Carrito',
            'checkout' => 'Proceso de compra',
            'form'     => 'Formularios',
            'footer'   => 'Pie de página',
            'msg'      => 'Mensajes de aviso',
            'cookie'   => 'Banner de cookies',
            'coming'   => 'Página Coming Soon',
            'search'   => 'Buscador',
            'journal'  => 'Journal',
            'error'    => 'Páginas de error',
            'common'   => 'Textos generales',
        ];
    }

    // ------------------------------------------------------------------
    // Biblioteca de imágenes
    // ------------------------------------------------------------------

    public function media(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/media', [
            'media' => Database::all('SELECT * FROM `media` ORDER BY `created_at` DESC LIMIT 200'),
        ]);
    }

    public function uploadMedia(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $file = $_FILES['image'] ?? null;

        if (!is_array($file)) {
            Flash::error('Elige una imagen antes de subirla.');
            $this->redirect(admin_url('media'));
        }

        $result = Uploader::image($file, $request->text('alt'));

        $result['ok'] ? $this->saved($result['message']) : Flash::error($result['message']);
        $this->redirect(admin_url('media'));
    }

    public function deleteMedia(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        Uploader::delete((int) $request->param('id'));
        $this->saved('Imagen eliminada.');
        $this->redirect(admin_url('media'));
    }

    // ------------------------------------------------------------------
    // Suscriptores y mensajes
    // ------------------------------------------------------------------

    public function subscribers(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/subscribers', [
            'subscribers' => Subscriber::all($request->text('status')),
            'status'      => $request->text('status'),
            'total'       => Subscriber::countActive(),
        ]);
    }

    public function exportSubscribers(Request $request): void
    {
        $this->boot($request);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="suscriptores-' . date('Y-m-d') . '.csv"');

        echo "\xEF\xBB\xBF" . Subscriber::toCsv(Subscriber::all('active'));
        exit;
    }

    public function deleteSubscriber(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        Subscriber::delete((int) $request->param('id'));
        $this->saved('Suscriptor eliminado.');
        $this->redirect(admin_url('subscribers'));
    }

    public function messages(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/messages', ['messages' => Message::all()]);
    }

    public function showMessage(Request $request): void
    {
        $this->boot($request);

        $messageId = (int) $request->param('id');
        $message   = Message::find($messageId);

        if ($message === null) {
            Flash::error('Ese mensaje no existe.');
            $this->redirect(admin_url('messages'));
        }

        Message::markRead($messageId);

        $this->view('admin/message-detail', ['message' => $message]);
    }

    public function deleteMessage(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        Message::delete((int) $request->param('id'));
        $this->saved('Mensaje eliminado.');
        $this->redirect(admin_url('messages'));
    }

    private function uniquePostSlug(string $source, int $exceptId = 0): string
    {
        $base = slugify($source);
        $slug = $base;
        $n    = 2;

        while (Database::value(
            'SELECT `id` FROM `posts` WHERE `slug` = :s AND `id` <> :id',
            ['s' => $slug, 'id' => $exceptId]
        )) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }
}
