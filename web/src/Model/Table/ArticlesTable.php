<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * テーブル
 */
class ArticlesTable extends Table
{
    /**
     * 初期化
     *
     * @param array $config
     * @return void
     */
    public function initialize(array $config) : void
    {
        $this->addBehavior('Timestamp');
    }

    /**
     * 保存時の前イベント
     *
     * @param [type] $event
     * @param [type] $entity
     * @param [type] $options
     * @return void
     */
    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            // スラグをスキーマで定義されている最大長に調整
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    /**
     * 保存時の入力バリデーション
     *
     * @param Validator $validator
     * @return void
     */
    public function validationDefault(Validator $validator) : Validator
    {
        $validator
            ->allowEmptyString('title', false)
            ->minLength('title', 10)
            ->maxLength('title', 255);

        $validator
            ->allowEmptyString('body', false)
            ->minLength('body', 10);

        return $validator;
    }
}
