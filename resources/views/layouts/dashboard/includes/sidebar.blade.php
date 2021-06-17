 <!-- Sidebar menu-->
 <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
 <aside class="app-sidebar">
     <div class="app-sidebar__user">
         <img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image">
         <div>
             <p class="app-sidebar__user-name">{{ auth()->user()->name }}</p>
             <p class="app-sidebar__user-designation">{{ auth()->user()->role }}</p>
         </div>
     </div>
     <ul class="app-menu">
         <li><a class="app-menu__item active" href="index.html"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
         @role('super admin')
         <li class="treeview">
             <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span
                        class="app-menu__label">User Management</span><i
                        class="treeview-indicator fa fa-angle-right"></i></a>
             <ul class="treeview-menu">
                 <li><a class="treeview-item" href="{{ route('users.index') }}"><i class="icon fa fa-users"></i>
                         Members</a></li>
                 <li><a class="treeview-item" href="{{ route('roles.index') }}"><i class="icon fa fa-circle-o"></i> Roles</a>
                 </li>
                 <li><a class="treeview-item" href="{{ route('permissions.index') }}">
                         <i class="icon fa fa-circle-o"></i>
                         Permissions
                     </a>
                 </li>
             </ul>
         </li>
         @endrole
         <li><a class="app-menu__item" href="{{ route('contact.dashboard') }}"><i class="app-menu__icon fa fa-comment"></i><span class="app-menu__label">Messages</span></a></li>
         <li>
             <a class="app-menu__item" href="{{ route('category.dashboard') }}"><i class="app-menu__icon fa fa-cogs"></i><span class="app-menu__label">Categories</span></a>
         </li>
         <li>
             <a class="app-menu__item" href="{{ route('discussion.dashboard') }}"><i class="app-menu__icon fa fa-file"></i><span class="app-menu__label">Discussions</span></a>
         </li>
         <li>
             <a class="app-menu__item" href="{{ route('gallery.album.dashboard') }}"><i class="app-menu__icon fa fa-camera"></i><span class="app-menu__label">Gallery</span></a>
         </li>
         <li>
             <a class="app-menu__item" href="{{ route('programme.dashboard') }}"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Programmes</span></a>
         </li>
         <li>
             <a class="app-menu__item" href="{{ route('presenter.dashboard') }}"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Presenters</span></a>
         </li>
         <li>
             <a class="app-menu__item" href="{{ route('metro.dashboard') }}"><i class="app-menu__icon fa fa-edit"></i><span class="app-menu__label">Metro</span></a>
         </li>
         <li>
             <a class="app-menu__item" href="{{ route('post.dashboard') }}"><i class="app-menu__icon fa fa-edit"></i><span class="app-menu__label">Posts</span></a>
         </li>
         <li><a class="app-menu__item" href="{{ route('newsletter.dashboard') }}"><i class="app-menu__icon fa fa-pencil"></i><span class="app-menu__label">Newsletters</span></a></li>

     </ul>
 </aside>
