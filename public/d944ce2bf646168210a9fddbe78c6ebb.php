<?php /*a:4:{s:76:"/www/wwwroot/saas.kf.renloong.com/plugin/ycDigitalHuman/public/pc/voice.html";i:1728620567;s:83:"/www/wwwroot/saas.kf.renloong.com/plugin/ycDigitalHuman/public/pc/modules/main.html";i:1728639750;s:85:"/www/wwwroot/saas.kf.renloong.com/plugin/ycDigitalHuman/public/pc/modules/header.html";i:1728637751;s:83:"/www/wwwroot/saas.kf.renloong.com/plugin/ycDigitalHuman/public/pc/modules/menu.html";i:1728632128;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn" data-theme="<?php echo htmlentities($theme); ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="maximum-scale=1.0, user-scalable=0">
    <meta name="aplus-xplug" content="NONE">
    <title><?php echo htmlentities($system['web_name']); ?></title>
    <link rel="icon" type="image/png" href="<?php echo htmlentities($system['web_logo']); ?>" />
    <meta name="keywords" content="<?php echo htmlentities($system['web_name']); ?>">
    <link rel="shortcut icon" href="<?php echo htmlentities($system['web_logo']); ?>">
    <link rel="apple-touch-icon" href="<?php echo htmlentities($system['web_logo']); ?>">
    <link rel="apple-touch-icon-precomposed" href="<?php echo htmlentities($system['web_logo']); ?>">
    <link rel="stylesheet" href="/app/ycDigitalHuman/pc/static/iconfont.css">
    <link rel="stylesheet" href="/app/ycDigitalHuman/pc/static/theme.min.css">
    <link rel="stylesheet" href="/app/ycDigitalHuman/pc/static/common.min.css">
    <link rel="stylesheet" href="/app/ycDigitalHuman/pc/static/element-plus/index.css">
    <link rel="stylesheet" href="/app/ycDigitalHuman/pc/static/index.css?t=<?php echo time(); ?>">
    <script>
        const __YCCONFIG = {
            appid: '<?php echo htmlentities($appid); ?>',
            domain: '<?php echo htmlentities($system['domain']); ?>',
            gt4: '<?php echo htmlentities($gt4_captcha_id); ?>',
        };
    </script>
</head>

<body>
    <div class="layouts" id="layouts">
        
            <div class="flex flex-center p-4 grid-gap-4">
    <a class="logo" href="<?php echo htmlentities($system['domain']); ?>?appid=<?php echo htmlentities($appid); ?>" alt="<?php echo htmlentities($system['web_name']); ?>">
        <img src="<?php echo htmlentities($system['web_logo']); ?>" class="logo-image" alt="<?php echo htmlentities($system['web_name']); ?>LOGO">
        <span class="logo-text"><?php echo htmlentities($system['web_name']); ?></span>
    </a>
    <div class="flex-1"></div>
    <div class="theme-switch">
        <label class="switch-label">
            <input type="checkbox" class="checkbox" id="themeSwitch" <?php if($theme == 'dark'): ?>checked<?php endif; ?> />
            <span class="slider"></span>
        </label>
    </div>
    <div class="rounded-round bg-active text-white px-6 py-2 flex pointer" @click="login.show()">
        <span>登录</span>
    </div>
</div>
        
        <div class="flex flex-1 overflow-hidden grid-gap-4">
            
                <div class="menu p-4">
    <div class="menu-highlight" id="menuHighlight"></div>
    <ul class="flex flex-column grid-gap-1" id="menu">
        <li class="<?php if(request()->action() == 'index'): ?>active<?php endif; ?>">
            <a href="<?php echo htmlentities($system['domain']); ?>?appid=<?php echo htmlentities($appid); ?>">
                <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill="var(--color2)"
                        d="M8.05561 12.2667C7.59537 12.2667 7.22228 12.6398 7.22228 13.1C7.22228 13.5602 7.59537 13.9333 8.05561 13.9333H11.9445C12.4047 13.9333 12.7778 13.5602 12.7778 13.1C12.7778 12.6398 12.4047 12.2667 11.9445 12.2667H8.05561Z">
                    </path>
                    <path fill="var(--color1)" fill-rule="evenodd" clip-rule="evenodd"
                        d="M11.338 2.83244C10.5201 2.31318 9.4759 2.31318 8.65804 2.83244L3.33249 6.21361C2.46815 6.76238 2.02577 7.78271 2.21602 8.78872L3.48921 15.521C3.71249 16.7017 4.7441 17.5565 5.94567 17.5565H14.0504C15.2519 17.5565 16.2836 16.7017 16.5068 15.521L17.78 8.78872C17.9703 7.78271 17.5279 6.76238 16.6635 6.2136L11.338 2.83244ZM9.55136 4.23947C9.82398 4.06639 10.1721 4.06639 10.4447 4.23947L15.7702 7.62064C16.0583 7.80357 16.2058 8.14368 16.1424 8.47901L14.8692 15.2113C14.7948 15.6049 14.4509 15.8898 14.0504 15.8898H5.94567C5.54514 15.8898 5.20127 15.6049 5.12685 15.2113L3.85366 8.47901C3.79024 8.14368 3.9377 7.80357 4.22582 7.62064L9.55136 4.23947Z">
                    </path>
                </svg>
                <span>首页</span>
            </a>
        </li>
        <li class="tips mt-6">
            <span>声音</span>
        </li>
        <li class="<?php if(request()->action() == 'voice'): ?>active<?php endif; ?>">
            <a href="<?php echo htmlentities($system['domain']); ?>/Index/voice?appid=<?php echo htmlentities($appid); ?>">
                <svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                    <path fill="var(--color2)" d="M469.333333 938.666667V85.333333q0-4.224 0.853334-8.362666 0.768-4.096 2.389333-7.978667 1.621333-3.882667 3.968-7.381333 2.304-3.498667 5.290667-6.485334 2.986667-2.986667 6.442666-5.290666 3.498667-2.346667 7.381334-3.925334 3.882667-1.621333 8.021333-2.432 4.138667-0.853333 8.32-0.853333t8.32 0.853333q4.138667 0.810667 8.021333 2.432 3.84 1.578667 7.381334 3.925334 3.456 2.346667 6.442666 5.290666 2.986667 2.986667 5.290667 6.485334 2.346667 3.498667 3.968 7.381333 1.621333 3.84 2.432 7.978667Q554.666667 81.109333 554.666667 85.333333v853.333334q0 4.181333-0.853334 8.32-0.768 4.096-2.389333 7.978666-1.621333 3.882667-3.968 7.381334-2.304 3.498667-5.290667 6.485333-2.986667 2.986667-6.442666 5.290667-3.498667 2.346667-7.381334 3.925333-3.882667 1.621333-8.021333 2.432-4.138667 0.853333-8.32 0.853333t-8.32-0.853333q-4.138667-0.810667-8.021333-2.432-3.84-1.578667-7.381334-3.925333-3.456-2.346667-6.442666-5.290667-2.986667-2.986667-5.290667-6.485333-2.346667-3.498667-3.968-7.381334-1.621333-3.84-2.432-7.978666Q469.333333 942.848 469.333333 938.666667zm85.333334 0q0 4.181333-0.853334 8.32-0.768 4.096-2.389333 7.978666-1.621333 3.882667-3.968 7.381334-2.304 3.498667-5.290667 6.485333-2.986667 2.986667-6.442666 5.290667-3.498667 2.346667-7.381334 3.925333-3.882667 1.621333-8.021333 2.432-4.138667 0.853333-8.32 0.853333t-8.32-0.853333q-4.138667-0.810667-8.021333-2.432-3.84-1.578667-7.381334-3.925333-3.456-2.346667-6.442666-5.290667-2.986667-2.986667-5.290667-6.485333-2.346667-3.498667-3.968-7.381334-1.621333-3.84-2.432-7.978666Q469.333333 942.848 469.333333 938.666667q0-4.224 0.853334-8.362667 0.768-4.096 2.389333-7.978667 1.621333-3.882667 3.968-7.381333 2.304-3.498667 5.290667-6.485333 2.986667-2.986667 6.442666-5.290667 3.498667-2.346667 7.381334-3.925333 3.882667-1.621333 8.021333-2.432 4.138667-0.853333 8.32-0.853334t8.32 0.853334q4.138667 0.810667 8.021333 2.432 3.84 1.578667 7.381334 3.925333 3.456 2.346667 6.442666 5.290667 2.986667 2.986667 5.290667 6.485333 2.346667 3.498667 3.968 7.381333 1.621333 3.84 2.432 7.978667 0.810667 4.138667 0.810667 8.362667z m0-853.333334q0 4.181333-0.853334 8.32-0.768 4.096-2.389333 7.978667-1.621333 3.882667-3.968 7.381333-2.304 3.498667-5.290667 6.485334-2.986667 2.986667-6.442666 5.290666-3.498667 2.346667-7.381334 3.925334-3.882667 1.621333-8.021333 2.432Q516.181333 128 512 128t-8.32-0.853333q-4.138667-0.810667-8.021333-2.432-3.84-1.578667-7.381334-3.925334-3.456-2.346667-6.442666-5.290666-2.986667-2.986667-5.290667-6.485334-2.346667-3.498667-3.968-7.381333-1.621333-3.84-2.432-7.978667Q469.333333 89.514667 469.333333 85.333333q0-4.224 0.853334-8.362666 0.768-4.096 2.389333-7.978667 1.621333-3.882667 3.968-7.381333 2.304-3.498667 5.290667-6.485334 2.986667-2.986667 6.442666-5.290666 3.498667-2.346667 7.381334-3.925334 3.882667-1.621333 8.021333-2.432 4.138667-0.853333 8.32-0.853333t8.32 0.853333q4.138667 0.810667 8.021333 2.432 3.84 1.578667 7.381334 3.925334 3.456 2.346667 6.442666 5.290666 2.986667 2.986667 5.290667 6.485334 2.346667 3.498667 3.968 7.381333 1.621333 3.84 2.432 7.978667Q554.666667 81.109333 554.666667 85.333333z"></path>
                    <path fill="var(--color1)" d="M682.666667 768V256q0-4.181333 0.853333-8.32 0.768-4.138667 2.389333-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290667-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021333-2.432Q721.152 213.333333 725.333333 213.333333t8.32 0.853334q4.138667 0.768 8.021334 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290666 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333Q768 251.818667 768 256v512q0 4.224-0.853333 8.32-0.768 4.138667-2.389334 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290666 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021334 2.432Q729.514667 810.666667 725.333333 810.666667t-8.32-0.853334q-4.138667-0.768-8.021333-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290667-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q682.666667 772.266667 682.666667 768z m85.333333 0q0 4.224-0.853333 8.32-0.768 4.138667-2.389334 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290666 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021334 2.432Q729.514667 810.666667 725.333333 810.666667t-8.32-0.853334q-4.138667-0.768-8.021333-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290667-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q682.666667 772.266667 682.666667 768t0.853333-8.32q0.768-4.138667 2.389333-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290667-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021333-2.432Q721.152 725.333333 725.333333 725.333333t8.32 0.853334q4.138667 0.768 8.021334 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290666 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333Q768 763.733333 768 768z m0-512q0 4.181333-0.853333 8.32-0.768 4.138667-2.389334 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290666 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021334 2.432Q729.514667 298.666667 725.333333 298.666667t-8.32-0.853334q-4.138667-0.768-8.021333-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290667-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q682.666667 260.181333 682.666667 256t0.853333-8.32q0.768-4.138667 2.389333-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290667-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021333-2.432Q721.152 213.333333 725.333333 213.333333t8.32 0.853334q4.138667 0.768 8.021334 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290666 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333Q768 251.818667 768 256zM42.666667 640V384q0-4.181333 0.853333-8.32 0.768-4.138667 2.389333-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290667-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021333-2.432Q81.152 341.333333 85.333333 341.333333t8.32 0.853334q4.138667 0.768 8.021334 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290666 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333Q128 379.818667 128 384v256q0 4.181333-0.853333 8.32-0.768 4.138667-2.389334 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290666 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021334 2.432Q89.514667 682.666667 85.333333 682.666667t-8.32-0.853334q-4.138667-0.768-8.021333-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290667-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q42.666667 644.181333 42.666667 640z m85.333333 0q0 4.181333-0.853333 8.32-0.768 4.138667-2.389334 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290666 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021334 2.432Q89.514667 682.666667 85.333333 682.666667t-8.32-0.853334q-4.138667-0.768-8.021333-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290667-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q42.666667 644.181333 42.666667 640t0.853333-8.32q0.768-4.138667 2.389333-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290667-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021333-2.432Q81.152 597.333333 85.333333 597.333333t8.32 0.853334q4.138667 0.768 8.021334 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290666 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333Q128 635.818667 128 640z m0-256q0 4.181333-0.853333 8.32-0.768 4.138667-2.389334 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290666 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021334 2.432Q89.514667 426.666667 85.333333 426.666667t-8.32-0.853334q-4.138667-0.768-8.021333-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290667-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q42.666667 388.181333 42.666667 384t0.853333-8.32q0.768-4.138667 2.389333-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290667-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021333-2.432Q81.152 341.333333 85.333333 341.333333t8.32 0.853334q4.138667 0.768 8.021334 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290666 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333Q128 379.818667 128 384zM896 640V384q0-4.181333 0.853333-8.32 0.768-4.138667 2.389334-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290666-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021334-2.432Q934.485333 341.333333 938.666667 341.333333t8.32 0.853334q4.138667 0.768 8.021333 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290667 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333Q981.333333 379.818667 981.333333 384v256q0 4.181333-0.853333 8.32-0.768 4.138667-2.389333 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290667 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021333 2.432Q942.848 682.666667 938.666667 682.666667t-8.32-0.853334q-4.138667-0.768-8.021334-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290666-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q896 644.181333 896 640z m85.333333 0q0 4.181333-0.853333 8.32-0.768 4.138667-2.389333 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290667 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021333 2.432Q942.848 682.666667 938.666667 682.666667t-8.32-0.853334q-4.138667-0.768-8.021334-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290666-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q896 644.181333 896 640t0.853333-8.32q0.768-4.138667 2.389334-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290666-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021334-2.432Q934.485333 597.333333 938.666667 597.333333t8.32 0.853334q4.138667 0.768 8.021333 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290667 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333 0.810667 4.138667 0.810666 8.32z m0-256q0 4.181333-0.853333 8.32-0.768 4.138667-2.389333 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290667 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021333 2.432Q942.848 426.666667 938.666667 426.666667t-8.32-0.853334q-4.138667-0.768-8.021334-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290666-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q896 388.181333 896 384t0.853333-8.32q0.768-4.138667 2.389334-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290666-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021334-2.432Q934.485333 341.333333 938.666667 341.333333t8.32 0.853334q4.138667 0.768 8.021333 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290667 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333Q981.333333 379.818667 981.333333 384zM256 768V256q0-4.181333 0.853333-8.32 0.768-4.138667 2.389334-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290666-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021334-2.432Q294.485333 213.333333 298.666667 213.333333t8.32 0.853334q4.138667 0.768 8.021333 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290667 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333Q341.333333 251.818667 341.333333 256v512q0 4.224-0.853333 8.32-0.768 4.138667-2.389333 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290667 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021333 2.432Q302.848 810.666667 298.666667 810.666667t-8.32-0.853334q-4.138667-0.768-8.021334-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290666-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q256 772.266667 256 768z m85.333333 0q0 4.224-0.853333 8.32-0.768 4.138667-2.389333 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290667 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021333 2.432Q302.848 810.666667 298.666667 810.666667t-8.32-0.853334q-4.138667-0.768-8.021334-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290666-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q256 772.266667 256 768t0.853333-8.32q0.768-4.138667 2.389334-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290666-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021334-2.432Q294.485333 725.333333 298.666667 725.333333t8.32 0.853334q4.138667 0.768 8.021333 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290667 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333Q341.333333 763.733333 341.333333 768zM341.333333 256q0 4.181333-0.853333 8.32-0.768 4.138667-2.389333 8.021333-1.621333 3.84-3.968 7.381334-2.304 3.456-5.290667 6.442666-2.986667 2.986667-6.442667 5.290667-3.498667 2.346667-7.381333 3.968-3.882667 1.621333-8.021333 2.432Q302.848 298.666667 298.666667 298.666667t-8.32-0.853334q-4.138667-0.768-8.021334-2.389333-3.84-1.621333-7.381333-3.968-3.456-2.304-6.442667-5.290667-2.986667-2.986667-5.290666-6.442666-2.346667-3.498667-3.968-7.381334-1.621333-3.882667-2.432-8.021333Q256 260.181333 256 256t0.853333-8.32q0.768-4.138667 2.389334-8.021333 1.621333-3.84 3.968-7.381334 2.304-3.456 5.290666-6.442666 2.986667-2.986667 6.442667-5.290667 3.498667-2.346667 7.381333-3.968 3.882667-1.621333 8.021334-2.432Q294.485333 213.333333 298.666667 213.333333t8.32 0.853334q4.138667 0.768 8.021333 2.389333 3.84 1.621333 7.381333 3.968 3.456 2.304 6.442667 5.290667 2.986667 2.986667 5.290667 6.442666 2.346667 3.498667 3.968 7.381334 1.621333 3.882667 2.432 8.021333Q341.333333 251.818667 341.333333 256z"></path>
                </svg>
                <span>克隆声音</span>
            </a>
        </li>
        <li class="tips mt-6">
            <span>创作</span>
        </li>
        <li class="<?php if(request()->action() == 'record'): ?>active<?php endif; ?>">
            <a href="<?php echo htmlentities($system['domain']); ?>/Index/record?appid=<?php echo htmlentities($appid); ?>">
                <svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" width="22" height="22">
                    <path fill="var(--color1)" d="M817.152 164.608H225.28a76.8 76.8 0 0 0-76.8 76.8v406.016a76.8 76.8 0 0 0 76.8 76.8h591.872a76.8 76.8 0 0 0 76.8-76.8V241.408a76.8 76.8 0 0 0-76.8-76.8z m25.6 482.816a25.6 25.6 0 0 1-25.6 25.6H225.28a25.6 25.6 0 0 1-25.6-25.6V241.408a25.6 25.6 0 0 1 25.6-25.6h591.872a25.6 25.6 0 0 1 25.6 25.6zM757.248 830.976H256a25.6 25.6 0 1 0 0 51.2h501.248a25.6 25.6 0 0 0 0-51.2z"></path>
                    <path fill="var(--color2)" d="M633.344 400.64l-128-82.176a55.552 55.552 0 0 0-58.112-6.144 57.088 57.088 0 0 0-32.768 51.2v166.4a56.832 56.832 0 0 0 56.32 58.88 55.808 55.808 0 0 0 34.048-11.52l128-82.176 2.56-1.536a58.624 58.624 0 0 0 0-90.368z m-29.184 51.2l-128 81.664-2.56 1.792a3.84 3.84 0 0 1-4.864 0 6.144 6.144 0 0 1-3.584-6.144v-164.608a6.656 6.656 0 0 1 3.584-6.144h2.048a4.096 4.096 0 0 1 2.816 0l2.56 1.792 128 81.664a6.656 6.656 0 0 1 1.792 4.608 6.912 6.912 0 0 1-1.792 5.632z"></path>
                </svg>
                <span>作品管理</span>
            </a>
        </li>
        <li class="tips mt-4">
            <hr class="">
        </li>
        <li class="<?php if(request()->action() == 'user'): ?>active<?php endif; ?>">
            <a href="<?php echo htmlentities($system['domain']); ?>/Index/user?appid=<?php echo htmlentities($appid); ?>">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill="var(--color1)" fill-rule="evenodd" clip-rule="evenodd"
                        d="M10.0018 2.28723C7.08695 2.28723 4.72401 4.65017 4.72401 7.56501C4.72401 10.4798 7.08695 12.8428 10.0018 12.8428C12.9166 12.8428 15.2796 10.4798 15.2796 7.56501C15.2796 4.65017 12.9166 2.28723 10.0018 2.28723ZM6.39068 7.56501C6.39068 5.57065 8.00743 3.9539 10.0018 3.9539C11.9962 3.9539 13.6129 5.57065 13.6129 7.56501C13.6129 9.55937 11.9962 11.1761 10.0018 11.1761C8.00743 11.1761 6.39068 9.55937 6.39068 7.56501Z">
                    </path>
                    <path fill="var(--color2)"
                        d="M13.9225 14.3878C12.7067 13.9484 11.3582 13.7225 9.99659 13.7233C8.63495 13.7241 7.28703 13.9516 6.07219 14.3924C4.85915 14.8325 3.79604 15.4795 2.99866 16.2979C2.67749 16.6275 2.68436 17.1551 3.01401 17.4763C3.34366 17.7975 3.87125 17.7906 4.19242 17.4609C4.78351 16.8543 5.61823 16.3301 6.64066 15.9591C7.66129 15.5888 8.81594 15.3907 9.99756 15.39C11.1792 15.3893 12.3344 15.586 13.3561 15.9552C14.3797 16.3251 15.216 16.8484 15.8089 17.4546C16.1307 17.7837 16.6583 17.7895 16.9873 17.4677C17.3164 17.1459 17.3222 16.6183 17.0004 16.2893C16.2011 15.472 15.1366 14.8264 13.9225 14.3878Z">
                    </path>
                </svg>
                <span>个人中心</span>
            </a>
        </li>
    </ul>
    <div class="flex-1"></div>
    <?php if(!(empty($system['web_service']) || (($system['web_service'] instanceof \think\Collection || $system['web_service'] instanceof \think\Paginator ) && $system['web_service']->isEmpty()))): ?>
    <div class="flex-shrink-0 pt-6 pl-6">
        <img src="<?php echo htmlentities($system['web_service']); ?>" class="rounded-4" alt="客服" style="max-width: 80%;height: auto;">
    </div>
    <?php endif; ?>
    <div class="py-2  pl-6 h10 text-info">
        <span class="py-2">&copy;<?php echo date("Y"); ?> </span>
        <a href="<?php echo htmlentities($system['domain']); ?>?appid=<?php echo htmlentities($appid); ?>">
            <span class="text-info"><?php echo htmlentities($system['web_name']); ?></span>
        </a>
    </div>
</div>
            
            <div class="flex-1 overflow-y-scroll overflow-x-auto rounded-top-left-4">
                
<div style="height:200vh">
</div>

            </div>
        </div>
    </div>
    <script src="/app/ycDigitalHuman/pc/static/qrcode.min.js"></script>
    <script src="/app/ycDigitalHuman/pc/static/axios.min.js"></script>
    <script src="/app/ycDigitalHuman/pc/static/gt4.js"></script>
    <script src="/app/ycDigitalHuman/pc/static/vue.global.js"></script>
    <script src="/app/ycDigitalHuman/pc/static/element-plus/index.js"></script>
    <script src="/app/ycDigitalHuman/pc/static/element-plus/icons.js"></script>
    <script src="/app/ycDigitalHuman/pc/static/index.js"></script>
    
<script src="/app/ycDigitalHuman/pc/static/page/index.js"></script>

</body>

</html>