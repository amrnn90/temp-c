<template>
 
 <div>
     <admin-header />

     <admin-sidebar />

     <main class="main">
         <router-view />
     </main>

     <portal-target name="modals" />
     <flash-messages />
 </div>
    
</template>
<style scoped lang="scss">
@import "resources/sass/init";

.main {
    padding-top: $header-height + $main-vertical;
    padding-left: $sidebar-width + $main-horizontal;
    padding-right: $main-horizontal;
}
</style>

<script>
export default {
};
</script>