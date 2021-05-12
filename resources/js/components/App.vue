<template>
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <router-link :to="{ name: 'home' }" class="navbar-brand"
            >Laravel-Vue SPA</router-link
        >
            <button
                class="navbar-toggler"
                data-toggle="collapse"
                data-target="#navbarCollapse"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="navbar-nav">
            <li class="nav-item">
                <router-link
                class="nav-link"
                data-toggle="collapse"
                :to="{ path: 'reports' }"
                >
                Audições
                </router-link>
            </li>

            <li class="nav-item">
                <router-link
                class="nav-link"
                data-toggle="collapse"
                :to="{ path: 'report' }"
                >
                About
                </router-link>
            </li>

             <li class="nav-item">
                <router-link
                class="nav-link"
                data-toggle="collapse"
                :to="{ path: 'report-pending' }"
                >
                Audições Pendentes
                </router-link>
            </li>
            </ul>
        </div>
        </nav>

        <div class="container mt-5">
            <router-view></router-view>
        </div>
  </div>
</template>
 
<script>
    export default {}
</script>