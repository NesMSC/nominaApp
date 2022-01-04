  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img style="border-radius: 95%" src="img/upteb.ico" alt="UPTEB">
        </div>
        <div class="info">
          <span href="#" class="d-block text-light">{{$persona->nombres.' '.$persona->apellidos}}</span>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="#" @click="menu=0" class="nav-link" :class="menu==0?'active':''">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Escritorio
              </p>
            </a>  
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link" :class="(menu==1 || menu==2 || menu==3)?'active':''">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Trabajadores
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" @click.prevent="menu=1" class="nav-link" :class="menu==1?'active':''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Administrativo</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" @click.prevent="menu=2" class="nav-link" :class="menu==2?'active':''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Docente</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" @click.prevent="menu=3" class="nav-link" :class="menu==3?'active':''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Obrero</p>
                </a>
              </li>
            </ul>
          </li>
          @if(Auth::user()->role_id == 1)
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link" :class="(menu==4 || menu==5 || menu==6)?'active':''">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Salarios
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" @click.prevent="menu=4" class="nav-link" :class="menu==4?'active':''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tabuladores</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" @click.prevent="menu=5" class="nav-link" :class="menu==5?'active':''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Beneficios</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" @click.prevent="menu=6" class="nav-link" :class="menu==6?'active':''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Descuentos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" @click.prevent="menu='bc'" class="nav-link" :class="menu=='bc'?'active':''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bancos</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" @click.prevent="menu=7" class="nav-link" :class="menu==7?'active':''">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                Nominas
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link" :class="(menu==8 || menu==9)?'active':''">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Acceso
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" @click.prevent="menu=8" class="nav-link" :class="menu==8?'active':''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" @click.prevent="menu=9" class="nav-link" :class="menu==9?'active':''">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Usuarios</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          <li class="nav-item has-treeview">
            <a href="#" @click.prevent="menu=10" class="nav-link" :class="menu==10?'active':''">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Respaldo
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>