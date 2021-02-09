<template>
    <div>
        <a-popover placement="bottom">
            <template #content>
            <p><a-button type="link">退出</a-button></p>
            <p><a-button type="link">退出站点</a-button> </p>
            </template>
            <template #title>
                <span>操作</span>
            </template>
             <div class=" user-info-top">
                <img src="../../assets/logo.png" />May
            </div>
        </a-popover>

    </div>
</template>
<script>
export default {
    data() {
        return {};
    },
};
</script>
