<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/14
 * Time: 下午9:29
 */


/**
 * @param string $path
 * @param $data
 * @param $user_id
 * @param $i
 */
function genPath($path, $i, $leafData) {
    if (strlen($path) == $i + 1) {
        return $leafData;
    }
    else {
        $pos = $path[$i+1];
        $data[$pos] = genPath($path, $i+1, $leafData);
        return ['val' => 0, 'child'=> $data];
    }
}


function downGraph($user_id, $level) {
    $data = ['val' => $user_id];
    // 向下, 查找子节点
    $userInfoModels = [['user_id' => 1, 'broker_path' => '010'], ['user_id' => 2, 'broker_path' => '1101']];
    foreach ($userInfoModels as $userInfoModel) {
        $path = $userInfoModel['broker_path'];
        $leaf_user_id = $userInfoModel['user_id'];
        $k = $level - strlen($path);
        if ($k >= 0) {
            $leaf_data = downGraph($leaf_user_id, $k);
            $data = array_merge($data, [$path[0] => genPath(substr($path, 0, $level), 0, $leaf_data)]);
        }
    }

    return $data;
}

//echo json_encode(genPath("01101", 5, 0));

echo json_encode(downGraph(19, 4));