<div class="page-sidebar-wrapper">
    <!-- BEGIN RESPONSIVE MENU FOR HORIZONTAL & SIDEBAR MENU -->
    <ul class="page-sidebar-menu visible-sm visible-xs  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">

        
        <?php if(!empty($allMenu['main']) && is_array($allMenu['main'])):?>
        <?php foreach ($allMenu['main'] as $menu): ?>
        <li class="nav-item <?php if (isset($menu['class'])) {echo 'active open';} ?>">
            <a href="<?=\yii\helpers\Url::toRoute($menu['url'])?>" class="nav-link nav-toggle">
                <?=$menu['title']?>
                <?php if (isset($menu['class'])) {echo '<span class="selected"></span>';} ?>
                <span class="arrow <?php if (isset($menu['class'])) {echo 'open';} ?>"> </span>
            </a>
            <?php if (!isset($menu['class'])) { continue;} ?>
            <ul class="sub-menu">
                
                <?php if(!empty($allMenu['child']) && is_array($allMenu['child'])):?>
                <?php foreach ($allMenu['child'] as $menu): ?>
                <li class="nav-item <?php if (isset($menu['class'])) {echo 'active open';} ?>">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="<?=$menu['icon']?>"></i>
                        <span class="title"><?=$menu['name']?></span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <?php if(!empty($menu['_child']) && is_array($menu['_child'])):?>
                        <?php foreach ($menu['_child'] as $v): ?>
                        <li class="nav-item <?php if (isset($v['class'])) {echo 'active open';} ?>">
                            <a href="<?=\yii\helpers\Url::toRoute($v['url'])?>" nav="<?=$v['url']?>" class="nav-link ">
                                <!--<i class="icon-bar-chart"></i>-->
                                <span class="title"><?=$v['title']?></span>
                            </a>
                        </li>
                        <?php endforeach ?>
                        <?php endif ?>
                    </ul>
                </li>
                <?php endforeach ?>
                <?php endif ?>
                
            </ul>
        </li>
        <?php endforeach; ?>
        <?php endif; ?>
        
    </ul>
    <!-- END RESPONSIVE MENU FOR HORIZONTAL & SIDEBAR MENU -->
</div>