<template>
    <div style="max-width: 300px; display: flex; align-items: center">
        <img v-if="is_title_field" :src="`https://api.adorable.io/avatars/40/${field.data.split('').join('_')}.png`" alt="" style="margin-right: 24px; border-radius: 50%; flex-shrink: 0; width: 40px; height: 40px">
        

        <span class="truncate">
            {{field.data}}
        </span>
    </div>
</template>

<script>
export default {
    props: ['field', 'item', 'is_title_field']
}
</script>

<style scoped lang="scss">
@import "resources/sass/init";

</style>