<template>

<li class="dropdown dropdown-inbox">
                        <a class="nav-link dropdown-toggle toolbar-icon" data-toggle="dropdown" href="#"><i class="ti-bell"></i>
                            <span class="envelope-badge">{{notifications.length}}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-media">
                            <div class="dropdown-arrow"></div>
                            <div class="dropdown-header text-center">
                                <div>
                                    <span class="font-18"><strong>{{notifications.length}}</strong> Notificaciones</span>
                                </div>
                                <a class="text-muted font-13" href="javascript:;">Notificaciones</a>
                            </div>
                            <div class="p-3">
                                <div class="media-list media-list-divider scroller" data-height="350px" data-color="#71808f">
                                    <a class="media p-3" v-for="(lesson, index) in notifications" :key="index.id" v-on:click='MarkAsRead(lesson)'>
                                      
                                        <div class="media-body">
                                            <div class="media-heading">Notificacion {{lesson.data['datos']['id']}}<small class="text-muted float-right">Justo Ahora</small></div>
                                            <div class="font-13 text-muted">{{lesson.data['datos']['created_at']}}</div>
                                            <div class="font-13 mt-1">
                                                <span class="d-inline-flex align-items-center text-primary"><i class="ti-eye mr-2"></i>Ver</span>
                                            </div>
                                        </div>
                                    </a>
                                   
                                </div>
                            </div>
                        </div>
                    </li>


</template>
