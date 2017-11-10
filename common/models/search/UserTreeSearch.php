<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/8
 * Time: 上午10:17
 */

namespace common\models\search;


use common\models\NormalUser;
use common\models\UserTree;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserTreeSearch extends Model
{
    const ORIENTATION_UP = 0;
    const ORIENTATION_DOWN = 1;

    const SCENARIO_FRONTEND = 'frontend';

    // 前端和后端搜索公用变量
    public $orientation = UserTreeSearch::ORIENTATION_DOWN;
    // 后端搜索专用变量
    public $user_id;
    public $depth;
    // 前端搜索专用变量
    public $username;

    public function rules()
    {
        return [
            [['user_id', 'orientation', 'depth'], 'integer'],
            ['username', 'string'],
            ['username', 'required', 'on' => [UserTreeSearch::SCENARIO_FRONTEND]]
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => '用户',
            'orientation' => '方向'
        ];
    }

    public function search($params)
    {
        $this->load($params);
        $this->depth = empty($this->depth) ? 2 : $this->depth;

        $validUserNode = UserTree::findOne(['user_id' => $this->user_id]);
        return $this->basicSearch($validUserNode, $this->depth);
    }

    /**
     * 如果是查找当前用户的系谱图，则设置user_id为当前用户的ID
     * 如果查看其它用户的系谱图，则被查询的用户必须是在当前用户的前后两层中
     *
     * @param array $params
     * @return null|array
     */
    public function frontendSearch($params)
    {
        $this->load($params);

        $currentUserId = \Yii::$app->user->identity->getId();

        $validUserNode = null;
        $currentUserNode = UserTree::findOne(['user_id' => $currentUserId]);
        if ($currentUserId == null) {
            return null;
        }
        if ($this->username == \Yii::$app->user->identity->username) {
            $validUserNode = $currentUserNode;
//            var_dump($validUserNode);
        } elseif (!empty($this->username)) {
            // 查找被查询用户的ID
            $queryUserModel = NormalUser::findOne(['username' => $this->username]);
            if ($queryUserModel === null) {
                return null;
            }
            // 获取被查询用户的node
            $queryUserNode = UserTree::findOne(['user_id' => $queryUserModel->id]);
            if ($queryUserModel === null) {
                return null;
            }
            // 被查询用户是当前用户向下两层以内的子用户
            if ($queryUserNode->isChildOf($currentUserNode) && ($queryUserNode->depth - $currentUserNode->depth) <= 2) {
                $validUserNode = $queryUserNode;
            } else {
                // 判断被查询用户是否是当前用户向上两层以内的子用户
                $parentNode = $currentUserNode->parents(2)->one();
                if ($parentNode == null) {
                    return null;
                }
                if ($queryUserNode->isChildOf($parentNode) && ($queryUserNode->depth - $parentNode->depth) <= 2) {
                    $validUserNode = $queryUserNode;
                }
            }
        }
        if ($validUserNode !== null) {
            return $this->basicSearch($validUserNode, 2);
        }
        return null;
    }

    private function basicSearch($rootUserNode, $depth)
    {
        if ($this->orientation == static::ORIENTATION_UP) {
            $tempNode = $rootUserNode->parents($depth)->one();
            if ($tempNode == null) {
                return null;
            }
            $rootUserNode = $tempNode;
        }
        return [
            'text' => [
                'user_id' => $rootUserNode->user_id,
                'user_name' => NormalUser::getUsername($rootUserNode->user_id),
                'parent_node_id' => 0,
            ],
            'HTMLid' => 'user_tree_node_' . $rootUserNode->id,
            'children' => $this->traverse($rootUserNode, $depth)
        ];
    }

    private function traverse($rootNode, $depth)
    {
        $data = [];
        if ($depth == 0) {
            return [];
        }
        // 获取子节点
        $children = $rootNode->children(1)->all();
        if ($children === null || empty($children)) {
            return [[
                'text' => [
                    'user_id' => 0,
                    'user_name' => '空',
                    'parent_node_id' => $rootNode->id,
                ],
            ]];
        }
        foreach ($children as $child) {
            $data[] = [
                'text' => [
                    'user_id' => $child->user_id,
                    'user_name' => NormalUser::getUsername($child->user_id),
                    'parent_node_id' => $rootNode->id,
                ],
                'HTMLid' => 'user_tree_node_' . $child->id,
                'children' => $this->traverse($child, $depth - 1)
            ];
        }
        return $data;
    }
}