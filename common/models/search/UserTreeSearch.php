<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/8
 * Time: 上午10:17
 */

namespace common\models\search;


use common\helpers\StringHelper;
use common\models\NormalUser;
use common\models\UserTree;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 用户系谱图搜索
 * Class UserTreeSearch
 * @package common\models\search
 */
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
        $this->depth = empty($this->depth) ? 3 : $this->depth;

        if (empty($this->user_id)) {
            return null;
        }
        $validUserNode = UserTree::findOne(['user_id' => $this->user_id]);
        if ($validUserNode == null) {
            return null;
        }
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
        $depth = \Yii::$app->params['user_tree_depth'];
        if ($this->username == \Yii::$app->user->identity->username) {
            $validUserNode = $currentUserNode;
        } elseif (!empty($this->username)) {
            // 查找被查询用户的ID
            $queryUserModel = NormalUser::findOne(['username' => $this->username]);
            if ($queryUserModel === null) {
                return null;
            }
            // 获取被查询用户的node
            $queryUserNode = UserTree::findOne(['user_id' => $queryUserModel->id]);
            if ($queryUserNode === null) {
                return null;
            }
            // 被查询用户是当前用户向下两层以内的子用户
            if ($queryUserNode->isChildOf($currentUserNode) && ($queryUserNode->depth - $currentUserNode->depth) <= 2) {
                $validUserNode = $queryUserNode;
                $depth -= $queryUserNode->depth - $currentUserNode->depth;
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
            return $this->basicSearch($validUserNode, $depth);
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
        $childData = $this->traverse($rootUserNode, $depth);

        return [
            'text' => [
                'user_id' => $rootUserNode->user_id,
                'username' => NormalUser::getUsername($rootUserNode->user_id),
                'parent_node_id' => 0,
                'level_data' => $childData[1]
            ],
            'innerHTML' => $this->convertNodeToHtmlNode([
                'user_id' => $rootUserNode->user_id,
                'username' => NormalUser::getUsername($rootUserNode->user_id),
                'parent_node_id' => 0,
                'level_data' => $childData[1]
            ]),
            'HTMLid' => 'user_tree_node_' . $rootUserNode->id,
            'children' => empty($childData) ? [] : $childData[0]
        ];
    }

    /**
     * 深度递归遍历一个node的子节点
     * @param $rootNode
     * @param $depth
     * @param $parents array
     * @return array [children, l1, l2, l3...]
     */
    private function traverse($rootNode, $depth)
    {
        $data = [];
        if ($depth == 0) {
            return [];
        }
        // 获取子节点
        $children = $rootNode->children(1)->all();
        if ($children === null || empty($children)) {
            return [];
        }
        $cntArr = [];
        foreach ($children as $child) {
            $childData = $this->traverse($child, $depth - 1);
            if (empty($childData)) {
                $childData = [[], []];
            }
            $data[] = [
                'text' => [
                    'user_id' => $child->user_id,
                    'username' => NormalUser::getUsername($child->user_id),
                    'parent_node_id' => $rootNode->id,
                    'level_data' => $childData[1]
                ],
                'innerHTML' => $this->convertNodeToHtmlNode([
                    'user_id' => $child->user_id,
                    'username' => NormalUser::getUsername($child->user_id),
                    'parent_node_id' => $rootNode->id,
                    'level_data' => $childData[1]
                ]),
                'HTMLid' => 'user_tree_node_' . $child->id,
                'children' => $childData[0]
            ];
            for ($i = 0; $i < count($childData[1]); $i++) {
                if (count($cntArr) <= $i) {
                    $cntArr[$i] = 0;
                }
                $cntArr[$i] += $childData[1][$i];
            }
        }
        return [$data, array_merge([count($children)], $cntArr)];
    }

    /**
     * 将节点数据转换成html格式的数据
     */
    private function convertNodeToHtmlNode($data)
    {
        $html = <<<HTML
        <div>用户账号：%s</div>%s
HTML;
        $tableData = '<table align="center" class="table table-user-tree table-hover table-striped">
        <thead>
        <tr>
            <th>层级</th>
            <th>计划加盟商</th>
            <th>当前已加盟</th>
        </tr>
        </thead>
        <tbody>
        %s
        </tbody></table>';

        $trs = '';
        foreach ($data['level_data'] as $key => $value) {
            $key += 1;
            $tr = '<tr>';
            $tr .= '<td>' . $key . '</td>';
            $tr .= '<td>' . pow(\Yii::$app->params['broker_child_cnt'], $key) . '</td>';
            $tr .= '<td>' . $value . '</td>';
            $tr .= '</tr>';
            $trs .= $tr;
        }
        if ($trs === '') {
            $tableData = '暂无加盟商信息';
        } else {
            $tableData = sprintf($tableData, $trs);
        }

        return sprintf($html, $data['username'], $tableData);
    }
}