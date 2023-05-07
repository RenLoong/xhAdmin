<template>
    <div class="h-100% app-container">
        <div class="info-container">
            <div class="flex items-center">
                <n-button type="warning" @click="toBack">返回</n-button>
            </div>
            <div class="flex items-center">花语旗舰版【平台旗下应用】</div>
        </div>
        <n-grid :cols="5" :y-gap="22" :x-gap="8" class="app-list">
            <n-grid-item class="item" v-for="(item, index) in 3">
                <div class="app-item">
                    <img src="http://demo.dkyx.cc/assets/addons/gs_code/icon.png" class="logo" alt="">
                    <div class="button-group">
                        <div class="action-button" @click="hanldAdmin">管理</div>
                        <div class="action-button" @click="hanldStop">停用</div>
                    </div>
                    <div class="title">WIFI大师专业版</div>
                </div>
            </n-grid-item>
            <n-grid-item class="action" @click="distribution">
                分配应用
            </n-grid-item>
        </n-grid>
    </div>
</template>

<script>
export default {
    methods: {
        distribution() {
            console.log('分配');
        },
        hanldAdmin() {
            console.log('管理');
        },
        hanldStop() {
            console.log('停用');
        },
        toBack() {
            this.$routerApp.back()
        },
    },
}
</script>

<style lang="scss" scoped>
.app-container {
    display: flex;
    flex-direction: column;
    background: #fff;

    .info-container {
        display: flex;
        justify-content: space-between;
        height: 50px;
        padding: 0 20px;
        border-bottom: 1px solid #e5e5e5;
    }

    .app-list {
        padding: 10px;
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;

        .item {
            .app-item {
                position: relative;
                width: 200px;
                height: 200px;

                .logo {
                    border-radius: 5px;
                }

                .button-group {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 32px;
                    color: #fff;
                    display: flex;
                    justify-content: space-between;
                    padding: 8px 10px;
                    user-select: none;
                    cursor: pointer;

                    .action-button {
                        display: none;
                    }
                }

                .button-group:hover {
                    background: rgba(#000000, 0.3);

                    .action-button {
                        display: block;
                    }
                }

                .title {
                    text-align: center;
                    position: absolute;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    padding: 5px 0;
                    color: #fff;
                    background: rgba(#000000, 0.5);
                    user-select: none;
                    border-bottom-left-radius: 5px;
                    border-bottom-right-radius: 5px;
                }
            }
        }


        .action {
            width: 200px;
            height: 200px;
            border: 1px solid #e1e1e1;
            border-radius: 5px;
            cursor: pointer;
        }

        .action:hover {
            background: #f5f5f5;
        }
    }
}
</style>