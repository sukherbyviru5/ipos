<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <a href="/" class="navbar-brand sidebar-gone-hide">
        {{ App\Models\SettingApp::get()->nama_madrasah }}
    </a>
    <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>

    <form class="form-inline ml-auto">
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="nav-item">
            <a href="#" class="nav-link nav-link-lg" id="btn-fullscreen" title="Toggle Fullscreen">
                <i class="fas fa-expand" id="fullscreen-icon"></i>
            </a>
        </li>
    </ul>
</nav>
