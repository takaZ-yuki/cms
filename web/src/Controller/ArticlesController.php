<?php

// src/Controller/ArticlesController.php

namespace App\Controller;

use Cake\Event\Event;
use App\Controller\AppController;

/**
 * コントロール
 */
class ArticlesController extends AppController
{

    /**
     * 初期化
     *
     * @return void
     */
    public function initialize() : void
    {
        parent::initialize();

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // FlashComponent をインクルード
    }

    /**
     * 一覧画面表示
     *
     * @return void
     */
    public function index()
    {
        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }

    /**
     * 閲覧画面
     *
     * @param [type] $slug
     * @return void
     */
    public function view($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }

    /**
     * 保存
     *
     * @return void
     */
    public function add()
    {
        $article = $this->Articles->newEmptyEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            // user_id の決め打ちは一時的なもので、あとで認証を構築する際に削除されます。
            $article->user_id = 1;

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        // タグのリストを取得
        $tags = $this->Articles->Tags->find('list');

        // ビューコンテキストにtagsをセット
        $this->set('tags', $tags);

        $this->set('article', $article);
    }

    /**
     * 編集
     *
     * @param [type] $slug
     * @return void
     */
    public function edit($slug)
    {
        $article = $this->Articles->findBySlug($slug)->contain('Tags')->firstOrFail();
        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)){
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }
        // タグのリストを取得
        $tags = $this->Articles->Tags->find('list');

        // ビューコンテキストにtagsをセット
        $this->set('tags', $tags);

        $this->set('article', $article);
    }

    /**
     * 削除
     *
     * @param [type] $slug
     * @return void
     */
    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }

    // /**
    //  * タグ一覧
    //  *
    //  * @return void
    //  */
    // public function tags()
    // {
    //     // 'pass' キーは CakePHP によって提供され、リクエストに渡された
    //     // 全ての URL パスセグメントを含みます。
    //     $tags = $this->request->getParam('pass');

    //     // ArticlesTable を使用してタグ付きの記事を検索します。
    //     $articles = $this->Articles->find('tagged', [
    //         'tags' => $tags
    //     ]);

    //     // 変数をビューテンプレートのコンテキストに渡します。
    //     $this->set([
    //         'articles' => $articles,
    //         'tags' => $tags
    //     ]);
    // }

    /**
     * タグ一覧
     *
     * @return void
     */
    public function tags(...$tags)
    {
        // ArticlesTable を使用してタグ付きの記事を検索します。
        $articles = $this->Articles->find('tagged', [
            'tags' => $tags
        ]);

        // 変数をビューテンプレートのコンテキストに渡します。
        $this->set([
            'articles' => $articles,
            'tags' => $tags
        ]);
    }
}
?>
