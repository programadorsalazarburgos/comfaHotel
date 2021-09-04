<template>
    <li class="nav-item d-md-down-none">
        <a class="nav-link" href="#" data-toggle="dropdown">
            <i class="icon-bell"></i>
          
            <span class="badge badge-pill badge-danger">{{lessons.length}}</span>
        </a>  
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
                <strong>Notificaciones</strong>
            </div>
       
        </div>
    </li>
</template>

<script>
export default {     
    props : ['lessons'],

    
};
</script>