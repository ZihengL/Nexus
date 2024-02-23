<template>
    <div id="ProfileDev">
        <Profile :isHimself="false" :idDevl="props.idDevl"/>
    </div>
</template>

<script setup>
    import Profile from '/src/components/Profile.vue';
    import { defineProps } from 'vue';

    const props = defineProps(['idDevl']);

</script>